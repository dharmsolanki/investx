<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Notifications\ActionAlertNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminWithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = Withdrawal::with('user', 'investment.plan')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->paginate(20);
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve(Request $request, Withdrawal $withdrawal)
    {
        $request->validate(['utr_number' => 'required|string|max:50']);

        DB::beginTransaction();
        try {
            $withdrawal->update([
                'status'       => 'completed',
                'utr_number'   => $request->utr_number,
                'processed_at' => now(),
                'processed_by' => Auth::id(),
            ]);

            // Investment withdrawal
            if (!$withdrawal->isWallet() && $withdrawal->investment) {
                $withdrawal->investment->update([
                    'status'       => 'withdrawn',
                    'withdrawn_at' => now(),
                ]);

                Transaction::where('investment_id', $withdrawal->investment_id)
                    ->where('type', 'withdrawal')
                    ->update(['status' => 'completed', 'payment_id' => $request->utr_number]);

                // ✅ Actual days se commission calculate karo
                $daysInvested     = max(1, (int) $withdrawal->investment->invested_at->diffInDays($withdrawal->created_at));
                $dailyFee         = (float) $withdrawal->investment->commission_amount;
                $actualCommission = round($dailyFee * $daysInvested, 2);

                Transaction::create([
                    'user_id'       => $withdrawal->user_id,
                    'investment_id' => $withdrawal->investment_id,
                    'type'          => 'commission',
                    'amount'        => $actualCommission,
                    'status'        => 'completed',
                    'notes'         => "Platform fee — {$daysInvested} days × ₹{$dailyFee}/day = ₹{$actualCommission}",
                ]);
            }

            // Wallet withdrawal
            if ($withdrawal->isWallet()) {
                Transaction::where('user_id', $withdrawal->user_id)
                    ->where('type', 'withdrawal')
                    ->where('status', 'pending')
                    ->whereNull('investment_id')
                    ->latest()
                    ->limit(1)
                    ->update(['status' => 'completed', 'payment_id' => $request->utr_number]);
            }

            $withdrawal->user->notify(new ActionAlertNotification(
                'Withdrawal Approved',
                'Aapki withdrawal request approve ho gayi hai.',
                [
                    'UTR: ' . $request->utr_number,
                    'Amount: ₹' . number_format((float) $withdrawal->total_amount, 2),
                ]
            ));

            DB::commit();
            return back()->with('success', "Withdrawal approved. UTR: {$request->utr_number}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, Withdrawal $withdrawal)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        DB::beginTransaction();
        try {
            $withdrawal->update([
                'status'           => 'rejected',
                'rejection_reason' => $request->reason,
                'processed_at'     => now(),
                'processed_by'     => Auth::id(),
            ]);

            // Wallet withdrawal reject — paise wapas
            if ($withdrawal->isWallet()) {
                $withdrawal->user->increment('wallet_balance', $withdrawal->total_amount);

                Transaction::create([
                    'user_id'        => $withdrawal->user_id,
                    'type'           => 'deposit',
                    'amount'         => $withdrawal->total_amount,
                    'payment_method' => 'wallet',
                    'status'         => 'completed',
                    'notes'          => 'Wallet withdrawal rejected — amount refunded',
                ]);
            }

            // Investment withdrawal reject — status wapas active karo
            if (!$withdrawal->isWallet() && $withdrawal->investment) {
                $withdrawal->investment->update(['status' => 'active']);
                // ✅ matured nahi — active karo taaki user dobara withdraw kar sake
            }

            $withdrawal->user->notify(new ActionAlertNotification(
                'Withdrawal Rejected',
                'Aapki withdrawal request reject ho gayi hai.',
                [
                    'Reason: ' . $request->reason,
                    $withdrawal->isWallet()
                        ? '₹' . number_format((float) $withdrawal->total_amount, 2) . ' wapas wallet mein credit ho gaya.'
                        : 'Aap dobara withdrawal request kar sakte hain.',
                ]
            ));

            DB::commit();
            return back()->with('success', 'Withdrawal rejected and user notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}