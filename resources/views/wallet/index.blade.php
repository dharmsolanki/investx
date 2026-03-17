@extends('layouts.app')
@section('title', 'Wallet')
@section('page-title', '👛 Wallet')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="label">Available Balance</div>
        <div class="value" style="color:var(--gold)">₹{{ number_format($user->wallet_balance, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Total Credits</div>
        <div class="value" style="color:var(--green)">₹{{ number_format($totalCredits, 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Total Debits</div>
        <div class="value" style="color:var(--red)">₹{{ number_format($totalDebits, 2) }}</div>
    </div>
</div>

<div class="card">
    <div class="card-title">Wallet Ledger</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Type</th><th>Amount</th><th>Status</th><th>Notes</th><th>Date</th></tr>
            </thead>
            <tbody>
                @forelse($transactions as $txn)
                <tr>
                    <td><span class="badge {{ $txn->type }}">{{ ucfirst($txn->type) }}</span></td>
                    <td style="color:{{ in_array($txn->type,['deposit','profit','referral_bonus']) ? 'var(--green)' : 'var(--red)' }};font-weight:600">
                        {{ in_array($txn->type,['deposit','profit','referral_bonus']) ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
                    </td>
                    <td><span class="badge {{ $txn->status }}">{{ ucfirst($txn->status) }}</span></td>
                    <td style="font-size:0.8rem;color:var(--muted)">{{ $txn->notes ?? '—' }}</td>
                    <td style="font-size:0.8rem;color:var(--muted)">{{ $txn->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--muted)">Abhi koi wallet transaction nahi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $transactions->links() }}</div>
</div>
@endsection
