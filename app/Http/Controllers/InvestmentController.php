<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\InvestmentPlan;
use App\Models\Transaction;
use App\Services\InvestmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvestmentController extends Controller
{
    public function plans()
    {
        $plans = InvestmentPlan::active()->get();
        return view('investments.plans', compact('plans'));
    }

    public function showInvestForm(InvestmentPlan $plan)
    {
        $user = Auth::user();

        if (!$user->isKycVerified()) {
            return redirect()->route('kyc')->with('warning', 'Participate karne ke liye pehle KYC complete karein.');
        }

        return view('investments.invest', compact('plan', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_id'        => 'required|exists:investment_plans,id',
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|in:upi,netbanking,card,imps,neft,wallet,razorpay',
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

        // Calculate returns — daily values store karo, full period nahi
        $dailyEarning    = ($request->amount / $plan->min_amount) * $plan->displayDailyEarning();
        $dailyFee        = $dailyEarning * ($plan->commission_percent / 100);
        $netDailyEarning = $dailyEarning - $dailyFee;
        $days            = $plan->duration_months * 30;

        $calc = [
            'principal'         => $request->amount,
            'gross_profit'      => round($dailyEarning, 2),      // ← daily gross (not total)
            'commission_amount' => round($dailyFee, 2),           // ← daily fee (not total)
            'net_profit'        => round($netDailyEarning, 2),   // ← daily net (not total)
            'total_return'      => round($request->amount + ($netDailyEarning * $days), 2),
        ];

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

            if ($request->payment_method === 'wallet') {
                $user->decrement('wallet_balance', $calc['principal']);
            }

            Transaction::create([
                'user_id'          => $user->id,
                'investment_id'    => $investment->id,
                'type'             => 'deposit',
                'amount'           => $calc['principal'],
                'payment_method'   => $request->payment_method,
                'payment_id'       => $request->payment_method === 'wallet' ? 'WALLET_' . uniqid() : ($request->payment_id ?? 'MANUAL_' . uniqid()),
                'gateway_order_id' => $request->razorpay_order_id ?? null,
                'status'           => 'completed',
                'notes'            => $request->payment_method === 'wallet' ? "Participation in {$plan->name} via wallet" : "Participation in {$plan->name}",
            ]);

            DB::commit();

            return redirect()->route('investments.my')
                ->with('success', "Participation successful! ₹" . number_format($calc['principal']) . " contribute ho gaya. Maturity: " . now()->addMonths($plan->duration_months)->format('d M Y'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Kuch gadbad ho gayi. Dobara try karein. Error: ' . $e->getMessage());
        }
    }

    public function myInvestments()
    {
        $user = Auth::user();

        InvestmentService::autoSettleMaturedInvestments($user);

        $investments = $user->investments()
            ->with('plan', 'withdrawal')
            ->latest()
            ->paginate(10);

        $stats = [
            'total_invested' => $user->investments()->sum('principal_amount'),
            'active_count'   => $user->investments()->where('status', 'active')->count(),
            'total_profit' => $user->investments()->where('status', 'withdrawn')->sum('actual_profit'),
            'matured_count' => $user->investments()->whereIn('status', ['active', 'matured'])->count(),
        ];

        return view('investments.my', compact('investments', 'stats'));
    }

    // AJAX: calculate profit
    public function calculate(Request $request)
    {

        $request->validate([
            'plan_id' => 'required|exists:investment_plans,id',
            'amount'  => 'required|numeric|min:1',
        ]);

        $plan   = InvestmentPlan::findOrFail($request->plan_id);
        $amount = (float) $request->amount;

        $dailyEarning     = ($amount / $plan->min_amount) * $plan->displayDailyEarning();
        $dailyFee         = $dailyEarning * ($plan->commission_percent / 100);
        $netDailyEarning  = $dailyEarning - $dailyFee;
        $days             = $plan->duration_months * 30;
        $totalNetEarnings = $netDailyEarning * $days;
        $totalReturn      = $amount + $totalNetEarnings;

        return response()->json([
            'principal'         => $amount,
            'daily_earning'     => round($dailyEarning, 2),
            'daily_fee'         => round($dailyFee, 2),
            'net_daily_earning' => round($netDailyEarning, 2),
            'days'              => $days,
            'total_earnings'    => round($totalNetEarnings, 2),
            'total_return'      => round($totalReturn, 2),
        ]);
    }
}
