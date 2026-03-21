@extends('layouts.admin')
@section('title', 'Manage Reviews')
@section('page-title', '⭐ Manage Reviews')

@section('content')

<div class="card">
    {{-- Filter --}}
    <div style="display:flex;gap:0.6rem;margin-bottom:1.5rem;flex-wrap:wrap">
        @foreach([''=>'All', 'pending'=>'Pending', 'approved'=>'Approved', 'rejected'=>'Rejected'] as $val => $label)
        <a href="{{ route('admin.reviews.index', $val ? ['status'=>$val] : []) }}"
           class="btn btn-sm {{ request('status') == $val ? 'btn-gold' : 'btn-outline' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $r)
                <tr>
                    <td>
                        <div style="font-weight:600">{{ $r->user->name }}</div>
                        <div style="font-size:0.72rem;color:var(--muted)">{{ $r->user->email }}</div>
                    </td>
                    <td>
                        <span style="color:var(--gold);font-size:1rem">
                            {{ str_repeat('★', $r->rating) }}{{ str_repeat('☆', 5 - $r->rating) }}
                        </span>
                    </td>
                    <td style="max-width:300px;font-size:0.85rem">{{ $r->comment }}</td>
                    <td><span class="badge {{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                    <td style="font-size:0.78rem;color:var(--muted)">{{ $r->created_at->format('d M Y') }}</td>
                    <td>
                        @if($r->status !== 'approved')
                        <form action="{{ route('admin.reviews.approve', $r) }}" method="POST" style="display:inline">
                            @csrf
                            <button class="btn btn-gold btn-sm">✅ Approve</button>
                        </form>
                        @endif
                        @if($r->status !== 'rejected')
                        <form action="{{ route('admin.reviews.reject', $r) }}" method="POST" style="display:inline;margin-left:0.4rem">
                            @csrf
                            <button class="btn btn-danger btn-sm">❌ Reject</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:2rem;color:var(--muted)">Koi review nahi abhi tak.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $reviews->links() }}</div>
</div>

@endsection