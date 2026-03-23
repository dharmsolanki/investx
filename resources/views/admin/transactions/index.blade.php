@extends('layouts.admin')
@section('title', 'Transactions')
@section('page-title', '📒 Transactions')

@section('content')

{{-- Stats --}}
<div class="stats-grid" style="margin-bottom:2rem">
    <div class="stat-card">
        <div class="label">Total Deposits</div>
        <div class="value" style="color:#22C55E">₹{{ number_format($stats['total_deposits'], 0) }}</div>
        <div class="change up">Today: ₹{{ number_format($stats['today_deposits'], 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Total Withdrawals</div>
        <div class="value" style="color:var(--red)">₹{{ number_format($stats['total_withdrawals'], 0) }}</div>
        <div class="change dn">Today: ₹{{ number_format($stats['today_withdrawals'], 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Total Profit Credited</div>
        <div class="value" style="color:var(--gold)">₹{{ number_format($stats['total_profit'], 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Platform Commission</div>
        <div class="value" style="color:var(--blue)">₹{{ number_format($stats['total_commission'], 0) }}</div>
        <div class="change" style="color:var(--muted)">Pending: {{ $stats['pending_count'] }}</div>
    </div>
</div>

<div class="card">

    {{-- Filters --}}
    <form method="GET" style="display:flex;gap:0.8rem;flex-wrap:wrap;margin-bottom:1.5rem;align-items:end">
        <div>
            <div style="font-size:0.75rem;color:var(--muted);margin-bottom:4px">Search</div>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" style="width:220px" placeholder="Name, email, payment ID...">
        </div>
        <div>
            <div style="font-size:0.75rem;color:var(--muted);margin-bottom:4px">Type</div>
            <select name="type" class="form-control">
                <option value="">All Types</option>
                @foreach(['deposit','withdrawal','profit','commission','refund','referral_bonus'] as $t)
                <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <div style="font-size:0.75rem;color:var(--muted);margin-bottom:4px">Status</div>
            <select name="status" class="form-control">
                <option value="">All Status</option>
                @foreach(['pending','completed','failed','refunded'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <div style="font-size:0.75rem;color:var(--muted);margin-bottom:4px">From</div>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
        </div>
        <div>
            <div style="font-size:0.75rem;color:var(--muted);margin-bottom:4px">To</div>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-gold">🔍 Filter</button>
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline">Reset</a>
        <a href="{{ route('admin.transactions.index', array_merge(request()->all(), ['export' => 1])) }}"
           class="btn btn-outline" style="margin-left:auto">📥 Export CSV</a>
    </form>

    {{-- Table --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Payment ID</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $txn)
                <tr>
                    <td style="color:var(--muted);font-size:0.8rem">{{ $txn->id }}</td>
                    <td>
                        <div style="font-weight:600;font-size:0.85rem">{{ $txn->user->name ?? '—' }}</div>
                        <div style="font-size:0.72rem;color:var(--muted)">{{ $txn->user->phone ?? '' }}</div>
                    </td>
                    <td><span class="badge {{ $txn->type }}">{{ ucfirst($txn->type) }}</span></td>
                    <td style="font-weight:700;color:{{ in_array($txn->type, ['deposit','profit','refund','referral_bonus']) ? 'var(--green)' : 'var(--red)' }}">
                        {{ in_array($txn->type, ['deposit','profit','refund','referral_bonus']) ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
                    </td>
                    <td style="font-size:0.8rem;color:var(--muted)">{{ $txn->payment_method ?? '—' }}</td>
                    <td style="font-size:0.75rem;color:var(--muted);max-width:120px;overflow:hidden;text-overflow:ellipsis">
                        {{ $txn->payment_id ?? '—' }}
                    </td>
                    <td style="font-size:0.8rem">{{ $txn->investment?->plan?->name ?? '—' }}</td>
                    <td><span class="badge {{ $txn->status }}">{{ ucfirst($txn->status) }}</span></td>
                    <td style="font-size:0.75rem;color:var(--muted);max-width:150px">{{ Str::limit($txn->notes, 40) ?? '—' }}</td>
                    <td style="font-size:0.78rem;color:var(--muted);white-space:nowrap">{{ $txn->created_at->format('d M Y') }}<br>{{ $txn->created_at->format('h:i A') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:3rem;color:var(--muted)">Koi transaction nahi mili.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem">{{ $transactions->links() }}</div>
</div>

@endsection