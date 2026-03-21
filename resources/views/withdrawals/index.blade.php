@extends('layouts.app')
@section('title', 'Withdrawals')
@section('page-title', 'Withdrawals')

@section('content')

{{-- Matured investments ready for withdrawal --}}
@if($maturedInvestments->count() > 0)
<div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:14px;padding:1.5rem;margin-bottom:2rem">
    <div style="font-weight:600;color:var(--green);margin-bottom:1rem">🎉 {{ $maturedInvestments->count() }} Investment(s) Mature Ho Gaye!</div>

    @foreach($maturedInvestments as $inv)
    <div style="background:var(--dark3);border:1px solid var(--border);border-radius:12px;padding:1.2rem;margin-bottom:1rem">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:1rem">
            <div>
                <div style="font-weight:600">{{ $inv->plan->name }}</div>
                <div style="font-size:0.78rem;color:var(--muted)">Mature: {{ $inv->maturity_date->format('d M Y') }}</div>
            </div>
            <div style="text-align:right">
                <div style="font-size:1.1rem;font-weight:700;color:var(--gold)">₹{{ number_format($inv->principal_amount + $inv->net_profit, 2) }}</div>
                <div style="font-size:0.72rem;color:var(--green)">+₹{{ number_format($inv->net_profit, 2) }} profit</div>
            </div>
        </div>

        <form action="{{ route('withdrawals.request') }}" method="POST">
            @csrf
            <input type="hidden" name="investment_id" value="{{ $inv->id }}">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:0.8rem;align-items:end">
                <div>
                    <label class="form-label">Bank Account No.</label>
                    <input class="form-control" name="bank_account" value="{{ $user->bank_account }}" placeholder="Account number" required>
                </div>
                <div>
                    <label class="form-label">IFSC Code</label>
                    <input class="form-control" name="bank_ifsc" value="{{ $user->bank_ifsc }}" placeholder="SBIN0001234" required>
                </div>
                <div>
                    <label class="form-label">Bank Name</label>
                    <input class="form-control" name="bank_name" value="{{ $user->bank_name }}" placeholder="SBI / HDFC..." required>
                </div>
                <button type="submit" class="btn btn-gold" onclick="return confirm('₹{{ number_format($inv->principal_amount + $inv->net_profit, 2) }} withdraw karna chahte ho?')">
                    Withdraw →
                </button>
            </div>
        </form>
    </div>
    @endforeach
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
                    <td>{{ $w->investment?->plan->name ?? '👛 Wallet Withdrawal' }}</td>
                    <td>₹{{ number_format($w->principal_amount, 2) }}</td>
                    <td style="color:var(--green)">₹{{ number_format($w->net_profit, 2) }}</td>
                    <td style="font-weight:600;color:var(--gold)">₹{{ number_format($w->total_amount, 2) }}</td>
                    <td>
                        <div style="font-size:0.8rem">{{ $w->bank_name }}</div>
                        <div style="font-size:0.72rem;color:var(--muted)">••••{{ substr($w->bank_account, -4) }} · {{ $w->bank_ifsc }}</div>
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
