<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\ActionAlertNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false)->with('investments');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%")
                ->orWhere('phone', 'like', "%$s%"));
        }

        if ($request->filled('kyc')) {
            $query->where('kyc_status', $request->kyc);
        }

        $users = $query->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('investments.plan', 'transactions', 'withdrawals');
        return view('admin.users.show', compact('user'));
    }

    public function updateKyc(Request $request, User $user)
    {
        $request->validate([
            'kyc_status' => 'required|in:verified,rejected',
            'reason'     => 'nullable|string|max:500',
        ]);

        $data = ['kyc_status' => $request->kyc_status];

        if ($request->kyc_status === 'rejected' && $request->filled('reason')) {
            $data['kyc_rejection_reason'] = $request->reason;
        } else {
            $data['kyc_rejection_reason'] = null;
        }

        $user->update($data);

        $user->notify(new ActionAlertNotification(
            'KYC Status Updated',
            'Aapka KYC status update ho gaya hai.',
            [
                'Naya status: ' . ucfirst($request->kyc_status),
                $request->kyc_status === 'rejected' && $request->filled('reason') ? ('Reason: ' . $request->reason) : 'Aap dashboard me details dekh sakte hain.',
            ]
        ));

        $msg = $request->kyc_status === 'verified'
            ? "✅ KYC Verified: {$user->name}"
            : "❌ KYC Rejected: {$user->name}";

        return back()->with('success', $msg);
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'suspended';
        return back()->with('success', "User {$status} successfully.");
    }


    public function addProfit(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'notes'  => 'nullable|string|max:300',
        ]);

        DB::transaction(function () use ($request, $user) {
            $user->increment('wallet_balance', $request->amount);

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'profit',
                'amount' => $request->amount,
                'payment_method' => 'wallet',
                'status' => 'completed',
                'notes' => $request->notes ?: 'Profit credited by admin',
            ]);
        });

        $user->notify(new ActionAlertNotification(
            'Profit Credited',
            'Aapke wallet me profit add kiya gaya hai.',
            [
                'Amount: ₹' . number_format((float) $request->amount, 2),
                'Updated wallet: ₹' . number_format((float) $user->fresh()->wallet_balance, 2),
            ]
        ));

        return back()->with('success', 'Profit successfully add kar diya gaya.');
    }

    public function adjustWallet(Request $request, User $user)
    {
        $request->validate([
            'type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string|max:300',
        ]);

        if ($request->type === 'debit' && $user->wallet_balance < $request->amount) {
            return back()->with('error', 'Debit amount wallet balance se zyada nahi ho sakta.');
        }

        DB::transaction(function () use ($request, $user) {
            if ($request->type === 'credit') {
                $user->increment('wallet_balance', $request->amount);
            } else {
                $user->decrement('wallet_balance', $request->amount);
            }

            Transaction::create([
                'user_id' => $user->id,
                'type' => $request->type === 'credit' ? 'deposit' : 'withdrawal',
                'amount' => $request->amount,
                'payment_method' => 'wallet',
                'status' => 'completed',
                'notes' => $request->notes ?: ('Wallet ' . $request->type . ' by admin'),
            ]);
        });

        $action = $request->type === 'credit' ? 'credited' : 'debited';
        $user->notify(new ActionAlertNotification(
            'Wallet Updated',
            'Aapka wallet balance admin ne update kiya hai.',
            [
                'Action: ' . ucfirst($request->type),
                'Amount: ₹' . number_format((float) $request->amount, 2),
                'Updated wallet: ₹' . number_format((float) $user->fresh()->wallet_balance, 2),
            ]
        ));

        return back()->with('success', "Wallet {$action} successfully.");
    }

}
