<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\InvestmentPlan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Create Razorpay order
     * Install: composer require razorpay/razorpay
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

        try {
            $api = new \Razorpay\Api\Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            $order = $api->order->create([
                'receipt'  => 'INV_' . uniqid(),
                'amount'   => $amount * 100, // paise
                'currency' => 'INR',
                'notes'    => [
                    'user_id' => Auth::id(),
                    'plan_id' => $plan->id,
                ],
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
            Log::error('Razorpay order creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment failed: ' . $e->getMessage()], 500); // ← change karo
        }
    }

    /**
     * Verify payment and create investment
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature'  => 'required',
            'plan_id'             => 'required|exists:investment_plans,id',
            'payment_method'      => 'required',
        ]);

        $expectedSignature = hash_hmac(
            'sha256',
            $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
            config('services.razorpay.secret')
        );

        if ($expectedSignature !== $request->razorpay_signature) {
            return back()->with('error', 'Payment verification failed. Contact support.');
        }

        // ✅ Razorpay se actual amount fetch karo
        try {
            $api     = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $payment = $api->payment->fetch($request->razorpay_payment_id);

            if ($payment->status !== 'captured') {
                return back()->with('error', 'Payment captured nahi hua.');
            }

            // Duplicate check
            if (Transaction::where('payment_id', $request->razorpay_payment_id)->exists()) {
                return redirect()->route('investments.my')->with('success', 'Investment already recorded!');
            }

            $verifiedAmount = $payment->amount / 100;
        } catch (\Exception $e) {
            return back()->with('error', 'Payment verify nahi ho saka.');
        }

        $request->merge([
            'payment_id'     => $request->razorpay_payment_id,
            'amount'         => $verifiedAmount,
            'payment_method' => 'razorpay', // ← yeh add karo
        ]);

        return app(InvestmentController::class)->store($request);
    }

    /**
     * Razorpay webhook (set in Razorpay dashboard)
     * URL: /payment/webhook
     */
    public function webhook(Request $request)
    {
        $webhookSecret = config('services.razorpay.webhook_secret');
        $signature     = $request->header('X-Razorpay-Signature');

        $expectedSig = hash_hmac('sha256', $request->getContent(), $webhookSecret);

        if (!hash_equals($expectedSig, $signature)) {
            Log::warning('Invalid Razorpay webhook signature');
            return response('Unauthorized', 401);
        }

        $event = $request->input('event');
        Log::info('Razorpay webhook: ' . $event);

        // Handle specific events
        if ($event === 'payment.failed') {
            $paymentId = $request->input('payload.payment.entity.id');
            Transaction::where('payment_id', $paymentId)->update(['status' => 'failed']);
        }

        return response('OK', 200);
    }
}
