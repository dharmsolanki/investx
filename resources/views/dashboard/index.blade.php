@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'My Dashboard')

@section('content')

{{-- KYC Warning --}}
@if($user->kyc_status !== 'verified')
<div class="alert warning">
    ⚠️ Aapka KYC <strong>{{ ucfirst($user->kyc_status) }}</strong> hai. 
    @if($user->kyc_status === 'pending')
        <a href="{{ route('kyc') }}" style="color:var(--gold);font-weight:600;">KYC complete karein →</a>
    @elseif($user->kyc_status === 'submitted')
        Verification 24 ghante mein hogi.
    @endif
</div>
@endif

{{-- STATS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="label">💰 Total Invested</div>
        <div class="value" style="color:var(--gold)">₹{{ number_format($totalInvested, 2) }}</div>
        <div class="change up">{{ $activeInvestments }} active plan{{ $activeInvestments != 1 ? 's' : '' }}</div>
    </div>
    <div class="stat-card">
        <div class="label">📈 Total Profit Earned</div>
        <div class="value" style="color:var(--green)">₹{{ number_format($totalProfit, 2) }}</div>
        <div class="change up">Withdrawn investments se</div>
    </div>
    <div class="stat-card">
        <div class="label">📊 Active Investments</div>
        <div class="value">{{ $activeInvestments }}</div>
        <div class="change" style="color:var(--muted)">Plans running</div>
    </div>
    <div class="stat-card">
        <div class="label">⏳ Pending Withdrawals</div>
        <div class="value" style="color:{{ $pendingWithdrawals > 0 ? '#FBB924' : 'var(--text)' }}">{{ $pendingWithdrawals }}</div>
        <div class="change" style="color:var(--muted)">Requests in queue</div>
    </div>
</div>

<div class="grid-2" style="margin-bottom:2rem">

    {{-- Active Investments --}}
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem">
            <div class="card-title" style="margin-bottom:0">Active Investments</div>
            <a href="{{ route('investments.my') }}" style="font-size:0.8rem;color:var(--gold);text-decoration:none">Sab dekho →</a>
        </div>

        @forelse($investments as $inv)
        <div style="padding:0.8rem 0;border-bottom:1px solid rgba(255,255,255,0.04)">
            <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:0.5rem">
                <div>
                    <div style="font-size:0.88rem;font-weight:600">{{ $inv->plan->name }}</div>
                    <div style="font-size:0.72rem;color:var(--muted)">
                        Maturity: {{ $inv->maturity_date?->format('d M Y') ?? 'N/A' }}
                    </div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:0.9rem;font-weight:600;color:var(--gold)">₹{{ number_format($inv->principal_amount, 0) }}</div>
                    <span class="badge {{ $inv->status }}">{{ ucfirst($inv->status) }}</span>
                </div>
            </div>
            @if($inv->status === 'active')
            <div class="progress">
                <div class="progress-bar" style="width:{{ $inv->progressPercent() }}%"></div>
            </div>
            <div style="font-size:0.7rem;color:var(--muted);margin-top:3px">{{ $inv->progressPercent() }}% complete • {{ $inv->daysRemaining() }} days left</div>
            @endif
        </div>
        @empty
        <div style="text-align:center;padding:2rem;color:var(--muted)">
            <div style="font-size:2rem;margin-bottom:0.5rem">📊</div>
            Koi active investment nahi.<br>
            <a href="{{ route('plans') }}" style="color:var(--gold)">Plans dekhein →</a>
        </div>
        @endforelse
    </div>

    {{-- Recent Transactions --}}
    <div class="card">
        <div class="card-title">Recent Transactions</div>
        @forelse($transactions as $txn)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:0.65rem 0;border-bottom:1px solid rgba(255,255,255,0.04)">
            <div>
                <span class="badge {{ $txn->type }}">{{ ucfirst($txn->type) }}</span>
                <div style="font-size:0.72rem;color:var(--muted);margin-top:3px">{{ $txn->created_at->format('d M Y, h:i A') }}</div>
            </div>
            <div style="font-weight:600;font-size:0.9rem;color:{{ in_array($txn->type, ['deposit','profit','referral_bonus']) ? 'var(--green)' : 'var(--red)' }}">
                {{ in_array($txn->type, ['deposit','profit','referral_bonus']) ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:2rem;color:var(--muted)">Koi transaction nahi abhi tak.</div>
        @endforelse
    </div>
</div>

{{-- Profit Chart --}}
<div class="card">
    <div class="card-title">Monthly Profit (Last 6 Months)</div>
    <div style="height:200px;position:relative">
        <canvas id="profitChart" height="200"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
const chartData = @json($chartData);
const ctx = document.getElementById('profitChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartData.map(d => d.month),
        datasets: [{
            label: 'Profit (₹)',
            data: chartData.map(d => d.profit),
            backgroundColor: 'rgba(201,168,76,0.3)',
            borderColor: '#C9A84C',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#7A8099', fontSize: 11 } },
            y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#7A8099', callback: v => '₹' + v.toLocaleString('en-IN') } }
        }
    }
});
</script>
@endpush
