<?php
// ============================================================
// FILE: app/Http/Controllers/Admin/AdminWithdrawalController.php
// ============================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\Withdrawal;
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

            // Update investment status
            $withdrawal->investment->update([
                'status'       => 'withdrawn',
                'withdrawn_at' => now(),
            ]);

            // Update transaction
            Transaction::where('investment_id', $withdrawal->investment_id)
                ->where('type', 'withdrawal')
                ->update(['status' => 'completed', 'payment_id' => $request->utr_number]);

            // Record commission transaction
            Transaction::create([
                'user_id'       => $withdrawal->user_id,
                'investment_id' => $withdrawal->investment_id,
                'type'          => 'commission',
                'amount'        => $withdrawal->investment->commission_amount,
                'status'        => 'completed',
                'notes'         => 'Commission deducted on profit withdrawal',
            ]);

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

        $withdrawal->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
            'processed_at'     => now(),
            'processed_by'     => Auth::id(),
        ]);

        // Revert investment to matured so user can re-request
        $withdrawal->investment->update(['status' => 'matured']);

        return back()->with('success', 'Withdrawal rejected and user notified.');
    }
}
