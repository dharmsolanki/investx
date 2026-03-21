@extends('layouts.admin')
@section('title', 'Manage Users')
@section('page-title', '👥 Manage Users')

@section('content')

<div class="card">
    {{-- Filters --}}
    <form method="GET" style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;align-items:end">
        <div>
            <label class="form-label">Search</label>
            <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Name, email, phone...">
        </div>
        <div>
            <label class="form-label">KYC Status</label>
            <select class="form-control" name="kyc">
                <option value="">All</option>
                <option value="pending" {{ request('kyc') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="submitted" {{ request('kyc') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="verified" {{ request('kyc') == 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="rejected" {{ request('kyc') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <button type="submit" class="btn btn-gold btn-sm">Filter</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">Reset</a>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Phone</th>
                    <th>KYC</th>
                    <th>Investments</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td style="color:var(--muted)">{{ $u->id }}</td>
                    <td>
                        <div style="font-weight:600">{{ $u->name }}</div>
                        <div style="font-size:0.72rem;color:var(--muted)">{{ $u->email }}</div>
                    </td>
                    <td>{{ $u->phone }}</td>
                    <td>
                        <span class="badge {{ $u->kyc_status }}">{{ ucfirst($u->kyc_status) }}</span>
                        @if($u->kyc_status === 'submitted')
                        <div style="display:flex;gap:0.4rem;margin-top:0.4rem">
                            <form action="{{ route('admin.users.kyc', $u) }}" method="POST">
                                @csrf
                                <input type="hidden" name="kyc_status" value="verified">
                                <button class="btn btn-gold btn-sm" style="padding:2px 8px;font-size:0.7rem">Verify</button>
                            </form>
                            <form action="{{ route('admin.users.kyc', $u) }}" method="POST">
                                @csrf
                                <input type="hidden" name="kyc_status" value="rejected">
                                <button class="btn btn-danger btn-sm" style="padding:2px 8px;font-size:0.7rem">Reject</button>
                            </form>
                        </div>
                        @endif
                    </td>
                    <td>
                        <div>{{ $u->investments_count ?? 0 }} plans</div>
                        <div style="font-size:0.72rem;color:var(--gold)">₹{{ number_format($u->investments->sum('principal_amount'), 0) }}</div>
                    </td>
                    <td style="font-size:0.8rem;color:var(--muted)">{{ $u->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge {{ $u->is_active ? 'active' : 'failed' }}">
                            {{ $u->is_active ? 'Active' : 'Suspended' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap">
                            <a href="{{ route('admin.users.show', $u) }}" class="btn btn-outline btn-sm">View</a>
                            <form action="{{ route('admin.users.toggle', $u) }}" method="POST">
                                @csrf
                                <button class="btn {{ $u->is_active ? 'btn-danger' : 'btn-gold' }} btn-sm">
                                    {{ $u->is_active ? 'Suspend' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $users->links() }}</div>
</div>

@endsection
