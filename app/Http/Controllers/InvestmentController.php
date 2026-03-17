<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\InvestmentPlan;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvestmentController extends Controller
{
    // Show all plans
    public function plans()
    {
        $plans = InvestmentPlan::active()->get();
        return view('investments.plans', compact('plans'));
    }

    // Show invest form for a specific plan
    public function showInvestForm(InvestmentPlan $plan)
    {
        $user = Auth::user();

        if (!$user->isKycVerified()) {
            return redirect()->route('kyc')->with('warning', 'Participate karne ke liye pehle KYC complete karein.');
        }

        return view('investments.invest', compact('plan', 'user'));
    }

    // Process investment (after payment success)
    public function store(Request $request)
    {
        $request->validate([
            'plan_id'        => 'required|exists:investment_plans,id',
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|in:upi,netbanking,card,imps,neft,wallet',
        ]);

        $user = Auth::user();
        $plan = InvestmentPlan::findOrFail($request->plan_id);

        if (!$user->isKycVerified()) {
            return back()->with('error', 'KYC verify hone ke baad participate kar sakte hain.');
        }

        if ($request->amount < $plan->min_amount) {
            return back()->with('error', "Minimum contribution ₹" . number_format($plan->min_amount) . " hona chahiye.");
        }

        if ($plan->max_amount && $request->amount > $plan->max_amount) {
            return back()->with('error', "Maximum contribution ₹" . number_format($plan->max_amount) . " se zyada nahi ho sakta.");
        }

        // Calculate returns
        $calc = $plan->calculateProfit($request->amount);

        if ($request->payment_method === 'wallet' && $user->wallet_balance < $request->amount) {
            return back()->with('error', 'Wallet balance kam hai. Pehle wallet top-up karein.');
        }

        DB::beginTransaction();
        try {
            $investment = Investment::create([
                'user_id'           => $user->id,
                'plan_id'           => $plan->id,
                'principal_amount'  => $calc['principal'],
                'expected_profit'   => $calc['gross_profit'],
                'commission_amount' => $calc['commission_amount'],
                'net_profit'        => $calc['net_profit'],
                'status'            => 'active',
                'invested_at'       => now(),
                'maturity_date'     => now()->addMonths($plan->duration_months),
            ]);

            // Wallet payment handled instantly
            if ($request->payment_method === 'wallet') {
                $user->decrement('wallet_balance', $calc['principal']);
            }

            // Record deposit transaction
            Transaction::create([
                'user_id'        => $user->id,
                'investment_id'  => $investment->id,
                'type'           => 'deposit',
                'amount'         => $calc['principal'],
                'payment_method' => $request->payment_method,
                'payment_id'     => $request->payment_method === 'wallet' ? 'WALLET_' . uniqid() : ($request->payment_id ?? 'MANUAL_' . uniqid()),
                'gateway_order_id' => $request->razorpay_order_id ?? null,
                'status'         => 'completed',
                'notes'          => $request->payment_method === 'wallet' ? "Participation in {$plan->name} via wallet" : "Participation in {$plan->name}",
            ]);

            DB::commit();

            return redirect()->route('investments.my')
                ->with('success', "Participation successful! ₹" . number_format($calc['principal']) . " contribute ho gaya. Maturity: " . now()->addMonths($plan->duration_months)->format('d M Y'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Kuch gadbad ho gayi. Dobara try karein. Error: ' . $e->getMessage());
        }
    }

    // My investments list
    public function myInvestments()
    {
        $user = Auth::user();

        // Auto-settle matured investments directly to wallet
        $this->autoSettleMaturedInvestments($user);

        $investments = $user->investments()
            ->with('plan', 'withdrawal')
            ->latest()
            ->paginate(10);

        $stats = [
            'total_invested' => $user->investments()->sum('principal_amount'),
            'active_count'   => $user->investments()->where('status', 'active')->count(),
            'total_profit'   => $user->investments()->where('status', 'withdrawn')->sum('net_profit'),
            'matured_count'  => $user->investments()->where('status', 'matured')->count(),
        ];

        return view('investments.my', compact('investments', 'stats'));
    }


    private function autoSettleMaturedInvestments($user): void
    {
        $maturedInvestments = $user->investments()
            ->where('status', 'active')
            ->whereDate('maturity_date', '<=', now())
            ->get();

        foreach ($maturedInvestments as $investment) {
            DB::transaction(function () use ($user, $investment) {
                $totalReturn = $investment->principal_amount + $investment->net_profit;

                $updated = Investment::where('id', $investment->id)
                    ->where('status', 'active')
                    ->update([
                        'status'       => 'withdrawn',
                        'withdrawn_at' => now(),
                        'actual_profit'=> $investment->net_profit,
                        'admin_notes'  => 'Auto-settled to wallet on maturity',
                    ]);

                if (!$updated) {
                    return;
                }

                $user->increment('wallet_balance', $totalReturn);

                Transaction::create([
                    'user_id'       => $user->id,
                    'investment_id' => $investment->id,
                    'type'          => 'refund',
                    'amount'        => $investment->principal_amount,
                    'payment_method'=> 'wallet',
                    'status'        => 'completed',
                    'notes'         => 'Principal auto-credited to wallet on maturity',
                ]);

                Transaction::create([
                    'user_id'       => $user->id,
                    'investment_id' => $investment->id,
                    'type'          => 'profit',
                    'amount'        => $investment->net_profit,
                    'payment_method'=> 'wallet',
                    'status'        => 'completed',
                    'notes'         => 'Profit auto-credited to wallet on maturity',
                ]);
            });
        }
    }

    // AJAX: calculate profit
    public function calculate(Request $request)
    {
        $plan   = InvestmentPlan::findOrFail($request->plan_id);
        $amount = (float) $request->amount;

        $grossProfit      = ($amount * $plan->roi_percent / 100) * ($plan->duration_months / 12);
        $commissionAmount = $grossProfit * $plan->commission_percent / 100;
        $netProfit        = $grossProfit - $commissionAmount;
        $totalReturn      = $amount + $netProfit;

        return response()->json([
            'principal'         => $amount,
            'gross_profit'      => round($grossProfit, 2),
            'commission_amount' => round($commissionAmount, 2),
            'net_profit'        => round($netProfit, 2),
            'total_return'      => round($totalReturn, 2),
        ]);
    }
}
