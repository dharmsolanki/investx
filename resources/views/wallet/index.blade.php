@extends('layouts.app')
@section('title', 'Wallet')
@section('page-title', '👛 Wallet')

@section('content')

    {{-- Stats --}}
    <div class="stats-grid" style="margin-bottom:2rem">
        <div class="stat-card">
            <div class="label">👛 Available Balance</div>
            <div class="value" style="color:var(--gold)">₹{{ number_format($user->wallet_balance, 2) }}</div>
            <div style="font-size:0.72rem;color:var(--green);margin-top:0.4rem;font-weight:600">
                ✅ Sirf yahi amount withdraw ho sakti hai
            </div>
        </div>
        <div class="stat-card">
            <div class="label">📥 Total Credits</div>
            <div class="value" style="color:var(--green)">
                ₹{{ number_format($user->transactions->whereIn('type', ['deposit', 'profit', 'referral_bonus'])->sum('amount'), 2) }}
            </div>
            <div style="font-size:0.72rem;color:var(--muted);margin-top:0.4rem">
                Kabhi bhi aaye sabhi paise ka total
            </div>
        </div>
        <div class="stat-card">
            <div class="label">📤 Total Debits</div>
            <div class="value" style="color:var(--red)">
                ₹{{ number_format($user->transactions->whereIn('type', ['withdrawal', 'commission'])->sum('amount'), 2) }}
            </div>
            <div style="font-size:0.72rem;color:var(--muted);margin-top:0.4rem">
                Abhi tak nikale / invest kiye gaye paise
            </div>
        </div>
    </div>

    {{-- Wallet explanation box --}}
    <div
        style="background:rgba(201,168,76,0.06);border:1px solid rgba(201,168,76,0.2);border-radius:12px;padding:1rem;margin-bottom:2rem;font-size:0.82rem">
        <div style="font-weight:700;color:var(--gold);margin-bottom:0.6rem">💡 Wallet kaise kaam karta hai?</div>
        <div style="display:flex;flex-direction:column;gap:0.4rem;color:var(--muted)">
            <div>🟢 <strong style="color:var(--text)">Available Balance</strong> = jo paise abhi aapke wallet mein hain —
                yahi sirf withdraw ya invest ho sakta hai</div>
            <div>📥 <strong style="color:var(--text)">Total Credits</strong> = aaj tak wallet mein aaye sabhi paise ka total
                (mature investments + top-ups) — yeh sirf ek hisaab hai, withdraw nahi hoga</div>
            <div>📤 <strong style="color:var(--text)">Total Debits</strong> = aaj tak wallet se gaye sabhi paise
                (withdrawals + investments) — yeh bhi sirf hisaab hai</div>
            <div style="margin-top:0.4rem;padding-top:0.6rem;border-top:1px solid rgba(255,255,255,0.05)">
                ⚠️ <strong style="color:var(--text)">Withdraw sirf Available Balance se hoga</strong> — Total Credits se
                nahi
            </div>
        </div>
    </div>

    {{-- Active Investments - Maturity Info --}}
    @if ($activeInvestments->count() > 0)
        <div class="card" style="margin-bottom:2rem">
            <div class="card-title">📅 Invest Kiya Hua Paisa — Kab Milega?</div>

            <div style="font-size:0.82rem;color:var(--muted);margin-bottom:1rem">
                ⚠️ Invest kiya hua paisa <strong style="color:var(--text)">maturity date tak locked</strong> rahega.
                Maturity par automatically wallet mein aa jayega, tab withdraw kar sakte ho.
            </div>

            <div style="display:flex;flex-direction:column;gap:0.8rem">
                @foreach ($activeInvestments as $inv)
                    @php
                        $daysLeft = (int) now()
                            ->startOfDay()
                            ->diffInDays($inv->maturity_date->startOfDay());
                        $totalReturn = $inv->principal_amount + $inv->net_profit;
                        $isSoon = $daysLeft <= 7;
                    @endphp
                    <div
                        style="background:var(--dark4);border-radius:12px;padding:1rem;border-left:3px solid {{ $isSoon ? '#22C55E' : 'var(--border)' }}">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.6rem">
                            <div style="font-weight:700">{{ $inv->plan->name }}</div>
                            @if ($isSoon)
                                <span
                                    style="font-size:0.7rem;background:rgba(34,197,94,0.15);color:#22C55E;padding:2px 10px;border-radius:50px;font-weight:700">
                                    🔔 Jald Mature
                                </span>
                            @endif
                        </div>
                        <div style="display:flex;gap:1.5rem;font-size:0.82rem;flex-wrap:wrap">
                            <div>
                                <div style="color:var(--muted)">Invest Kiya</div>
                                <strong style="color:var(--gold)">₹{{ number_format($inv->principal_amount, 2) }}</strong>
                            </div>
                            <div>
                                <div style="color:var(--muted)">Wallet Mein Aayega</div>
                                <strong style="color:#22C55E">₹{{ number_format($totalReturn, 2) }}</strong>
                                <div style="font-size:0.7rem;color:var(--muted)">
                                    ₹{{ number_format($inv->principal_amount, 2) }} +
                                    ₹{{ number_format($inv->net_profit, 2) }} profit
                                </div>
                            </div>
                            <div>
                                <div style="color:var(--muted)">Maturity Date</div>
                                <strong>{{ $inv->maturity_date->format('d M Y') }}</strong>
                            </div>
                            <div>
                                <div style="color:var(--muted)">Baaki Days</div>
                                <strong style="color:{{ $isSoon ? '#22C55E' : 'var(--text)' }}">
                                    @if ($daysLeft <= 0)
                                        🎉 Aaj Mature!
                                    @elseif($daysLeft == 1)
                                        Kal Mature Hoga
                                    @else
                                        {{ $daysLeft }} din baaki
                                    @endif
                                </strong>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        <div style="margin-top:0.8rem">
                            <div
                                style="display:flex;justify-content:space-between;font-size:0.7rem;color:var(--muted);margin-bottom:4px">
                                <span>{{ $inv->invested_at->format('d M Y') }}</span>
                                <span>{{ $inv->progressPercent() }}% complete</span>
                                <span>{{ $inv->maturity_date->format('d M Y') }}</span>
                            </div>
                            <div style="background:var(--dark3);border-radius:50px;height:6px">
                                <div
                                    style="background:{{ $isSoon ? '#22C55E' : 'var(--gold)' }};width:{{ $inv->progressPercent() }}%;height:6px;border-radius:50px;transition:width 0.3s">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($activeInvestments->sum(fn($i) => $i->principal_amount + $i->net_profit) > 0)
                <div
                    style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border);display:flex;justify-content:space-between;font-size:0.85rem">
                    <span style="color:var(--muted)">Kul Expected Wallet Credit (sab mature hone par)</span>
                    <strong style="color:#22C55E">
                        ₹{{ number_format($activeInvestments->sum(fn($i) => $i->principal_amount + $i->net_profit), 2) }}
                    </strong>
                </div>
            @endif
        </div>
    @endif

    {{-- Top-up & Withdraw --}}
    <div class="grid-2" style="margin-bottom:2rem">

        {{-- Top-up --}}
        <div class="card">
            <div class="card-title">➕ Wallet Top-up</div>
            <div class="form-group">
                <label class="form-label">Amount (₹)</label>
                <input class="form-control" type="number" id="topupAmount" min="100" placeholder="Min ₹100"
                    value="500">
            </div>
            <button onclick="initiateTopup()" class="btn btn-gold btn-block">
                🔒 Pay & Add to Wallet
            </button>
            <p style="text-align:center;font-size:0.72rem;color:var(--muted);margin-top:0.6rem">Powered by Razorpay ·
                Instant Credit</p>
        </div>

        {{-- Withdraw to Bank --}}
        <div class="card">
            <div class="card-title">🏦 Withdraw to Bank</div>

            {{-- Eligibility Status --}}
            <div style="background:var(--dark4);border-radius:12px;padding:1rem;margin-bottom:1.2rem">
                <div style="font-size:0.82rem;font-weight:600;margin-bottom:0.8rem;color:var(--text)">
                    Aapki Withdrawal Eligibility:
                </div>

                {{-- KYC --}}
                <div style="display:flex;align-items:center;gap:0.6rem;font-size:0.82rem;padding:0.3rem 0">
                    <span>{{ $eligibility['kyc_verified'] ? '✅' : '❌' }}</span>
                    <span style="color:{{ $eligibility['kyc_verified'] ? 'var(--text)' : 'var(--red)' }}">
                        KYC Verified
                    </span>
                    @if (!$eligibility['kyc_verified'])
                        <a href="{{ route('kyc') }}" style="color:var(--gold);font-size:0.75rem;margin-left:auto">KYC
                            Karein →</a>
                    @endif
                </div>

                {{-- Balance --}}
                <div style="display:flex;align-items:center;gap:0.6rem;font-size:0.82rem;padding:0.3rem 0">
                    <span>{{ $eligibility['has_balance'] ? '✅' : '❌' }}</span>
                    <span style="color:{{ $eligibility['has_balance'] ? 'var(--text)' : 'var(--red)' }}">
                        Minimum ₹500 balance
                    </span>
                    <span style="margin-left:auto;color:var(--gold);font-size:0.78rem">
                        Available: ₹{{ number_format($user->wallet_balance, 2) }}
                    </span>
                </div>

                {{-- Pending Request --}}
                <div style="display:flex;align-items:center;gap:0.6rem;font-size:0.82rem;padding:0.3rem 0">
                    <span>{{ $eligibility['no_pending'] ? '✅' : '❌' }}</span>
                    <span style="color:{{ $eligibility['no_pending'] ? 'var(--text)' : 'var(--red)' }}">
                        Koi pending request nahi
                    </span>
                    @if (!$eligibility['no_pending'])
                        <span style="margin-left:auto;font-size:0.75rem;color:var(--muted)">Wait karein</span>
                    @endif
                </div>

                {{-- Daily Limit --}}
                <div style="display:flex;align-items:center;gap:0.6rem;font-size:0.82rem;padding:0.3rem 0">
                    <span>{{ $eligibility['daily_limit_ok'] ? '✅' : '❌' }}</span>
                    <span style="color:{{ $eligibility['daily_limit_ok'] ? 'var(--text)' : 'var(--red)' }}">
                        Daily limit available
                    </span>
                    <span style="margin-left:auto;font-size:0.78rem;color:var(--muted)">
                        @if ($eligibility['today_withdrawn'] > 0)
                            Aaj: ₹{{ number_format($eligibility['today_withdrawn'], 2) }} / ₹50,000
                        @else
                            Limit: ₹50,000/day
                        @endif
                    </span>
                </div>

                {{-- Overall status --}}
                <div
                    style="margin-top:0.8rem;padding-top:0.8rem;border-top:1px solid var(--border);font-size:0.85rem;font-weight:700;
                    color:{{ $eligibility['is_eligible'] ? '#22C55E' : 'var(--red)' }}">
                    {{ $eligibility['is_eligible'] ? '🟢 Aap abhi withdraw kar sakte ho' : '🔴 Abhi withdrawal available nahi' }}
                </div>
            </div>

            {{-- Rules info --}}
            <div
                style="background:var(--dark4);border-radius:10px;padding:0.8rem;margin-bottom:1.2rem;font-size:0.8rem;color:var(--muted)">
                <div style="margin-bottom:0.3rem">📌 Withdrawal Rules:</div>
                <div>• Minimum: <strong style="color:var(--text)">₹500</strong></div>
                <div>• Daily Limit: <strong style="color:var(--text)">₹50,000</strong></div>
                <div>• Ek time par sirf 1 pending request allowed</div>
                <div>• KYC verified hona zaroori</div>
            </div>

            @if ($eligibility['is_eligible'])
                <form action="{{ route('wallet.withdraw') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Amount (₹)</label>
                        <input class="form-control" type="number" name="amount" min="500"
                            max="{{ min($user->wallet_balance, $eligibility['daily_remaining']) }}"
                            placeholder="Min ₹500 · Max ₹{{ number_format(min($user->wallet_balance, $eligibility['daily_remaining']), 0) }}"
                            required>
                        <div style="font-size:0.75rem;color:var(--muted);margin-top:4px">
                            Available: <strong
                                style="color:var(--gold)">₹{{ number_format($user->wallet_balance, 2) }}</strong>
                            · Aaj withdraw ho sakta hai: <strong
                                style="color:var(--gold)">₹{{ number_format($eligibility['daily_remaining'], 0) }}</strong>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bank Name</label>
                        <input class="form-control" type="text" name="bank_name" value="{{ $user->bank_name }}"
                            placeholder="e.g. SBI, HDFC" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Account Number</label>
                        <input class="form-control" type="text" name="bank_account"
                            value="{{ $user->bank_account }}" placeholder="Account number" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">IFSC Code</label>
                        <input class="form-control" type="text" name="bank_ifsc" value="{{ $user->bank_ifsc }}"
                            placeholder="e.g. SBIN0001234" style="text-transform:uppercase" required>
                    </div>
                    <button type="submit" class="btn btn-outline btn-block"
                        onclick="return confirm('₹' + document.querySelector('[name=amount]').value + ' withdraw karna chahte ho?')">
                        💸 Withdrawal Request Karein
                    </button>
                </form>
            @endif

        </div>

    </div>

    {{-- Transaction History --}}
    <div class="card">
        <div class="card-title">📋 Wallet Ledger</div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                        <tr>
                            <td><span class="badge {{ $txn->type }}">{{ ucfirst($txn->type) }}</span></td>
                            <td
                                style="color:{{ in_array($txn->type, ['deposit', 'profit', 'referral_bonus']) ? 'var(--green)' : 'var(--red)' }};font-weight:600">
                                {{ in_array($txn->type, ['deposit', 'profit', 'referral_bonus']) ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
                            </td>
                            <td><span class="badge {{ $txn->status }}">{{ ucfirst($txn->status) }}</span></td>
                            <td style="font-size:0.8rem;color:var(--muted)">{{ $txn->notes ?? '—' }}</td>
                            <td style="font-size:0.8rem;color:var(--muted)">{{ $txn->created_at->format('d M Y, h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:2rem;color:var(--muted)">Abhi koi wallet
                                transaction nahi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem">{{ $transactions->links() }}</div>
    </div>

@endsection

@push('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        async function initiateTopup() {
            const amount = document.getElementById('topupAmount').value;
            if (!amount || amount < 100) {
                alert('Minimum ₹100 top-up zaroori hai.');
                return;
            }

            try {
                const res = await fetch('{{ route('wallet.topup.order') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({
                        amount
                    }),
                });

                const order = await res.json();
                if (order.error) {
                    alert(order.error);
                    return;
                }

                const options = {
                    key: order.key,
                    amount: order.amount,
                    currency: order.currency,
                    name: order.name,
                    description: 'Wallet Top-up',
                    order_id: order.order_id,
                    prefill: {
                        name: order.user_name,
                        email: order.user_email,
                        contact: order.user_phone,
                    },
                    theme: {
                        color: '#C9A84C'
                    },
                    handler: function(response) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('wallet.topup.verify') }}';

                        const fields = {
                            '_token': '{{ csrf_token() }}',
                            'razorpay_order_id': response.razorpay_order_id,
                            'razorpay_payment_id': response.razorpay_payment_id,
                            'razorpay_signature': response.razorpay_signature,
                            'amount': amount,
                        };

                        Object.entries(fields).forEach(([name, val]) => {
                            const el = document.createElement('input');
                            el.type = 'hidden';
                            el.name = name;
                            el.value = val;
                            form.appendChild(el);
                        });

                        document.body.appendChild(form);
                        form.submit();
                    }
                };

                const rzp = new Razorpay(options);
                rzp.on('payment.failed', (resp) => alert('Payment failed: ' + resp.error.description));
                rzp.open();

            } catch (e) {
                console.error(e);
                alert('Gateway error. Please try again.');
            }
        }
    </script>
@endpush
