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

        // Matured investments without withdrawal request
        $maturedInvestments = $user->investments()
            ->with('plan')
            ->where('status', 'matured')
            ->whereDoesntHave('withdrawal', fn($q) => $q->whereIn('status', ['pending', 'processing', 'completed']))
            ->get();

        $withdrawals = $user->withdrawals()
            ->with('investment.plan')
            ->latest()
            ->paginate(10);

        return view('withdrawals.index', compact('user', 'maturedInvestments', 'withdrawals'));
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
            ->where('status', 'matured')
            ->firstOrFail();

        // Check no duplicate withdrawal
        if ($investment->withdrawal && in_array($investment->withdrawal->status, ['pending', 'processing', 'completed'])) {
            return back()->with('error', 'Is investment ke liye withdrawal request already exist karti hai.');
        }

        DB::beginTransaction();
        try {
            $total = $investment->principal_amount + $investment->net_profit;

            Withdrawal::create([
                'user_id'          => $user->id,
                'investment_id'    => $investment->id,
                'principal_amount' => $investment->principal_amount,
                'net_profit'       => $investment->net_profit,
                'total_amount'     => $total,
                'bank_account'     => $request->bank_account,
                'bank_ifsc'        => strtoupper($request->bank_ifsc),
                'bank_name'        => $request->bank_name,
                'status'           => 'pending',
            ]);

            // Record transaction
            Transaction::create([
                'user_id'       => $user->id,
                'investment_id' => $investment->id,
                'type'          => 'withdrawal',
                'amount'        => $total,
                'status'        => 'pending',
                'notes'         => 'Withdrawal request submitted',
            ]);

            DB::commit();

            return redirect()->route('withdrawals.index')
                ->with('success', "Withdrawal request submit ho gayi! ₹" . number_format($total, 2) . " 4 ghante mein aapke account mein aa jayega.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
