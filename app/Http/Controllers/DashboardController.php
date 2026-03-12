<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Portfolio stats
        $totalInvested     = $user->investments()->whereIn('status', ['active','matured','withdrawn'])->sum('principal_amount');
        $activeInvestments = $user->investments()->where('status', 'active')->count();
        $totalProfit       = $user->investments()->where('status', 'withdrawn')->sum('net_profit');
        $pendingWithdrawals = $user->withdrawals()->where('status', 'pending')->count();

        // Active investments
        $investments = $user->investments()
            ->with('plan')
            ->whereIn('status', ['active', 'matured'])
            ->latest()
            ->take(5)
            ->get();

        // Recent transactions
        $transactions = $user->transactions()
            ->with('investment.plan')
            ->latest()
            ->take(10)
            ->get();

        // Monthly profit chart data (last 6 months)
        $chartData = $this->getMonthlyProfitData($user);

        // Current portfolio value
        $currentValue = $totalInvested; // simplified; you can add live ROI calc

        return view('dashboard.index', compact(
            'user', 'totalInvested', 'activeInvestments', 'totalProfit',
            'pendingWithdrawals', 'investments', 'transactions', 'chartData', 'currentValue'
        ));
    }

    public function kyc()
    {
        return view('dashboard.kyc', ['user' => Auth::user()]);
    }

    public function updateKyc(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'pan_number'     => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'aadhar_number'  => 'required|digits:12',
            'bank_account'   => 'required|string|max:20',
            'bank_ifsc'      => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'bank_name'      => 'required|string|max:100',
        ]);

        $user = Auth::user();
        $user->update([
            'pan_number'     => strtoupper($request->pan_number),
            'aadhar_number'  => $request->aadhar_number,
            'bank_account'   => $request->bank_account,
            'bank_ifsc'      => strtoupper($request->bank_ifsc),
            'bank_name'      => $request->bank_name,
            'kyc_status'     => 'submitted',
        ]);

        return redirect()->route('dashboard')->with('success', 'KYC documents submit ho gaye. 24 ghante mein verify karenge.');
    }

    private function getMonthlyProfitData($user): array
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $profit = Transaction::where('user_id', $user->id)
                ->where('type', 'profit')
                ->where('status', 'completed')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
            $data[] = [
                'month'  => $month->format('M Y'),
                'profit' => (float) $profit,
            ];
        }
        return $data;
    }
}
