@extends('layouts.app')
@section('title', 'Trading Plans')
@section('page-title', '📈 Trading Plans')

@section('content')

<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem">

@foreach($plans as $plan)
@php $isFeatured = $loop->iteration == 3; @endphp

<div style="
    background:var(--dark3);
    border:2px solid {{ $isFeatured ? 'var(--gold)' : 'var(--border)' }};
    border-radius:20px;
    padding:2rem;
    text-align:center;
    position:relative;
    {{ $isFeatured ? 'box-shadow: 0 0 24px rgba(201,168,76,0.15);' : '' }}
">

    @if($isFeatured)
    <div style="position:absolute;top:-13px;left:50%;transform:translateX(-50%);background:var(--gold);color:#0A0C10;font-size:0.68rem;font-weight:800;padding:4px 16px;border-radius:50px;letter-spacing:1.5px;white-space:nowrap">
        ⭐ MOST POPULAR
    </div>
    @endif

    <div style="font-size:0.7rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:{{ $isFeatured ? 'var(--gold)' : 'var(--muted)' }};margin-bottom:1.2rem">
        {{ $plan->name }}
    </div>

    <div style="margin-bottom:0.6rem;font-size:0.95rem;color:var(--muted)">
        💵 Invest <strong style="color:var(--gold);font-size:1.1rem">₹{{ number_format($plan->min_amount, 0) }}</strong>
    </div>
    <div style="font-size:2rem;font-weight:900;color:#22C55E;margin-bottom:0.4rem">
        {{ $plan->displayDailyEarningFormatted() }}
    </div>
    <div style="font-size:0.78rem;color:var(--muted);margin-bottom:1.8rem;letter-spacing:1px;text-transform:uppercase">
        Daily Earning
    </div>

    <a href="{{ route('investments.form', $plan) }}"
       class="btn {{ $isFeatured ? 'btn-gold' : 'btn-outline' }} btn-block"
       style="font-weight:700">
        Invest Karein →
    </a>

</div>
@endforeach

</div>

@endsection