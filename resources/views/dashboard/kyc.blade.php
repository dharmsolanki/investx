@extends('layouts.app')
@section('title', 'KYC Verification')
@section('page-title', '🪪 KYC Verification')

@section('content')

<div style="max-width:600px">

    @if($user->kyc_status === 'verified')
    <div class="alert success" style="font-size:1rem">
        ✅ Aapka KYC verify ho gaya hai! Ab aap freely invest kar sakte hain.
    </div>
    @elseif($user->kyc_status === 'submitted')
    <div class="alert info">
        ⏳ Aapka KYC review mein hai. 24 ghante mein verify karenge.
    </div>
    @elseif($user->kyc_status === 'rejected')
    <div class="alert error">
        ❌ KYC reject ho gaya. Please correct information ke saath dobara submit karein.
    </div>
    @endif

    <div class="card">
        <div class="card-title">Personal & Bank Details</div>

        <form action="{{ route('kyc.update') }}" method="POST">
            @csrf

            <div style="background:rgba(201,168,76,0.08);border:1px solid var(--border);border-radius:10px;padding:1rem;margin-bottom:1.5rem;font-size:0.82rem;color:var(--muted)">
                ℹ️ Yeh details ek baar submit karne ke baad admin verify karega. Accurate information dein.
            </div>

            <div style="font-size:0.8rem;font-weight:600;color:var(--gold);letter-spacing:1px;text-transform:uppercase;margin-bottom:1rem">Identity Documents</div>

            <div class="grid-2" style="margin-bottom:0">
                <div class="form-group">
                    <label class="form-label">PAN Number *</label>
                    <input class="form-control" type="text" name="pan_number"
                           value="{{ old('pan_number', $user->pan_number) }}"
                           placeholder="ABCDE1234F" maxlength="10" style="text-transform:uppercase"
                           {{ $user->kyc_status === 'verified' ? 'disabled' : '' }} required>
                    @error('pan_number')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Aadhaar Number *</label>
                    <input class="form-control" type="text" name="aadhar_number"
                           value="{{ old('aadhar_number', $user->aadhar_number) }}"
                           placeholder="12 digit number" maxlength="12"
                           {{ $user->kyc_status === 'verified' ? 'disabled' : '' }} required>
                    @error('aadhar_number')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="font-size:0.8rem;font-weight:600;color:var(--gold);letter-spacing:1px;text-transform:uppercase;margin-bottom:1rem;margin-top:0.5rem">Bank Account Details</div>

            <div class="form-group">
                <label class="form-label">Bank Account Number *</label>
                <input class="form-control" type="text" name="bank_account"
                       value="{{ old('bank_account', $user->bank_account) }}"
                       placeholder="Account number"
                       {{ $user->kyc_status === 'verified' ? 'disabled' : '' }} required>
                @error('bank_account')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">IFSC Code *</label>
                    <input class="form-control" type="text" name="bank_ifsc"
                           value="{{ old('bank_ifsc', $user->bank_ifsc) }}"
                           placeholder="SBIN0001234" maxlength="11" style="text-transform:uppercase"
                           {{ $user->kyc_status === 'verified' ? 'disabled' : '' }} required>
                    @error('bank_ifsc')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Bank Name *</label>
                    <input class="form-control" type="text" name="bank_name"
                           value="{{ old('bank_name', $user->bank_name) }}"
                           placeholder="SBI, HDFC, ICICI..."
                           {{ $user->kyc_status === 'verified' ? 'disabled' : '' }} required>
                    @error('bank_name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            @if($user->kyc_status !== 'verified')
            <button type="submit" class="btn btn-gold btn-block" style="margin-top:0.5rem">
                📤 Submit KYC Documents
            </button>
            @endif
        </form>
    </div>

</div>

@endsection
