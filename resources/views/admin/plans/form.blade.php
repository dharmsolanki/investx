@extends('layouts.admin')
@section('title', isset($plan->id) ? 'Edit Plan' : 'Create Plan')
@section('page-title', isset($plan->id) ? '✏️ Edit Plan' : '➕ New Plan')

@section('content')

<div style="max-width:600px">
<div class="card">
    <form action="{{ isset($plan->id) ? route('admin.plans.update', $plan) : route('admin.plans.store') }}" method="POST">
        @csrf
        @if(isset($plan->id)) @method('PUT') @endif

        <div class="form-group">
            <label class="form-label">Plan Name</label>
            <input class="form-control" type="text" name="name"
                   value="{{ old('name', $plan->name) }}" placeholder="e.g. Plan 1" required>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <input class="form-control" type="text" name="description"
                   value="{{ old('description', $plan->description) }}"
                   placeholder="e.g. 💵 Invest ₹15,000 → Earn ₹750 daily">
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Min Amount (₹)</label>
                <input class="form-control" type="number" name="min_amount"
                       value="{{ old('min_amount', $plan->min_amount) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Max Amount (₹) <span style="color:var(--muted)">(optional)</span></label>
                <input class="form-control" type="number" name="max_amount"
                       value="{{ old('max_amount', $plan->max_amount) }}">
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Duration (Months)</label>
                <input class="form-control" type="number" name="duration_months"
                       value="{{ old('duration_months', $plan->duration_months) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Platform Fee (%)</label>
                <input class="form-control" type="number" step="0.01" name="commission_percent"
                       value="{{ old('commission_percent', $plan->commission_percent) }}" required>
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">ROI % (Annual)</label>
                <input class="form-control" type="number" step="0.01" name="roi_percent"
                       value="{{ old('roi_percent', $plan->roi_percent) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input class="form-control" type="number" name="sort_order"
                       value="{{ old('sort_order', $plan->sort_order ?? 0) }}">
            </div>
        </div>

        @if(isset($plan->id))
        <div class="form-group">
            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer">
                <input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}>
                <span class="form-label" style="margin:0">Active</span>
            </label>
        </div>
        @endif

        @if($errors->any())
        <div class="alert error">❌ {{ $errors->first() }}</div>
        @endif

        <div style="display:flex;gap:1rem">
            <button type="submit" class="btn btn-gold">
                {{ isset($plan->id) ? 'Update Plan' : 'Create Plan' }}
            </button>
            <a href="{{ route('admin.plans.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
</div>

@endsection