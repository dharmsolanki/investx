<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    public function index()
    {
        $user         = Auth::user();
        $transactions = $user->transactions()->latest()->paginate(15);

        $activeInvestments = $user->investments()
            ->with('plan')
            ->where('status', 'active')
            ->orderBy('maturity_date')
            ->get();

        $todayWithdrawn = Withdrawal::where('user_id', $user->id)
            ->where('type', 'wallet')
            ->whereDate('created_at', today())
            ->whereIn('status', ['pending', 'processing', 'completed'])
            ->sum('total_amount');

        $hasPendingRequest = Withdrawal::where('user_id', $user->id)
            ->where('type', 'wallet')
            ->where('status', 'pending')
            ->exists();

        $eligibility = [
            'kyc_verified'    => $user->isKycVerified(),
            'has_balance'     => $user->wallet_balance >= 500,
            'no_pending'      => !$hasPendingRequest,
            'daily_limit_ok'  => $todayWithdrawn < 50000,
            'today_withdrawn' => $todayWithdrawn,
            'daily_remaining' => max(0, 50000 - $todayWithdrawn),
            'is_eligible'     => $user->isKycVerified()
                && $user->wallet_balance >= 500
                && !$hasPendingRequest
                && $todayWithdrawn < 50000,
        ];

        return view('wallet.index', compact('user', 'transactions', 'eligibility', 'activeInvestments'));
    }

    public function topupOrder(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:100']);
        $amount = (float) $request->amount;

        try {
            $api = new \Razorpay\Api\Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            $order = $api->order->create([
                'receipt'  => 'WALLET_' . uniqid(),
                'amount'   => $amount * 100,
                'currency' => 'INR',
                'notes'    => ['user_id' => Auth::id(), 'purpose' => 'wallet_topup'],
            ]);

            return response()->json([
                'order_id'   => $order->id,
                'amount'     => $amount * 100,
                'currency'   => 'INR',
                'key'        => config('services.razorpay.key'),
                'name'       => config('app.name'),
                'user_name'  => Auth::user()->name,
                'user_email' => Auth::user()->email,
                'user_phone' => Auth::user()->phone,
            ]);
        } catch (\Exception $e) {
            Log::error('Wallet topup order failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment gateway error'], 500);
        }
    }

    public function topupVerify(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature'  => 'required',
        ]);

        $expectedSignature = hash_hmac(
            'sha256',
            $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
            config('services.razorpay.secret')
        );

        if ($expectedSignature !== $request->razorpay_signature) {
            return back()->with('error', 'Payment verification failed.');
        }

        // ✅ Amount Razorpay API se verify karo, client se nahi
        try {
            $api     = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $payment = $api->payment->fetch($request->razorpay_payment_id);

            if ($payment->status !== 'captured') {
                return back()->with('error', 'Payment captured nahi hua.');
            }

            $amount = $payment->amount / 100; // paise to rupees
        } catch (\Exception $e) {
            return back()->with('error', 'Payment verify nahi ho saka: ' . $e->getMessage());
        }

        // Duplicate payment check
        if (Transaction::where('payment_id', $request->razorpay_payment_id)->exists()) {
            return redirect()->route('wallet.index')->with('success', 'Payment already credited!');
        }

        $user = Auth::user();

        DB::beginTransaction();
        try {
            $user->increment('wallet_balance', $amount);

            Transaction::create([
                'user_id'          => $user->id,
                'type'             => 'deposit',
                'amount'           => $amount,
                'payment_method'   => 'razorpay',
                'payment_id'       => $request->razorpay_payment_id,
                'gateway_order_id' => $request->razorpay_order_id,
                'status'           => 'completed',
                'notes'            => 'Wallet top-up via Razorpay',
            ]);

            DB::commit();
            return redirect()->route('wallet.index')
                ->with('success', '₹' . number_format($amount, 2) . ' wallet mein add ho gaya!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function withdrawRequest(Request $request)
    {
        $request->validate([
            'amount'       => 'required|numeric|min:500',
            'bank_account' => 'required|string|max:20',
            'bank_ifsc'    => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/i',
            'bank_name'    => 'required|string|max:100',
        ]);

        $user   = Auth::user();
        $amount = (float) $request->amount;

        // KYC check
        if (!$user->isKycVerified()) {
            return back()->with('error', 'Withdrawal ke liye pehle KYC complete karein.');
        }

        // Wallet balance check
        if ($user->wallet_balance < $amount) {
            return back()->with('error', 'Wallet balance kam hai. Available: ₹' . number_format($user->wallet_balance, 2));
        }

        // Max ₹50,000 per day check
        $todayTotal = Withdrawal::where('user_id', $user->id)
            ->where('type', 'wallet')
            ->whereDate('created_at', today())
            ->whereIn('status', ['pending', 'processing', 'completed'])
            ->sum('total_amount');

        if ($todayTotal + $amount > 50000) {
            $remaining = 50000 - $todayTotal;
            return back()->with('error', 'Aaj ka daily limit ₹50,000 hai. Aap abhi sirf ₹' . number_format($remaining, 2) . ' aur withdraw kar sakte ho.');
        }

        // Pending request already hai check
        $hasPending = Withdrawal::where('user_id', $user->id)
            ->where('type', 'wallet')
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return back()->with('error', 'Aapki ek withdrawal request pehle se pending hai. Uske process hone ka wait karein.');
        }

        DB::beginTransaction();
        try {
            // Balance pehle deduct karo
            $user->decrement('wallet_balance', $amount);

            Withdrawal::create([
                'user_id'          => $user->id,
                'investment_id'    => null,
                'type'             => 'wallet',
                'principal_amount' => $amount,
                'net_profit'       => 0,
                'total_amount'     => $amount,
                'bank_account'     => $request->bank_account,
                'bank_ifsc'        => strtoupper($request->bank_ifsc),
                'bank_name'        => $request->bank_name,
                'status'           => 'pending',
            ]);

            Transaction::create([
                'user_id'        => $user->id,
                'type'           => 'withdrawal',
                'amount'         => $amount,
                'payment_method' => 'bank_transfer',
                'status'         => 'pending',
                'notes'          => 'Wallet withdrawal to bank - pending admin approval',
            ]);

            DB::commit();
            return redirect()->route('wallet.index')
                ->with('success', '₹' . number_format($amount, 2) . ' withdrawal request submit ho gayi! Admin approve karega.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
