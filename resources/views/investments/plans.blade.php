@extends('layouts.app')
@section('title', 'Trading Plans')
@section('page-title', '📈 Trading Plans')

@section('content')

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem">

@foreach($plans as $plan)
<div style="background:var(--dark3);border:1px solid {{ $loop->iteration == 2 ? 'var(--gold)' : 'var(--border)' }};border-radius:20px;padding:2rem;position:relative;{{ $loop->iteration == 2 ? 'transform:scale(1.02)' : '' }}">

    @if($loop->iteration == 2)
    <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:var(--gold);color:#0A0C10;font-size:0.7rem;font-weight:700;padding:3px 14px;border-radius:50px;letter-spacing:1px;white-space:nowrap">⭐ MOST POPULAR</div>
    @endif

    <div style="font-size:0.75rem;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:var(--muted);margin-bottom:0.6rem">{{ $plan->name }}</div>

    <div style="font-family:'Playfair Display',serif;font-size:3rem;font-weight:900;color:var(--gold);line-height:1">{{ $plan->roi_percent }}%</div>
    <div style="font-size:0.82rem;color:var(--muted);margin-bottom:1.5rem">Expected Annual Returns</div>

    <div style="background:var(--dark4);border-radius:10px;padding:1rem;margin-bottom:1.5rem">
        <div style="display:flex;justify-content:space-between;font-size:0.82rem;padding:0.3rem 0">
            <span style="color:var(--muted)">Lock-in Period</span>
            <strong>{{ $plan->duration_months }} Mahine</strong>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:0.82rem;padding:0.3rem 0">
            <span style="color:var(--muted)">Min Contribution</span>
            <strong style="color:var(--gold)">₹{{ number_format($plan->min_amount) }}</strong>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:0.82rem;padding:0.3rem 0">
            <span style="color:var(--muted)">Management Fee</span>
            <strong style="color:var(--red)">{{ $plan->commission_percent }}%</strong>
        </div>
        @if($plan->max_amount)
        <div style="display:flex;justify-content:space-between;font-size:0.82rem;padding:0.3rem 0">
            <span style="color:var(--muted)">Max Contribution</span>
            <strong>₹{{ number_format($plan->max_amount) }}</strong>
        </div>
        @endif
    </div>

    @if($plan->description)
    <p style="font-size:0.82rem;color:var(--muted);line-height:1.6;margin-bottom:1.5rem">{{ $plan->description }}</p>
    @endif

    <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);border-radius:8px;padding:0.8rem;margin-bottom:1.5rem;font-size:0.8rem">
        <div style="font-weight:600;color:#22C55E;margin-bottom:0.3rem">₹{{ number_format($plan->min_amount) }} contribute karoge to:</div>
        @php $calc = $plan->calculateProfit($plan->min_amount); @endphp
        <div style="color:var(--muted)">Net Returns: <strong style="color:var(--text)">₹{{ number_format($calc['net_profit']) }}</strong> + ₹{{ number_format($calc['principal']) }} contribution wapas</div>
    </div>

    {{-- Risk note --}}
    <div style="font-size:0.72rem;color:var(--muted);margin-bottom:1rem;line-height:1.5;opacity:0.7">
        ⚠️ Returns market performance par based hain. Guaranteed nahi hain.
    </div>

    <a href="{{ route('investments.form', $plan) }}"
       class="btn {{ $loop->iteration == 2 ? 'btn-gold' : 'btn-outline' }} btn-block">
        Participate Karein →
    </a>
</div>
@endforeach

</div>

@endsection