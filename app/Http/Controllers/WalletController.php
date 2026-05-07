<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    private function cashfreeHeaders(): array
    {
        return [
            'x-client-id'     => config('services.cashfree.app_id'),
            'x-client-secret' => config('services.cashfree.secret_key'),
            'x-api-version'   => '2023-08-01',
            'Content-Type'    => 'application/json',
        ];
    }

    private function baseUrl(): string
    {
        return config('services.cashfree.base_url');
    }

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
        $amount  = (float) $request->amount;
        $user    = Auth::user();
        $orderId = 'WALLET_' . uniqid();

        try {
            $response = Http::withHeaders($this->cashfreeHeaders())
                ->post($this->baseUrl() . '/orders', [
                    'order_id'       => $orderId,
                    'order_amount'   => $amount,
                    'order_currency' => 'INR',
                    'customer_details' => [
                        'customer_id'    => (string) $user->id,
                        'customer_name'  => $user->name,
                        'customer_email' => $user->email,
                        'customer_phone' => $user->phone ?? '9999999999',
                    ],
                    'order_meta' => [
                        'return_url' => route('wallet.topup.verify') .
                            '?order_id={order_id}',
                    ],
                    'order_note' => 'Wallet Top-up',
                ]);

            if ($response->failed()) {
                Log::error('Cashfree wallet topup error: ' . $response->body());
                return response()->json(['error' => 'Order create nahi hua.'], 500);
            }

            $order = $response->json();

            return response()->json([
                'order_id'           => $order['order_id'],
                'payment_session_id' => $order['payment_session_id'],
                'amount'             => $amount,
                'env'                => config('services.cashfree.env'),
            ]);

        } catch (\Exception $e) {
            Log::error('Cashfree wallet exception: ' . $e->getMessage());
            return response()->json(['error' => 'Payment gateway error'], 500);
        }
    }

    public function topupVerify(Request $request)
    {
        $orderId = $request->query('order_id');

        if (!$orderId) {
            return redirect()->route('wallet.index')
                ->with('error', 'Invalid payment response.');
        }

        try {
            $response = Http::withHeaders($this->cashfreeHeaders())
                ->get($this->baseUrl() . '/orders/' . $orderId);

            if ($response->failed()) {
                return back()->with('error', 'Payment verify nahi ho saka.');
            }

            $order = $response->json();

            if ($order['order_status'] !== 'PAID') {
                return redirect()->route('wallet.index')
                    ->with('error', 'Payment successful nahi hua. Status: ' . $order['order_status']);
            }

            $amount = $order['order_amount'];

        } catch (\Exception $e) {
            return back()->with('error', 'Payment verify nahi ho saka: ' . $e->getMessage());
        }

        // Duplicate check
        if (Transaction::where('payment_id', $orderId)->exists()) {
            return redirect()->route('wallet.index')
                ->with('success', 'Payment already credited!');
        }

        $user = Auth::user();

        DB::beginTransaction();
        try {
            $user->increment('wallet_balance', $amount);

            Transaction::create([
                'user_id'          => $user->id,
                'type'             => 'deposit',
                'amount'           => $amount,
                'payment_method'   => 'cashfree',
                'payment_id'       => $orderId,
                'gateway_order_id' => $orderId,
                'status'           => 'completed',
                'notes'            => 'Wallet top-up via Cashfree',
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

        if (!$user->isKycVerified()) {
            return back()->with('error', 'Withdrawal ke liye pehle KYC complete karein.');
        }

        if ($user->wallet_balance < $amount) {
            return back()->with('error', 'Wallet balance kam hai. Available: ₹' . number_format($user->wallet_balance, 2));
        }

        $todayTotal = Withdrawal::where('user_id', $user->id)
            ->where('type', 'wallet')
            ->whereDate('created_at', today())
            ->whereIn('status', ['pending', 'processing', 'completed'])
            ->sum('total_amount');

        if ($todayTotal + $amount > 50000) {
            $remaining = 50000 - $todayTotal;
            return back()->with('error', 'Aaj ka daily limit ₹50,000 hai. Aap abhi sirf ₹' . number_format($remaining, 2) . ' aur withdraw kar sakte ho.');
        }

        $hasPending = Withdrawal::where('user_id', $user->id)
            ->where('type', 'wallet')
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return back()->with('error', 'Aapki ek withdrawal request pehle se pending hai.');
        }

        DB::beginTransaction();
        try {
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
                ->with('success', '₹' . number_format($amount, 2) . ' withdrawal request submit ho gayi!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}