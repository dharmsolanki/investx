@extends('layouts.app')
@section('title', 'Participate in ' . $plan->name)
@section('page-title', 'Participate Karein')

@section('content')

    <div class="grid-2">

        {{-- FORM --}}
        <div class="card">
            <div class="card-title">💰 {{ $plan->name }} — Participate Karein</div>

            @if (!$user->isKycVerified())
                <div class="alert warning">⚠️ KYC verify nahi hai. <a href="{{ route('kyc') }}"
                        style="color:var(--gold)">Pehle KYC karein →</a></div>
            @else
                <form id="investForm" action="{{ route('investments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <input type="hidden" name="payment_id" id="paymentId">
                    <input type="hidden" name="payment_method" id="hiddenPaymentMethod" value="cashfree">

                    <div class="form-group">
                        <label class="form-label">Contribution Amount (₹)</label>
                        <input class="form-control" type="number" name="amount" id="amount"
                            min="{{ $plan->min_amount }}"
                            @if ($plan->max_amount) max="{{ $plan->max_amount }}" @endif
                            value="{{ $plan->min_amount }}" placeholder="Min ₹{{ number_format($plan->min_amount) }}"
                            oninput="calculateProfit(this.value)" required>
                        <div style="font-size:0.75rem;color:var(--muted);margin-top:4px">
                            Min: ₹{{ number_format($plan->min_amount) }}
                            @if ($plan->max_amount)
                                | Max: ₹{{ number_format($plan->max_amount) }}
                            @endif
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="form-group">
                        <label class="form-label">Payment Method</label>
                        <div style="display:flex;gap:1rem">

                            <label
                                style="display:flex;align-items:center;gap:0.8rem;cursor:pointer;
                               background:var(--dark4);border:2px solid var(--gold);
                               border-radius:10px;padding:0.9rem 1.2rem;flex:1"
                                id="cashfree-option">
                                <input type="radio" name="pay_choice" value="cashfree" id="pay-cashfree" checked
                                    onchange="togglePaymentMethod('cashfree')">
                                <span>
                                    💳 Online Payment
                                    <span style="font-size:0.72rem;color:var(--muted);display:block">UPI · Card ·
                                        NetBanking</span>
                                </span>
                            </label>

                            <label
                                style="display:flex;align-items:center;gap:0.8rem;cursor:pointer;
                               background:var(--dark4);border:2px solid var(--border);
                               border-radius:10px;padding:0.9rem 1.2rem;flex:1"
                                id="wallet-option">
                                <input type="radio" name="pay_choice" value="wallet" id="pay-wallet"
                                    onchange="togglePaymentMethod('wallet')">
                                <span>
                                    👛 Wallet Balance
                                    <span style="font-size:0.72rem;color:var(--gold);display:block">Available:
                                        ₹{{ number_format($user->wallet_balance, 2) }}</span>
                                </span>
                            </label>

                        </div>
                    </div>

                    {{-- Summary Box --}}
                    <div id="summary"
                        style="background:rgba(201,168,76,0.06);border:1px solid var(--border);border-radius:12px;padding:1.2rem;margin-bottom:1.5rem">
                        <div style="font-weight:600;margin-bottom:0.8rem;font-size:0.88rem">Participation Summary</div>
                        <div
                            style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.3rem 0;border-bottom:1px solid rgba(255,255,255,0.05)">
                            <span style="color:var(--muted)">Contribution</span>
                            <strong id="s-principal">₹0</strong>
                        </div>
                        <div
                            style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.3rem 0;border-bottom:1px solid rgba(255,255,255,0.05)">
                            <span style="color:var(--muted)">Daily Earning</span>
                            <strong id="s-daily" style="color:#22C55E">₹0</strong>
                        </div>
                        <div
                            style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.3rem 0;border-bottom:1px solid rgba(255,255,255,0.05)">
                            <span style="color:var(--muted)">Platform Fee ({{ $plan->commission_percent }}%) Daily</span>
                            <strong id="s-daily-fee" style="color:var(--red)">₹0</strong>
                        </div>
                        <div
                            style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.3rem 0;border-bottom:1px solid rgba(255,255,255,0.05)">
                            <span style="color:var(--muted)">Net Daily Earning</span>
                            <strong id="s-net-daily" style="color:var(--gold)">₹0</strong>
                        </div>
                        <div
                            style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.3rem 0;border-bottom:1px solid rgba(255,255,255,0.05)">
                            <span style="color:var(--muted)">Total Net Earnings (<span
                                    id="s-days">{{ $plan->duration_months * 30 }}</span> days)</span>
                            <strong id="s-total-earnings" style="color:var(--gold)">₹0</strong>
                        </div>
                        <div
                            style="display:flex;justify-content:space-between;font-size:0.9rem;padding:0.5rem 0;font-weight:700">
                            <span>Total (Plan Complete Hone Par)</span>
                            <span id="s-total" style="color:#22C55E">₹0</span>
                        </div>
                        <div style="font-size:0.72rem;color:var(--muted);margin-top:0.5rem">
                            📅 Maturity Date:
                            <strong>{{ now()->addMonths($plan->duration_months)->format('d M Y') }}</strong>
                        </div>
                    </div>

                    <button type="button" onclick="initiatePayment()" class="btn btn-gold btn-block"
                        style="font-size:1rem;padding:0.9rem">
                        🔒 Secure Payment Karein
                    </button>
                    <p style="text-align:center;font-size:0.72rem;color:var(--muted);margin-top:0.6rem">
                        Powered by Cashfree · SSL Secured · Instant Confirmation
                    </p>
                </form>
            @endif
        </div>

        {{-- Plan Info --}}
        <div>
            <div class="card" style="margin-bottom:1rem">
                <div class="card-title">📋 Plan Details</div>
                <div style="font-size:2rem;font-weight:900;color:#22C55E;margin-bottom:0.3rem">
                    {{ $plan->displayDailyEarningFormatted() }}</div>
                <div style="font-size:0.82rem;color:var(--muted);margin-bottom:1.5rem">Daily Earning</div>

                <div style="display:flex;flex-direction:column;gap:0.6rem">
                    <div
                        style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.5rem;background:var(--dark4);border-radius:8px">
                        <span style="color:var(--muted)">💵 Invest Amount</span>
                        <strong style="color:var(--gold)">₹{{ number_format($plan->min_amount) }}</strong>
                    </div>
                    <div
                        style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.5rem;background:var(--dark4);border-radius:8px">
                        <span style="color:var(--muted)">⏱️ Plan Duration</span>
                        <strong>{{ $plan->duration_months }} Months</strong>
                    </div>
                    <div
                        style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.5rem;background:var(--dark4);border-radius:8px">
                        <span style="color:var(--muted)">📊 Platform Fee</span>
                        <strong>{{ $plan->commission_percent }}% of daily earning</strong>
                    </div>
                    <div
                        style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);border-radius:8px;padding:0.7rem;font-size:0.8rem;color:#22C55E">
                        ✅ Koi lock-in nahi — kab bhi withdraw kar sakte ho. Pro-rated profit milega.
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- Cashfree JS SDK --}}
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
    <script>
        const planId = {{ $plan->id }};
        const cfEnv  = "{{ config('services.cashfree.env') === 'production' ? 'production' : 'sandbox' }}";

        function fmt(n) {
            return '₹' + parseFloat(n).toLocaleString('en-IN', {
                minimumFractionDigits: 2
            });
        }

        function togglePaymentMethod(val) {
            document.getElementById('hiddenPaymentMethod').value = val;
            const cfLabel     = document.getElementById('cashfree-option');
            const walletLabel = document.getElementById('wallet-option');
            if (val === 'wallet') {
                cfLabel.style.borderColor     = 'var(--border)';
                walletLabel.style.borderColor = 'var(--gold)';
            } else {
                cfLabel.style.borderColor     = 'var(--gold)';
                walletLabel.style.borderColor = 'var(--border)';
            }
        }

        async function calculateProfit(amount) {
            if (!amount || amount < 1) return;
            try {
                const res = await fetch(`{{ route('investments.calculate') }}?plan_id=${planId}&amount=${amount}`);
                const d   = await res.json();
                document.getElementById('s-principal').textContent      = fmt(d.principal);
                document.getElementById('s-daily').textContent          = fmt(d.daily_earning);
                document.getElementById('s-daily-fee').textContent      = fmt(d.daily_fee);
                document.getElementById('s-net-daily').textContent      = fmt(d.net_daily_earning);
                document.getElementById('s-days').textContent           = d.days;
                document.getElementById('s-total-earnings').textContent = fmt(d.total_earnings);
                document.getElementById('s-total').textContent          = fmt(d.total_return);
            } catch (e) {
                console.error(e);
            }
        }

        calculateProfit(document.getElementById('amount').value);

        async function initiatePayment() {
            const amount = document.getElementById('amount').value;
            const method = document.getElementById('hiddenPaymentMethod').value;

            if (!amount || amount < {{ $plan->min_amount }}) {
                alert('Minimum ₹{{ number_format($plan->min_amount) }} contribution zaroori hai.');
                return;
            }

            // Wallet flow
            if (method === 'wallet') {
                document.getElementById('investForm').submit();
                return;
            }

            // Cashfree flow
            try {
                const res = await fetch('{{ route('payment.order') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({ plan_id: planId, amount }),
                });

                if (!res.ok) {
                    const text = await res.text();
                    console.error('Server error:', text);
                    alert('Server error. Please try again.');
                    return;
                }

                const order = await res.json();
                if (order.error) {
                    alert(order.error);
                    return;
                }

                // Cashfree checkout open karo
                const cashfree = await load({ mode: cfEnv });

                const checkoutOptions = {
                    paymentSessionId: order.payment_session_id,
                    redirectTarget:   '_self', // same tab mein redirect
                };

                cashfree.checkout(checkoutOptions);

            } catch (e) {
                console.error('Payment error:', e);
                alert('Payment gateway error. Please try again.');
            }
        }
    </script>
@endpush