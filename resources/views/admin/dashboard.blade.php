@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', '⚙️ Admin Dashboard')

@section('content')

{{-- Stats --}}
<div class="stats-grid" style="margin-bottom:2rem">
    <div class="stat-card">
        <div class="label">Total Members</div>
        <div class="value" style="color:var(--gold)">{{ number_format($stats['total_users']) }}</div>
        <div class="change up">+{{ $stats['total_users_today'] }} today</div>
    </div>
    <div class="stat-card">
        <div class="label">KYC Pending Review</div>
        <div class="value" style="color:#FBB924">{{ $stats['kyc_pending'] }}</div>
        <div class="change" style="color:var(--muted)">Needs review</div>
    </div>
    <div class="stat-card">
        <div class="label">Total Contributions</div>
        <div class="value" style="color:var(--green)">₹{{ number_format($stats['total_invested'], 0) }}</div>
        <div class="change up">{{ $stats['active_investments'] }} active plans</div>
    </div>
    <div class="stat-card">
        <div class="label">Total Management Fee</div>
        <div class="value" style="color:var(--gold)">₹{{ number_format($stats['total_commission'], 0) }}</div>
        <div class="change" style="color:var(--muted)">All time earnings</div>
    </div>
    <div class="stat-card">
        <div class="label">Pending Withdrawals</div>
        <div class="value" style="color:var(--red)">{{ $stats['pending_withdrawals'] }}</div>
        <div class="change" style="color:var(--muted)">Needs approval</div>
    </div>
    <div class="stat-card">
        <div class="label">Today's Contributions</div>
        <div class="value">₹{{ number_format($stats['today_deposits'], 0) }}</div>
        <div class="change up">Today</div>
    </div>
    <div class="stat-card">
        <div class="label">Active Participations</div>
        <div class="value">{{ $stats['active_investments'] }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Quick Links</div>
        <div style="display:flex;flex-direction:column;gap:0.4rem;margin-top:0.4rem">
            <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-gold btn-sm">Withdrawals →</a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">Members →</a>
        </div>
    </div>
</div>

<div class="grid-2">

    {{-- Pending Withdrawals --}}
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem">
            <div class="card-title" style="margin-bottom:0">🔔 Pending Withdrawals</div>
            <a href="{{ route('admin.withdrawals.index') }}" style="font-size:0.8rem;color:var(--gold);text-decoration:none">Sab dekho →</a>
        </div>

        @forelse($pendingWithdrawals as $w)
        <div style="padding:0.8rem 0;border-bottom:1px solid rgba(255,255,255,0.04)">
            <div style="display:flex;justify-content:space-between;align-items:start">
                <div>
                    <div style="font-size:0.88rem;font-weight:600">{{ $w->user->name }}</div>
                    <div style="font-size:0.72rem;color:var(--muted)">{{ $w->investment->plan->name }}</div>
                </div>
                <div style="text-align:right">
                    <div style="font-weight:700;color:var(--gold)">₹{{ number_format($w->total_amount, 0) }}</div>
                    <div style="font-size:0.7rem;color:var(--muted)">{{ $w->created_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:1.5rem;color:var(--muted)">✅ Koi pending withdrawal nahi</div>
        @endforelse
    </div>

    {{-- Recent Members --}}
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem">
            <div class="card-title" style="margin-bottom:0">👥 New Members</div>
            <a href="{{ route('admin.users.index') }}" style="font-size:0.8rem;color:var(--gold);text-decoration:none">Sab dekho →</a>
        </div>

        @foreach($recentUsers as $u)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:0.65rem 0;border-bottom:1px solid rgba(255,255,255,0.04)">
            <div>
                <div style="font-size:0.88rem;font-weight:600">{{ $u->name }}</div>
                <div style="font-size:0.72rem;color:var(--muted)">{{ $u->email }}</div>
            </div>
            <span class="badge {{ $u->kyc_status }}">{{ ucfirst($u->kyc_status) }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- Monthly Revenue Chart --}}
<div class="card" style="margin-top:1.5rem">
    <div class="card-title">📊 Monthly Performance (Last 6 Months)</div>
    <canvas id="revenueChart" height="80"></canvas>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
const monthlyData = @json($monthlyData);
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: monthlyData.map(d => d.month),
        datasets: [
            {
                label: 'Total Contributions',
                data: monthlyData.map(d => d.invested),
                backgroundColor: 'rgba(59,130,246,0.4)',
                borderColor: '#3B82F6',
                borderWidth: 2, borderRadius: 6,
            },
            {
                label: 'Management Fee Earned',
                data: monthlyData.map(d => d.commission),
                backgroundColor: 'rgba(201,168,76,0.4)',
                borderColor: '#C9A84C',
                borderWidth: 2, borderRadius: 6,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#7A8099' } } },
        scales: {
            x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#7A8099' } },
            y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#7A8099', callback: v => '₹' + v.toLocaleString('en-IN') } }
        }
    }
});
</script>
@endpush