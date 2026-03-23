@extends('layouts.app')
@section('title', 'My Participations')
@section('page-title', 'My Participations')

@section('content')

{{-- Stats --}}
<div class="stats-grid" style="margin-bottom:2rem">
    <div class="stat-card">
        <div class="label">Total Contributed</div>
        <div class="value" style="color:var(--gold)">₹{{ number_format($stats['total_invested'], 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Active Plans</div>
        <div class="value">{{ $stats['active_count'] }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Withdraw Available</div>
        <div class="value" style="color:#FBB924">{{ $stats['active_count'] }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Total Returns Earned</div>
        <div class="value" style="color:var(--green)">₹{{ number_format($stats['total_profit'], 2) }}</div>
    </div>
</div>

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <div class="card-title" style="margin-bottom:0">All Participations</div>
        <a href="{{ route('plans') }}" class="btn btn-gold btn-sm">+ New Participation</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Plan</th>
                    <th>Contribution</th>
                    <th>Gross Earned</th>
                    <th>Platform Fee</th>
                    <th>Net Earned</th>
                    <th>Days</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($investments as $inv)
                @php
                    $daysInvested  = max(1, (int) $inv->invested_at->diffInDays(now()));
                    $grossSoFar    = round($inv->expected_profit * $daysInvested, 2);
                    $feeSoFar      = round($inv->commission_amount * $daysInvested, 2);
                    $netSoFar      = round($inv->net_profit * $daysInvested, 2);
                    $totalDays     = $inv->plan->duration_months * 30;
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:600">{{ $inv->plan->name }}</div>
                        <div style="font-size:0.72rem;color:var(--muted)">
                            {{ $inv->plan->displayDailyEarningFormatted() }} daily · {{ $inv->plan->duration_months }}mo
                        </div>
                    </td>
                    <td style="font-weight:600">₹{{ number_format($inv->principal_amount, 2) }}</td>

                    <td style="color:var(--green)">
                        ₹{{ number_format($grossSoFar, 2) }}
                        <div style="font-size:0.7rem;color:var(--muted)">₹{{ number_format($inv->expected_profit, 2) }}/day</div>
                    </td>

                    <td style="color:var(--red)">
                        ₹{{ number_format($feeSoFar, 2) }}
                        <div style="font-size:0.7rem;color:var(--muted)">₹{{ number_format($inv->commission_amount, 2) }}/day</div>
                    </td>

                    <td style="font-weight:600;color:var(--gold)">
                        ₹{{ number_format($netSoFar, 2) }}
                        <div style="font-size:0.7rem;color:var(--muted)">₹{{ number_format($inv->net_profit, 2) }}/day</div>
                    </td>

                    <td>
                        <div>{{ $daysInvested }} / {{ $totalDays }} days</div>
                        @if($inv->status === 'active')
                        <div style="margin-top:4px">
                            <div class="progress" style="width:80px">
                                <div class="progress-bar" style="width:{{ $inv->progressPercent() }}%"></div>
                            </div>
                            <div style="font-size:0.68rem;color:var(--muted);margin-top:2px">{{ $inv->progressPercent() }}%</div>
                        </div>
                        @endif
                    </td>

                    <td><span class="badge {{ $inv->status }}">{{ ucfirst($inv->status) }}</span></td>

                    <td>
                        @if(in_array($inv->status, ['active', 'matured']) && (!$inv->withdrawal || $inv->withdrawal->status === 'rejected'))
                            <a href="{{ route('withdrawals.index') }}" class="btn btn-gold btn-sm">Withdraw</a>
                        @elseif($inv->withdrawal)
                            <span class="badge {{ $inv->withdrawal->status }}">{{ ucfirst($inv->withdrawal->status) }}</span>
                        @else
                            <span style="color:var(--muted);font-size:0.8rem">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:3rem;color:var(--muted)">
                        Koi participation nahi abhi tak.<br>
                        <a href="{{ route('plans') }}" style="color:var(--gold)">Trading plans dekho aur participate karo →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem">{{ $investments->links() }}</div>
</div>

@endsection