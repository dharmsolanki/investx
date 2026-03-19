<?php

namespace App\Services;

use App\Models\Investment;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class InvestmentService
{
    public static function autoSettleMaturedInvestments($user): void
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
                        'status'        => 'withdrawn',
                        'withdrawn_at'  => now(),
                        'actual_profit' => $investment->net_profit,
                        'admin_notes'   => 'Auto-settled to wallet on maturity',
                    ]);

                if (!$updated) return;

                $user->increment('wallet_balance', $totalReturn);

                Transaction::create([
                    'user_id'        => $user->id,
                    'investment_id'  => $investment->id,
                    'type'           => 'refund',
                    'amount'         => $investment->principal_amount,
                    'payment_method' => 'wallet',
                    'status'         => 'completed',
                    'notes'          => 'Principal auto-credited to wallet on maturity',
                ]);

                Transaction::create([
                    'user_id'        => $user->id,
                    'investment_id'  => $investment->id,
                    'type'           => 'profit',
                    'amount'         => $investment->net_profit,
                    'payment_method' => 'wallet',
                    'status'         => 'completed',
                    'notes'          => 'Profit auto-credited to wallet on maturity',
                ]);
            });
        }
    }
}