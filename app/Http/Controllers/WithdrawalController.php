<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Active + matured dono dikho — jo pending request nahi hai
        $availableInvestments = $user->investments()
            ->with('plan')
            ->whereIn('status', ['active', 'matured'])
            ->whereDoesntHave('withdrawal', fn($q) => $q->whereIn('status', ['pending', 'processing', 'completed']))
            ->get();

        $withdrawals = $user->withdrawals()
            ->with('investment.plan')
            ->latest()
            ->paginate(10);

        return view('withdrawals.index', compact('user', 'availableInvestments', 'withdrawals'));
    }

    public function request(Request $request)
    {
        $request->validate([
            'investment_id' => 'required|exists:investments,id',
            'bank_account'  => 'required|string|max:20',
            'bank_ifsc'     => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/i',
            'bank_name'     => 'required|string|max:100',
        ]);

        $user       = Auth::user();
        $investment = Investment::where('id', $request->investment_id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'matured'])
            ->firstOrFail();

        if ($investment->withdrawal && in_array($investment->withdrawal->status, ['pending', 'processing', 'completed'])) {
            return back()->with('error', 'Is investment ke liye withdrawal request already exist karti hai.');
        }

        // ✅ Actual days se calculate karo
        $daysInvested    = (int) $investment->invested_at->startOfDay()->diffInDays(now()->startOfDay());
        $dailyNet        = $investment->net_profit;        // ab daily value hai
        $dailyFee        = $investment->commission_amount; // ab daily fee hai
        $dailyGross      = $investment->expected_profit;   // ab daily gross hai

        $actualNetProfit    = round($dailyNet * $daysInvested, 2);
        $actualCommission   = round($dailyFee * $daysInvested, 2);
        $actualGrossProfit  = round($dailyGross * $daysInvested, 2);
        $total              = round($investment->principal_amount + $actualNetProfit, 2);

        // Same day withdraw — sirf principal wapas
        if ($daysInvested === 0) {
            $actualNetProfit  = 0;
            $actualCommission = 0;
            $actualGrossProfit = 0;
            $total            = $investment->principal_amount;
        }

        DB::beginTransaction();
        try {
            Withdrawal::create([
                'user_id'          => $user->id,
                'investment_id'    => $investment->id,
                'principal_amount' => $investment->principal_amount,
                'net_profit'       => $actualNetProfit,
                'total_amount'     => $total,
                'bank_account'     => $request->bank_account,
                'bank_ifsc'        => strtoupper($request->bank_ifsc),
                'bank_name'        => $request->bank_name,
                'status'           => 'pending',
            ]);

            Transaction::create([
                'user_id'       => $user->id,
                'investment_id' => $investment->id,
                'type'          => 'withdrawal',
                'amount'        => $total,
                'status'        => 'pending',
                'notes'         => "Withdrawal — {$daysInvested} din · Gross: ₹{$actualGrossProfit} · Fee: ₹{$actualCommission} · Net: ₹{$actualNetProfit}",
            ]);

            DB::commit();

            return redirect()->route('withdrawals.index')
                ->with('success', "Withdrawal request submit ho gayi! ₹" . number_format($total, 2) . " 24 ghante mein aapke account mein aa jayega.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
