@extends('layouts.app')
@section('title', 'Withdrawals')
@section('page-title', 'Withdrawals')

@section('content')

{{-- Available investments for withdrawal --}}
@if($availableInvestments->count() > 0)
<div class="card" style="margin-bottom:2rem">
    <div class="card-title">💸 Withdrawal Request Karein</div>

    @foreach($availableInvestments as $inv)
    @php
        $daysInvested  = (int) $inv->invested_at->diffInDays(now());
        $totalDays     = $inv->plan->duration_months * 30;
        $ratio         = min(1, $daysInvested / max(1, $totalDays));
        $proRatedProfit = round($inv->net_profit * $ratio, 2);
        $total          = $inv->principal_amount + $proRatedProfit;
    @endphp
    <div style="background:var(--dark4);border:1px solid var(--border);border-radius:12px;padding:1.2rem;margin-bottom:1rem">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:1rem">
            <div>
                <div style="font-weight:600">{{ $inv->plan->name }}</div>
                <div style="font-size:0.78rem;color:var(--muted)">
                    {{ $daysInvested }} din invest kiya hua · Invested: {{ $inv->invested_at->format('d M Y') }}
                </div>
            </div>
            <div style="text-align:right">
                <div style="font-size:1.1rem;font-weight:700;color:var(--gold)">₹{{ number_format($total, 2) }}</div>
                <div style="font-size:0.72rem;color:#22C55E">
                    ₹{{ number_format($inv->principal_amount, 2) }} + ₹{{ number_format($proRatedProfit, 2) }} profit
                </div>
                @if($daysInvested < $totalDays)
                <div style="font-size:0.7rem;color:var(--muted);margin-top:2px">
                    ({{ $daysInvested }}/{{ $totalDays }} days — pro-rated profit)
                </div>
                @endif
            </div>
        </div>

        <form action="{{ route('withdrawals.request') }}" method="POST">
            @csrf
            <input type="hidden" name="investment_id" value="{{ $inv->id }}">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:0.8rem;align-items:end">
                <div>
                    <label class="form-label">Bank Account No.</label>
                    <input class="form-control" name="bank_account"
                           value="{{ $user->bank_account }}" placeholder="Account number" required>
                </div>
                <div>
                    <label class="form-label">IFSC Code</label>
                    <input class="form-control" name="bank_ifsc"
                           value="{{ $user->bank_ifsc }}" placeholder="SBIN0001234" required>
                </div>
                <div>
                    <label class="form-label">Bank Name</label>
                    <input class="form-control" name="bank_name"
                           value="{{ $user->bank_name }}" placeholder="SBI / HDFC..." required>
                </div>
                <button type="submit" class="btn btn-gold"
                        onclick="return confirm('₹{{ number_format($total, 2) }} withdraw karna chahte ho?')">
                    Withdraw →
                </button>
            </div>
        </form>
    </div>
    @endforeach
</div>
@else
<div class="alert info" style="margin-bottom:2rem">
    ℹ️ Koi active investment nahi hai jiske liye withdrawal request ki ja sake.
    <a href="{{ route('plans') }}" style="color:var(--gold)">Plans dekho →</a>
</div>
@endif

{{-- Withdrawal History --}}
<div class="card">
    <div class="card-title">Withdrawal History</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Plan</th>
                    <th>Principal</th>
                    <th>Net Profit</th>
                    <th>Total Amount</th>
                    <th>Bank Details</th>
                    <th>UTR No.</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $w)
                <tr>
                    <td>{{ $w->investment?->plan->name ?? '👛 Wallet' }}</td>
                    <td>₹{{ number_format($w->principal_amount, 2) }}</td>
                    <td style="color:var(--green)">₹{{ number_format($w->net_profit, 2) }}</td>
                    <td style="font-weight:600;color:var(--gold)">₹{{ number_format($w->total_amount, 2) }}</td>
                    <td>
                        <div style="font-size:0.8rem">{{ $w->bank_name }}</div>
                        <div style="font-size:0.72rem;color:var(--muted)">
                            ••••{{ substr($w->bank_account ?? '', -4) }} · {{ $w->bank_ifsc }}
                        </div>
                    </td>
                    <td style="font-size:0.8rem">{{ $w->utr_number ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $w->status }}">{{ ucfirst($w->status) }}</span>
                        @if($w->rejection_reason)
                        <div style="font-size:0.7rem;color:var(--red);margin-top:2px">{{ $w->rejection_reason }}</div>
                        @endif
                    </td>
                    <td style="font-size:0.8rem;color:var(--muted)">{{ $w->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:3rem;color:var(--muted)">Koi withdrawal nahi abhi tak.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $withdrawals->links() }}</div>
</div>

@endsection