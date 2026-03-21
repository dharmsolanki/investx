@extends('layouts.admin')
@section('title', 'Manage Withdrawals')
@section('page-title', '💸 Manage Withdrawals')

@section('content')

<div class="card">
    {{-- Status Filter --}}
    <div style="display:flex;gap:0.6rem;margin-bottom:1.5rem;flex-wrap:wrap">
        @foreach(['','pending','processing','completed','rejected'] as $s)
        <a href="{{ route('admin.withdrawals.index', $s ? ['status'=>$s] : []) }}"
           class="btn btn-sm {{ request('status') == $s ? 'btn-gold' : 'btn-outline' }}">
            {{ $s ? ucfirst($s) : 'All' }}
        </a>
        @endforeach
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Type / Plan</th>
                    <th>Principal</th>
                    <th>Net Profit</th>
                    <th>Total</th>
                    <th>Bank</th>
                    <th>Status</th>
                    <th>Requested</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($withdrawals as $w)
                <tr>
                    <td style="color:var(--muted)">{{ $w->id }}</td>
                    <td>
                        <div style="font-weight:600">{{ $w->user->name }}</div>
                        <div style="font-size:0.72rem;color:var(--muted)">{{ $w->user->phone }}</div>
                    </td>
                    <td style="font-size:0.82rem">
                        @if($w->isWallet())
                            <span class="badge" style="background:var(--gold);color:#0A0C10">👛 Wallet</span>
                        @else
                            {{ $w->investment?->plan->name ?? '—' }}
                        @endif
                    </td>
                    <td>₹{{ number_format($w->principal_amount, 2) }}</td>
                    <td style="color:var(--green)">
                        {{ $w->isWallet() ? '—' : '₹' . number_format($w->net_profit, 2) }}
                    </td>
                    <td style="font-weight:700;color:var(--gold)">₹{{ number_format($w->total_amount, 2) }}</td>
                    <td style="font-size:0.78rem">
                        <div>{{ $w->bank_name }}</div>
                        <div style="color:var(--muted)">{{ $w->bank_account }}</div>
                        <div style="color:var(--muted)">{{ $w->bank_ifsc }}</div>
                    </td>
                    <td>
                        <span class="badge {{ $w->status }}">{{ ucfirst($w->status) }}</span>
                        @if($w->utr_number)
                        <div style="font-size:0.7rem;color:var(--green)">UTR: {{ $w->utr_number }}</div>
                        @endif
                        @if($w->rejection_reason)
                        <div style="font-size:0.7rem;color:var(--red)">{{ $w->rejection_reason }}</div>
                        @endif
                    </td>
                    <td style="font-size:0.78rem;color:var(--muted)">{{ $w->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        @if($w->status === 'pending')
                        <div style="display:flex;flex-direction:column;gap:0.4rem">
                            {{-- Approve --}}
                            <form action="{{ route('admin.withdrawals.approve', $w) }}" method="POST"
                                  onsubmit="return confirmApprove(this)">
                                @csrf
                                <input class="form-control" name="utr_number" placeholder="UTR Number" required style="margin-bottom:0.4rem;font-size:0.8rem;padding:0.4rem 0.6rem">
                                <button class="btn btn-gold btn-sm btn-block" type="submit">✅ Approve</button>
                            </form>
                            {{-- Reject --}}
                            <form action="{{ route('admin.withdrawals.reject', $w) }}" method="POST"
                                  onsubmit="return confirmReject(this)">
                                @csrf
                                <input class="form-control" name="reason" placeholder="Rejection reason" required style="margin-bottom:0.4rem;font-size:0.8rem;padding:0.4rem 0.6rem">
                                <button class="btn btn-danger btn-sm btn-block" type="submit">❌ Reject</button>
                            </form>
                        </div>
                        @else
                        <span style="color:var(--muted);font-size:0.8rem">{{ $w->processed_at?->format('d M Y') ?? '—' }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $withdrawals->links() }}</div>
</div>

@endsection

@push('scripts')
<script>
function confirmApprove(form) {
    const utr = form.querySelector('[name=utr_number]').value;
    return confirm(`UTR: ${utr}\nIs withdrawal ko approve karna chahte ho?`);
}
function confirmReject(form) {
    return confirm('Is withdrawal ko reject karna chahte ho?');
}
</script>
@endpush