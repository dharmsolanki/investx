<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $transactions = $user->transactions()
            ->where('status', 'completed')
            ->latest()
            ->paginate(15);

        $totalCredits = $user->transactions()
            ->where('status', 'completed')
            ->whereIn('type', ['deposit', 'profit', 'referral_bonus', 'refund'])
            ->sum('amount');

        $totalDebits = $user->transactions()
            ->where('status', 'completed')
            ->whereIn('type', ['withdrawal', 'commission'])
            ->sum('amount');

        return view('wallet.index', compact('user', 'transactions', 'totalCredits', 'totalDebits'));
    }
}
