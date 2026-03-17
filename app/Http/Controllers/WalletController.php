<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = $user->transactions()->latest()->paginate(15);

        return view('wallet.index', compact('user', 'transactions'));
    }
}
