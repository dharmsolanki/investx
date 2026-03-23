<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ✅ Stats 5 min cache — har baar DB hit nahi hoga
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_users'         => User::where('is_admin', false)->count(),
                'kyc_pending'         => User::where('kyc_status', 'submitted')->count(),
                'active_investments'  => Investment::where('status', 'active')->count(),
                'total_invested'      => Investment::sum('principal_amount'),
                'total_commission'    => Transaction::where('type', 'commission')->where('status', 'completed')->sum('amount'),
                'pending_withdrawals' => Withdrawal::where('status', 'pending')->count(),
                'today_deposits'      => Transaction::where('type', 'deposit')->where('status', 'completed')->whereDate('created_at', today())->sum('amount'),
                'total_users_today'   => User::whereDate('created_at', today())->count(),
            ];
        });

        // ✅ Eager loading sahi se
        $recentUsers        = User::where('is_admin', false)->latest()->take(5)->get();
        $recentTransactions = Transaction::with('user')->latest()->take(10)->get();
        $pendingWithdrawals = Withdrawal::with('user', 'investment.plan')
                                ->where('status', 'pending')
                                ->latest()->take(5)->get();

        // ✅ Monthly chart — ek single query
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $monthlyData[] = [
                'month'      => $m->format('M'),
                'invested'   => (float) Transaction::where('type', 'deposit')
                                    ->where('status', 'completed')
                                    ->whereYear('created_at', $m->year)
                                    ->whereMonth('created_at', $m->month)
                                    ->sum('amount'),
                'commission' => (float) Transaction::where('type', 'commission')
                                    ->where('status', 'completed')
                                    ->whereYear('created_at', $m->year)
                                    ->whereMonth('created_at', $m->month)
                                    ->sum('amount'),
            ];
        }

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentTransactions', 'pendingWithdrawals', 'monthlyData'));
    }
}