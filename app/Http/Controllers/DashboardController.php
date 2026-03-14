<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalInvested      = $user->investments()->whereIn('status', ['active', 'matured', 'withdrawn'])->sum('principal_amount');
        $activeInvestments  = $user->investments()->where('status', 'active')->count();
        $totalProfit        = $user->investments()->where('status', 'withdrawn')->sum('net_profit');
        $pendingWithdrawals = $user->withdrawals()->where('status', 'pending')->count();

        $investments = $user->investments()
            ->with('plan')
            ->whereIn('status', ['active', 'matured'])
            ->latest()
            ->take(5)
            ->get();

        $transactions = $user->transactions()
            ->with('investment.plan')
            ->latest()
            ->take(10)
            ->get();

        $chartData    = $this->getMonthlyProfitData($user);
        $currentValue = $totalInvested;

        return view('dashboard.index', compact(
            'user',
            'totalInvested',
            'activeInvestments',
            'totalProfit',
            'pendingWithdrawals',
            'investments',
            'transactions',
            'chartData',
            'currentValue'
        ));
    }

    public function kyc()
    {
        return view('dashboard.kyc', ['user' => Auth::user()]);
    }

    public function updateKyc(Request $request)
    {
        // Uppercase fix
        $request->merge([
            'pan_number' => strtoupper($request->pan_number),
            'bank_ifsc'  => strtoupper($request->bank_ifsc),
        ]);

        $request->validate([
            'pan_number'          => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'aadhar_number'       => 'required|digits:12',
            'bank_account'        => 'required|digits_between:9,18',
            'bank_ifsc'           => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'bank_name'           => 'required|string|max:100',
            'pan_image'           => 'required|image|mimes:jpg,jpeg,png,pdf|max:2048',
            'aadhar_front_image'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'aadhar_back_image'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'bank_passbook_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'pan_image.required'           => 'PAN Card image zaroori hai.',
            'aadhar_front_image.required'  => 'Aadhaar front image zaroori hai.',
            'aadhar_back_image.required'   => 'Aadhaar back image zaroori hai.',
            'bank_passbook_image.required' => 'Bank passbook/cheque image zaroori hai.',
            'pan_image.max'                => 'Image size 2MB se kam honi chahiye.',
        ]);

        $user = Auth::user();
        $data = [
            'pan_number'    => $request->pan_number,
            'aadhar_number' => $request->aadhar_number,
            'bank_account'  => $request->bank_account,
            'bank_ifsc'     => $request->bank_ifsc,
            'bank_name'     => $request->bank_name,
            'kyc_status'    => 'submitted',
        ];

        // Upload images to storage/app/public/kyc/{user_id}/
        $folder = 'kyc/' . $user->id;

        if ($request->hasFile('pan_image')) {
            $data['pan_image'] = $request->file('pan_image')->store($folder, 'public');
        }
        if ($request->hasFile('aadhar_front_image')) {
            $data['aadhar_front_image'] = $request->file('aadhar_front_image')->store($folder, 'public');
        }
        if ($request->hasFile('aadhar_back_image')) {
            $data['aadhar_back_image'] = $request->file('aadhar_back_image')->store($folder, 'public');
        }
        if ($request->hasFile('bank_passbook_image')) {
            $data['bank_passbook_image'] = $request->file('bank_passbook_image')->store($folder, 'public');
        }

        $user->update($data);

        return redirect()->route('dashboard')
            ->with('success', 'KYC documents submit ho gaye! Admin 24 ghante mein verify karega.');
    }

    private function getMonthlyProfitData($user): array
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month  = now()->subMonths($i);
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
