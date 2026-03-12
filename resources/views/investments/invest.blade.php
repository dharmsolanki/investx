@extends('layouts.app')
@section('title', 'Invest in ' . $plan->name)
@section('page-title', 'Invest Karein')

@section('content')

<div class="grid-2">

{{-- FORM --}}
<div class="card">
    <div class="card-title">💰 {{ $plan->name }} — Invest Karein</div>

    @if(!$user->isKycVerified())
    <div class="alert warning">⚠️ KYC verify nahi hai. <a href="{{ route('kyc') }}" style="color:var(--gold)">Pehle KYC karein →</a></div>
    @else

    <form id="investForm" action="{{ route('investments.store') }}" method="POST">
        @csrf
        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
        <input type="hidden" name="payment_id" id="paymentId">

        <div class="form-group">
            <label class="form-label">Investment Amount (₹)</label>
            <input class="form-control" type="number" name="amount" id="amount"
                   min="{{ $plan->min_amount }}"
                   @if($plan->max_amount) max="{{ $plan->max_amount }}" @endif
                   value="{{ $plan->min_amount }}"
                   placeholder="Min ₹{{ number_format($plan->min_amount) }}"
                   oninput="calculateProfit(this.value)" required>
            <div style="font-size:0.75rem;color:var(--muted);margin-top:4px">
                Min: ₹{{ number_format($plan->min_amount) }}
                @if($plan->max_amount) | Max: ₹{{ number_format($plan->max_amount) }} @endif
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Payment Method</label>
            <select class="form-control" name="payment_method" required>
                <option value="upi">📱 UPI (GPay, PhonePe, Paytm)</option>
                <option value="netbanking">🏦 Net Banking</option>
                <option value="card">💳 Credit/Debit Card</option>
                <option value="imps">⚡ IMPS</option>
                <option value="neft">🏛️ NEFT</option>
            </select>
        </div>

        {{-- Summary Box --}}
        <div id="summary" style="background:rgba(201,168,76,0.06);border:1px solid var(--border);border-radius:12px;padding:1.2rem;margin-bottom:1.5rem">
            <div style="font-weight:600;margin-bottom:0.8rem;font-size:0.88rem">Investment Summary</div>
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.3rem 0;border-bottom:1px solid rgba(255,255,255,0.05)">
                <span style="color:var(--muted)">Principal</span>
                <strong id="s-principal">₹0</strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.3rem 0;border-bottom:1px solid rgba(255,255,255,0.05)">
                <span style="color:var(--muted)">Expected Profit ({{ $plan->roi_percent }}% for {{ $plan->duration_months }}mo)</span>
                <strong id="s-profit" style="color:var(--green)">₹0</strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.3rem 0;border-bottom:1px solid rgba(255,255,255,0.05)">
                <span style="color:var(--muted)">Our Commission ({{ $plan->commission_percent }}%)</span>
                <strong id="s-commission" style="color:var(--red)">₹0</strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.9rem;padding:0.5rem 0;font-weight:700">
                <span>Net Return</span>
                <span id="s-net" style="color:var(--gold)">₹0</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.82rem;padding:0.3rem 0">
                <span style="color:var(--muted)">Total Amount at Maturity</span>
                <strong id="s-total" style="color:var(--green)">₹0</strong>
            </div>
            <div style="font-size:0.72rem;color:var(--muted);margin-top:0.5rem">
                📅 Maturity Date: <strong>{{ now()->addMonths($plan->duration_months)->format('d M Y') }}</strong>
            </div>
        </div>

        <button type="button" onclick="initiatePayment()" class="btn btn-gold btn-block" style="font-size:1rem;padding:0.9rem">
            🔒 Secure Payment Karein
        </button>
        <p style="text-align:center;font-size:0.72rem;color:var(--muted);margin-top:0.6rem">Powered by Razorpay · SSL Secured · Instant Credit</p>
    </form>
    @endif
</div>

{{-- Plan Info --}}
<div>
    <div class="card" style="margin-bottom:1rem">
        <div class="card-title">📋 Plan Details</div>
        <div style="font-family:'Playfair Display',serif;font-size:2.5rem;color:var(--gold);font-weight:900;margin-bottom:0.3rem">{{ $plan->roi_percent }}%</div>
        <div style="font-size:0.82rem;color:var(--muted);margin-bottom:1.5rem">Annual Return (Expected)</div>

        <div style="display:flex;flex-direction:column;gap:0.6rem">
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.5rem;background:var(--dark4);border-radius:8px">
                <span style="color:var(--muted)">⏱️ Lock-in Period</span>
                <strong>{{ $plan->duration_months }} Months</strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.5rem;background:var(--dark4);border-radius:8px">
                <span style="color:var(--muted)">💰 Min Amount</span>
                <strong style="color:var(--gold)">₹{{ number_format($plan->min_amount) }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.5rem;background:var(--dark4);border-radius:8px">
                <span style="color:var(--muted)">📊 Our Commission</span>
                <strong>{{ $plan->commission_percent }}% of profit</strong>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom:1rem">
        <div style="font-size:0.85rem;color:var(--muted);line-height:1.7">
            <div style="font-weight:600;color:var(--text);margin-bottom:0.5rem">⚠️ Important Notes:</div>
            <ul style="padding-left:1.2rem;display:flex;flex-direction:column;gap:0.4rem">
                <li>Returns market conditions pe depend karte hain.</li>
                <li>Lock-in period se pehle early exit pe 5% fee lagegi.</li>
                <li>Commission sirf profit pe lagta hai, principal pe nahi.</li>
                <li>Maturity pe withdrawal 4 ghante mein process hogi.</li>
            </ul>
        </div>
    </div>
</div>
</div>

@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
const planId = {{ $plan->id }};

function fmt(n) {
    return '₹' + parseFloat(n).toLocaleString('en-IN', {minimumFractionDigits: 2});
}

async function calculateProfit(amount) {
    if (!amount || amount < 1) return;
    try {
        const res = await fetch(`/calculate-profit?plan_id=${planId}&amount=${amount}`);
        const d   = await res.json();
        document.getElementById('s-principal').textContent   = fmt(d.principal);
        document.getElementById('s-profit').textContent      = fmt(d.gross_profit);
        document.getElementById('s-commission').textContent  = fmt(d.commission_amount);
        document.getElementById('s-net').textContent         = fmt(d.net_profit);
        document.getElementById('s-total').textContent       = fmt(d.total_return);
    } catch(e) { console.error(e); }
}

// Init on load
calculateProfit(document.getElementById('amount').value);

async function initiatePayment() {
    const amount = document.getElementById('amount').value;
    if (!amount || amount < {{ $plan->min_amount }}) {
        alert('Minimum ₹{{ number_format($plan->min_amount) }} invest karna zaroori hai.');
        return;
    }

    try {
        const res = await fetch('/payment/create-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
            body: JSON.stringify({ plan_id: planId, amount }),
        });
        const order = await res.json();

        if (order.error) { alert(order.error); return; }

        const options = {
            key:         order.key,
            amount:      order.amount,
            currency:    order.currency,
            name:        order.name,
            description: 'Investment — {{ $plan->name }}',
            order_id:    order.order_id,
            prefill: {
                name:    order.user_name,
                email:   order.user_email,
                contact: order.user_phone,
            },
            theme: { color: '#C9A84C' },
            handler: function(response) {
                document.getElementById('paymentId').value = response.razorpay_payment_id;

                // Submit form with Razorpay details for server-side verification
                const form = document.getElementById('investForm');
                const input = (name, val) => {
                    const el = document.createElement('input');
                    el.type = 'hidden'; el.name = name; el.value = val;
                    form.appendChild(el);
                };
                input('razorpay_order_id',   response.razorpay_order_id);
                input('razorpay_payment_id', response.razorpay_payment_id);
                input('razorpay_signature',  response.razorpay_signature);
                form.action = '/payment/verify';
                form.submit();
            }
        };

        const rzp = new Razorpay(options);
        rzp.on('payment.failed', (resp) => alert('Payment failed: ' + resp.error.description));
        rzp.open();

    } catch(e) {
        alert('Payment gateway error. Please try again.');
    }
}
</script>
@endpush
