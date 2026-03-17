@extends('layouts.app')
@section('title', 'User Details')
@section('page-title', '👤 User Details')

@section('content')

<div style="margin-bottom:1.5rem">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">← Back to Users</a>
</div>

<div class="grid-2" style="margin-bottom:1.5rem">

    {{-- User Info --}}
    <div class="card">
        <div class="card-title">Personal Information</div>
        <div style="display:flex;flex-direction:column;gap:0.7rem">
            @foreach([
                ['Name', $user->name],
                ['Email', $user->email],
                ['Phone', $user->phone],
                ['Joined', $user->created_at->format('d M Y, h:i A')],
                ['Referral Code', $user->referral_code ?? 'N/A'],
            ] as [$label, $value])
            <div style="display:flex;justify-content:space-between;padding:0.6rem;background:var(--dark4);border-radius:8px">
                <span style="color:var(--muted);font-size:0.85rem">{{ $label }}</span>
                <strong>{{ $value }}</strong>
            </div>
            @endforeach
            <div style="display:flex;justify-content:space-between;padding:0.6rem;background:var(--dark4);border-radius:8px">
                <span style="color:var(--muted);font-size:0.85rem">Status</span>
                <span class="badge {{ $user->is_active ? 'active' : 'failed' }}">{{ $user->is_active ? 'Active' : 'Suspended' }}</span>
            </div>
        </div>
        <form action="{{ route('admin.users.toggle', $user) }}" method="POST" style="margin-top:1rem">
            @csrf
            <button class="btn {{ $user->is_active ? 'btn-danger' : 'btn-gold' }} btn-block">
                {{ $user->is_active ? '🚫 Suspend User' : '✅ Activate User' }}
            </button>
        </form>
    </div>

    {{-- KYC Details --}}
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem">
            <div class="card-title" style="margin-bottom:0">KYC Information</div>
            <span class="badge {{ $user->kyc_status }}">{{ ucfirst($user->kyc_status) }}</span>
        </div>

        <div style="display:flex;flex-direction:column;gap:0.7rem;margin-bottom:1.5rem">
            @foreach([
                ['PAN Number', $user->pan_number ?? '—'],
                ['Aadhaar', $user->aadhar_number ? '••••••••'.substr($user->aadhar_number,-4) : '—'],
                ['Bank Name', $user->bank_name ?? '—'],
                ['Bank Account', $user->bank_account ?? '—'],
                ['IFSC Code', $user->bank_ifsc ?? '—'],
            ] as [$label, $value])
            <div style="display:flex;justify-content:space-between;padding:0.6rem;background:var(--dark4);border-radius:8px">
                <span style="color:var(--muted);font-size:0.85rem">{{ $label }}</span>
                <strong>{{ $value }}</strong>
            </div>
            @endforeach
        </div>

        {{-- KYC Action Buttons --}}
        @if(in_array($user->kyc_status, ['submitted', 'pending', 'rejected']))
        <form action="{{ route('admin.users.kyc', $user) }}" method="POST">
            @csrf
            <div style="margin-bottom:0.8rem">
                <label class="form-label">Rejection Reason (sirf reject karne pe)</label>
                <input class="form-control" type="text" name="reason"
                       placeholder="e.g. Documents unclear, PAN mismatch..."
                       value="{{ $user->kyc_rejection_reason }}">
            </div>
            <div style="display:flex;gap:0.8rem">
                <button type="submit" name="kyc_status" value="verified" class="btn btn-gold" style="flex:1">
                    ✅ Verify KYC
                </button>
                <button type="submit" name="kyc_status" value="rejected" class="btn btn-danger" style="flex:1">
                    ❌ Reject KYC
                </button>
            </div>
        </form>
        @elseif($user->kyc_status === 'verified')
        <div class="alert success">✅ KYC Already Verified Hai</div>
        @endif
    </div>
</div>

{{-- KYC DOCUMENTS IMAGES --}}
@if($user->pan_image || $user->aadhar_front_image || $user->aadhar_back_image || $user->bank_passbook_image)
<div class="card" style="margin-bottom:1.5rem">
    <div class="card-title">📁 Submitted KYC Documents</div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem">

        @if($user->pan_image)
        <div>
            <div style="font-size:0.78rem;color:var(--muted);margin-bottom:0.5rem;font-weight:600">PAN Card</div>
            <a href="{{ asset('storage/' . $user->pan_image) }}" target="_blank">
                <img src="{{ asset('storage/' . $user->pan_image) }}"
                     style="width:100%;border-radius:8px;border:1px solid var(--border);cursor:zoom-in;transition:transform 0.2s"
                     onmouseover="this.style.transform='scale(1.03)'"
                     onmouseout="this.style.transform='scale(1)'"
                     title="Click to view full size">
            </a>
            <div style="font-size:0.7rem;color:var(--gold);margin-top:0.3rem;text-align:center">🔍 Click to enlarge</div>
        </div>
        @endif

        @if($user->aadhar_front_image)
        <div>
            <div style="font-size:0.78rem;color:var(--muted);margin-bottom:0.5rem;font-weight:600">Aadhaar Front</div>
            <a href="{{ asset('storage/' . $user->aadhar_front_image) }}" target="_blank">
                <img src="{{ asset('storage/' . $user->aadhar_front_image) }}"
                     style="width:100%;border-radius:8px;border:1px solid var(--border);cursor:zoom-in;transition:transform 0.2s"
                     onmouseover="this.style.transform='scale(1.03)'"
                     onmouseout="this.style.transform='scale(1)'"
                     title="Click to view full size">
            </a>
            <div style="font-size:0.7rem;color:var(--gold);margin-top:0.3rem;text-align:center">🔍 Click to enlarge</div>
        </div>
        @endif

        @if($user->aadhar_back_image)
        <div>
            <div style="font-size:0.78rem;color:var(--muted);margin-bottom:0.5rem;font-weight:600">Aadhaar Back</div>
            <a href="{{ asset('storage/' . $user->aadhar_back_image) }}" target="_blank">
                <img src="{{ asset('storage/' . $user->aadhar_back_image) }}"
                     style="width:100%;border-radius:8px;border:1px solid var(--border);cursor:zoom-in;transition:transform 0.2s"
                     onmouseover="this.style.transform='scale(1.03)'"
                     onmouseout="this.style.transform='scale(1)'"
                     title="Click to view full size">
            </a>
            <div style="font-size:0.7rem;color:var(--gold);margin-top:0.3rem;text-align:center">🔍 Click to enlarge</div>
        </div>
        @endif

        @if($user->bank_passbook_image)
        <div>
            <div style="font-size:0.78rem;color:var(--muted);margin-bottom:0.5rem;font-weight:600">Bank Passbook/Cheque</div>
            <a href="{{ asset('storage/' . $user->bank_passbook_image) }}" target="_blank">
                <img src="{{ asset('storage/' . $user->bank_passbook_image) }}"
                     style="width:100%;border-radius:8px;border:1px solid var(--border);cursor:zoom-in;transition:transform 0.2s"
                     onmouseover="this.style.transform='scale(1.03)'"
                     onmouseout="this.style.transform='scale(1)'"
                     title="Click to view full size">
            </a>
            <div style="font-size:0.7rem;color:var(--gold);margin-top:0.3rem;text-align:center">🔍 Click to enlarge</div>
        </div>
        @endif

    </div>
</div>
@else
<div class="card" style="margin-bottom:1.5rem">
    <div style="text-align:center;padding:2rem;color:var(--muted)">
        📂 User ne abhi tak koi documents upload nahi kiye.
    </div>
</div>
@endif



{{-- Investment Stats --}}
<div class="stats-grid" style="margin-bottom:1.5rem">
    <div class="stat-card">
        <div class="label">Total Invested</div>
        <div class="value" style="color:var(--gold)">₹{{ number_format($user->investments->sum('principal_amount'), 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Active Plans</div>
        <div class="value">{{ $user->investments->where('status','active')->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Total Profit</div>
        <div class="value" style="color:var(--green)">₹{{ number_format($user->investments->where('status','withdrawn')->sum('net_profit'), 2) }}</div>
    </div>
    <div class="stat-card">
        <div class="label">Commission Earned</div>
        <div class="value" style="color:var(--red)">₹{{ number_format($user->investments->where('status','withdrawn')->sum('commission_amount'), 2) }}</div>
    </div>
</div>

{{-- Investments --}}
<div class="card" style="margin-bottom:1.5rem">
    <div class="card-title">Investment History</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Plan</th><th>Principal</th><th>Profit</th>
                    <th>Commission</th><th>Net</th><th>Invested</th><th>Maturity</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($user->investments as $inv)
                <tr>
                    <td><strong>{{ $inv->plan->name ?? 'N/A' }}</strong></td>
                    <td>₹{{ number_format($inv->principal_amount, 2) }}</td>
                    <td style="color:var(--green)">₹{{ number_format($inv->expected_profit, 2) }}</td>
                    <td style="color:var(--red)">₹{{ number_format($inv->commission_amount, 2) }}</td>
                    <td style="font-weight:600;color:var(--gold)">₹{{ number_format($inv->net_profit, 2) }}</td>
                    <td style="font-size:0.8rem;color:var(--muted)">{{ $inv->invested_at->format('d M Y') }}</td>
                    <td style="font-size:0.8rem;color:var(--muted)">{{ $inv->maturity_date?->format('d M Y') ?? '—' }}</td>
                    <td><span class="badge {{ $inv->status }}">{{ ucfirst($inv->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--muted)">Koi investment nahi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Transactions --}}
<div class="card">
    <div class="card-title">Recent Transactions</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Type</th><th>Amount</th><th>Method</th><th>Payment ID</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
                @forelse($user->transactions->take(15) as $txn)
                <tr>
                    <td><span class="badge {{ $txn->type }}">{{ ucfirst($txn->type) }}</span></td>
                    <td style="font-weight:600;color:{{ in_array($txn->type,['deposit','profit','referral_bonus']) ? 'var(--green)' : 'var(--red)' }}">
                        {{ in_array($txn->type,['deposit','profit','referral_bonus']) ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
                    </td>
                    <td style="font-size:0.82rem">{{ ucfirst($txn->payment_method ?? '—') }}</td>
                    <td style="font-size:0.75rem;color:var(--muted)">{{ $txn->payment_id ?? '—' }}</td>
                    <td><span class="badge {{ $txn->status }}">{{ ucfirst($txn->status) }}</span></td>
                    <td style="font-size:0.8rem;color:var(--muted)">{{ $txn->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--muted)">Koi transaction nahi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection