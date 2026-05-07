<?php

namespace App\Http\Controllers;

use App\Models\InvestmentPlan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
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

    /**
     * Create Cashfree order
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:investment_plans,id',
            'amount'  => 'required|numeric|min:1',
        ]);

        $plan   = InvestmentPlan::findOrFail($request->plan_id);
        $amount = (float) $request->amount;

        if ($amount < $plan->min_amount) {
            return response()->json(['error' => 'Amount too low'], 422);
        }

        $user    = Auth::user();
        $orderId = 'INV_' . uniqid();

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
                        'return_url' => route('payment.verify') .
                            '?order_id={order_id}&plan_id=' . $plan->id,
                    ],
                    'order_note' => 'Investment Plan: ' . $plan->name,
                ]);

            if ($response->failed()) {
                Log::error('Cashfree order error: ' . $response->body());
                return response()->json(['error' => 'Order create nahi hua.'], 500);
            }

            $order = $response->json();

            return response()->json([
                'order_id'         => $order['order_id'],
                'payment_session_id' => $order['payment_session_id'],
                'amount'           => $amount,
                'currency'         => 'INR',
                'app_id'           => config('services.cashfree.app_id'),
                'env'              => config('services.cashfree.env'),
                'name'             => config('app.name'),
                'user_name'        => $user->name,
                'user_email'       => $user->email,
                'user_phone'       => $user->phone,
            ]);

        } catch (\Exception $e) {
            Log::error('Cashfree exception: ' . $e->getMessage());
            return response()->json(['error' => 'Payment failed. Try again.'], 500);
        }
    }

    /**
     * Verify payment after redirect
     */
    public function verifyPayment(Request $request)
    {
        $orderId = $request->query('order_id');
        $planId  = $request->query('plan_id');

        if (!$orderId || !$planId) {
            return redirect()->route('investments.index')
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
                return redirect()->route('investments.index')
                    ->with('error', 'Payment successful nahi hua. Status: ' . $order['order_status']);
            }

            // Duplicate check
            if (Transaction::where('payment_id', $orderId)->exists()) {
                return redirect()->route('investments.my')
                    ->with('success', 'Investment already recorded!');
            }

            $verifiedAmount = $order['order_amount'];

            $request->merge([
                'plan_id'        => $planId,
                'payment_id'     => $orderId,
                'amount'         => $verifiedAmount,
                'payment_method' => 'cashfree',
            ]);

            return app(InvestmentController::class)->store($request);

        } catch (\Exception $e) {
            Log::error('Cashfree verify exception: ' . $e->getMessage());
            return back()->with('error', 'Payment verification failed.');
        }
    }

    /**
     * Cashfree webhook
     * URL: /payment/webhook
     * Cashfree dashboard pe set karo
     */
    public function webhook(Request $request)
    {
        $signature  = $request->header('x-webhook-signature');
        $timestamp  = $request->header('x-webhook-timestamp');
        $rawBody    = $request->getContent();
        $secretKey  = config('services.cashfree.secret_key');

        $signedPayload = $timestamp . $rawBody;
        $expectedSig   = base64_encode(hash_hmac('sha256', $signedPayload, $secretKey, true));

        if ($signature !== $expectedSig) {
            Log::warning('Invalid Cashfree webhook signature');
            return response('Unauthorized', 401);
        }

        $event = $request->input('type');
        Log::info('Cashfree webhook: ' . $event);

        if ($event === 'PAYMENT_FAILED') {
            $orderId = $request->input('data.order.order_id');
            Transaction::where('payment_id', $orderId)
                ->update(['status' => 'failed']);
        }

        return response('OK', 200);
    }
}