@extends('layouts.admin')
@section('title', 'Manage Plans')
@section('page-title', '📈 Manage Plans')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div></div>
    <a href="{{ route('admin.plans.create') }}" class="btn btn-gold">+ New Plan</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Min Amount</th>
                    <th>Daily Earning</th>
                    <th>Duration</th>
                    <th>Platform Fee</th>
                    <th>Investments</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                <tr>
                    <td style="font-weight:600">{{ $plan->name }}</td>
                    <td style="color:var(--gold)">₹{{ number_format($plan->min_amount, 0) }}</td>
                    <td style="color:#22C55E">{{ $plan->displayDailyEarningFormatted() }}</td>
                    <td>{{ $plan->duration_months }} Months</td>
                    <td style="color:var(--red)">{{ $plan->commission_percent }}%</td>
                    <td>{{ $plan->investments_count }}</td>
                    <td>
                        <span class="badge {{ $plan->is_active ? 'active' : 'failed' }}">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem">
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-outline btn-sm">Edit</a>
                            <form action="{{ route('admin.plans.toggle', $plan) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm {{ $plan->is_active ? 'btn-danger' : 'btn-gold' }}">
                                    {{ $plan->is_active ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;color:var(--muted)">Koi plan nahi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection