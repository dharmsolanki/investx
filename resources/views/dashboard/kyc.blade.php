@extends('layouts.app')
@section('title', 'KYC Verification')
@section('page-title', '🪪 KYC Verification')

@section('content')

<div style="max-width:650px">

    @if($user->kyc_status === 'verified')
    <div class="alert success" style="font-size:1rem">
        ✅ Aapka KYC verify ho gaya hai! Ab aap freely invest kar sakte hain.
    </div>
    @elseif($user->kyc_status === 'submitted')
    <div class="alert info">
        ⏳ Aapka KYC review mein hai. Admin 24 ghante mein verify karega.
    </div>
    @elseif($user->kyc_status === 'rejected')
    <div class="alert error">
        ❌ KYC reject ho gaya.
        @if($user->kyc_rejection_reason)
            <br><strong>Reason:</strong> {{ $user->kyc_rejection_reason }}
        @endif
        <br>Please correct karke dobara submit karein.
    </div>
    @endif

    @if($user->kyc_status !== 'verified' && $user->kyc_status !== 'submitted')
    <div class="card">
        <div class="card-title">Personal & Document Details</div>

        <div style="background:rgba(201,168,76,0.08);border:1px solid var(--border);border-radius:10px;padding:1rem;margin-bottom:1.5rem;font-size:0.82rem;color:var(--muted)">
            ℹ️ Saare documents clearly visible hone chahiye. Max size: 2MB per image.
        </div>

        <form action="{{ url('/kyc') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Identity Details --}}
            <div style="font-size:0.8rem;font-weight:600;color:var(--gold);letter-spacing:1px;text-transform:uppercase;margin-bottom:1rem">
                📋 Identity Documents
            </div>

            <div class="grid-2">
                {{-- PAN Number --}}
                <div class="form-group">
                    <label class="form-label">PAN Number *</label>
                    <input class="form-control {{ $errors->has('pan_number') ? 'error' : '' }}"
                           type="text" name="pan_number" id="pan_number"
                           value="{{ old('pan_number', $user->pan_number) }}"
                           placeholder="ABCDE1234F" maxlength="10"
                           oninput="validatePan(this)"
                           style="text-transform:uppercase" required>
                    @error('pan_number')<div class="form-error">{{ $message }}</div>@enderror
                    <div id="pan_error"   style="display:none;font-size:0.75rem;color:var(--red);margin-top:4px"></div>
                    <div id="pan_success" style="display:none;font-size:0.75rem;color:var(--green);margin-top:4px"></div>
                </div>

                {{-- Aadhaar Number --}}
                <div class="form-group">
                    <label class="form-label">Aadhaar Number *</label>
                    <input class="form-control {{ $errors->has('aadhar_number') ? 'error' : '' }}"
                           type="text" name="aadhar_number" id="aadhar_number"
                           value="{{ old('aadhar_number', $user->aadhar_number) }}"
                           placeholder="12 digit number" maxlength="12"
                           oninput="validateAadhar(this)"
                           required>
                    @error('aadhar_number')<div class="form-error">{{ $message }}</div>@enderror
                    <div id="aadhar_error"   style="display:none;font-size:0.75rem;color:var(--red);margin-top:4px"></div>
                    <div id="aadhar_success" style="display:none;font-size:0.75rem;color:var(--green);margin-top:4px"></div>
                </div>
            </div>

            {{-- PAN Card Upload --}}
            <div class="form-group">
                <label class="form-label">PAN Card Photo * <span style="color:var(--muted)">(JPG/PNG, max 2MB)</span></label>
                <input class="form-control {{ $errors->has('pan_image') ? 'error' : '' }}"
                       type="file" name="pan_image" accept="image/*" required>
                @error('pan_image')<div class="form-error">{{ $message }}</div>@enderror
                @if($user->pan_image && $user->kyc_status !== 'rejected')
                    <div style="margin-top:0.5rem;font-size:0.75rem;color:var(--green)">
                        ✅ Pehle upload ki hui image maujood hai
                    </div>
                @endif
            </div>

            {{-- Aadhaar Upload --}}
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Aadhaar Front * <span style="color:var(--muted)">(JPG/PNG)</span></label>
                    <input class="form-control {{ $errors->has('aadhar_front_image') ? 'error' : '' }}"
                           type="file" name="aadhar_front_image" accept="image/*" required>
                    @error('aadhar_front_image')<div class="form-error">{{ $message }}</div>@enderror
                    @if($user->aadhar_front_image && $user->kyc_status !== 'rejected')
                        <div style="margin-top:0.5rem;font-size:0.75rem;color:var(--green)">
                            ✅ Pehle upload ki hui image maujood hai
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="form-label">Aadhaar Back * <span style="color:var(--muted)">(JPG/PNG)</span></label>
                    <input class="form-control {{ $errors->has('aadhar_back_image') ? 'error' : '' }}"
                           type="file" name="aadhar_back_image" accept="image/*" required>
                    @error('aadhar_back_image')<div class="form-error">{{ $message }}</div>@enderror
                    @if($user->aadhar_back_image && $user->kyc_status !== 'rejected')
                        <div style="margin-top:0.5rem;font-size:0.75rem;color:var(--green)">
                            ✅ Pehle upload ki hui image maujood hai
                        </div>
                    @endif
                </div>
            </div>

            {{-- Bank Details --}}
            <div style="font-size:0.8rem;font-weight:600;color:var(--gold);letter-spacing:1px;text-transform:uppercase;margin:1.2rem 0 1rem">
                🏦 Bank Account Details
            </div>

            <div class="form-group">
                <label class="form-label">Bank Account Number *</label>
                <input class="form-control {{ $errors->has('bank_account') ? 'error' : '' }}"
                       type="text" name="bank_account" id="bank_account"
                       value="{{ old('bank_account', $user->bank_account) }}"
                       placeholder="9-18 digit account number"
                       maxlength="18" oninput="validateAccount(this)"
                       required>
                @error('bank_account')<div class="form-error">{{ $message }}</div>@enderror
                <div id="account_error"   style="display:none;font-size:0.75rem;color:var(--red);margin-top:4px"></div>
                <div id="account_success" style="display:none;font-size:0.75rem;color:var(--green);margin-top:4px"></div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">IFSC Code *</label>
                    <input class="form-control {{ $errors->has('bank_ifsc') ? 'error' : '' }}"
                           type="text" name="bank_ifsc" id="bank_ifsc"
                           value="{{ old('bank_ifsc', $user->bank_ifsc) }}"
                           placeholder="SBIN0001234" maxlength="11"
                           style="text-transform:uppercase" required>
                    @error('bank_ifsc')<div class="form-error">{{ $message }}</div>@enderror
                    <div id="ifsc_loading" style="display:none;margin-top:0.5rem;font-size:0.75rem;color:var(--muted)">
                        ⏳ Bank details fetch ho rahi hain...
                    </div>
                    <div id="ifsc_result" style="display:none;margin-top:0.5rem;padding:0.6rem 0.8rem;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);border-radius:8px;font-size:0.78rem;color:var(--green)">
                    </div>
                    <div id="ifsc_error" style="display:none;margin-top:0.5rem;font-size:0.75rem;color:var(--red)">
                        ❌ Invalid IFSC code — dobara check karein
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Bank Name *</label>
                    <input class="form-control {{ $errors->has('bank_name') ? 'error' : '' }}"
                           type="text" name="bank_name" id="bank_name"
                           value="{{ old('bank_name', $user->bank_name) }}"
                           placeholder="Auto fill hoga IFSC se..." required>
                    @error('bank_name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Bank Passbook Upload --}}
            <div class="form-group">
                <label class="form-label">Bank Passbook / Cancelled Cheque * <span style="color:var(--muted)">(JPG/PNG)</span></label>
                <input class="form-control {{ $errors->has('bank_passbook_image') ? 'error' : '' }}"
                       type="file" name="bank_passbook_image" accept="image/*" required>
                @error('bank_passbook_image')<div class="form-error">{{ $message }}</div>@enderror
                @if($user->bank_passbook_image && $user->kyc_status !== 'rejected')
                    <div style="margin-top:0.5rem;font-size:0.75rem;color:var(--green)">
                        ✅ Pehle upload ki hui image maujood hai
                    </div>
                @endif
            </div>

            <button type="submit" class="btn btn-gold btn-block" style="margin-top:0.5rem;padding:0.9rem;font-size:1rem">
                📤 KYC Documents Submit Karein
            </button>

            <p style="text-align:center;font-size:0.72rem;color:var(--muted);margin-top:0.6rem">
                Aapki documents sirf verification ke liye use ki jaayengi. 100% secure.
            </p>
        </form>
    </div>

@elseif($user->kyc_status === 'submitted')
<div class="card" style="margin-top:1rem">
    <div class="card-title">📁 Submitted Documents</div>
    <div class="grid-2">

        @if($user->pan_image)
        <div>
            <div style="font-size:0.82rem;color:var(--muted);margin-bottom:0.5rem;font-weight:600">PAN Card</div>
            <a href="{{ asset('storage/' . $user->pan_image) }}" target="_blank">
                <img src="{{ asset('storage/' . $user->pan_image) }}"
                     style="width:100%;border-radius:8px;border:1px solid var(--border);cursor:zoom-in">
            </a>
            <div style="font-size:0.7rem;color:var(--gold);margin-top:0.3rem;text-align:center">🔍 Click to enlarge</div>
        </div>
        @endif

        @if($user->aadhar_front_image)
        <div>
            <div style="font-size:0.82rem;color:var(--muted);margin-bottom:0.5rem;font-weight:600">Aadhaar Front</div>
            <a href="{{ asset('storage/' . $user->aadhar_front_image) }}" target="_blank">
                <img src="{{ asset('storage/' . $user->aadhar_front_image) }}"
                     style="width:100%;border-radius:8px;border:1px solid var(--border);cursor:zoom-in">
            </a>
            <div style="font-size:0.7rem;color:var(--gold);margin-top:0.3rem;text-align:center">🔍 Click to enlarge</div>
        </div>
        @endif

        @if($user->aadhar_back_image)
        <div>
            <div style="font-size:0.82rem;color:var(--muted);margin-bottom:0.5rem;font-weight:600">Aadhaar Back</div>
            <a href="{{ asset('storage/' . $user->aadhar_back_image) }}" target="_blank">
                <img src="{{ asset('storage/' . $user->aadhar_back_image) }}"
                     style="width:100%;border-radius:8px;border:1px solid var(--border);cursor:zoom-in">
            </a>
            <div style="font-size:0.7rem;color:var(--gold);margin-top:0.3rem;text-align:center">🔍 Click to enlarge</div>
        </div>
        @endif

        @if($user->bank_passbook_image)
        <div>
            <div style="font-size:0.82rem;color:var(--muted);margin-bottom:0.5rem;font-weight:600">Bank Passbook / Cheque</div>
            <a href="{{ asset('storage/' . $user->bank_passbook_image) }}" target="_blank">
                <img src="{{ asset('storage/' . $user->bank_passbook_image) }}"
                     style="width:100%;border-radius:8px;border:1px solid var(--border);cursor:zoom-in">
            </a>
            <div style="font-size:0.7rem;color:var(--gold);margin-top:0.3rem;text-align:center">🔍 Click to enlarge</div>
        </div>
        @endif

    </div>
</div>
@endif

</div>

@endsection

@push('scripts')
<script>

    // ─── PAN Validation ────────────────────────────────────────────────────────
    function validatePan(input) {
        const val = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        input.value = val;

        const errDiv  = document.getElementById('pan_error');
        const succDiv = document.getElementById('pan_success');
        errDiv.style.display  = 'none';
        succDiv.style.display = 'none';

        if (val.length === 0) return;

        // PAN format: ABCDE1234F — 5 letters, 4 digits, 1 letter
        const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;

        if (val.length < 10) {
            errDiv.style.display = 'block';
            errDiv.textContent   = '❌ PAN number 10 characters ka hona chahiye. (e.g. ABCDE1234F)';
            input.classList.add('error');
            return;
        }

        if (!panRegex.test(val)) {
            errDiv.style.display = 'block';
            errDiv.textContent   = '❌ Invalid PAN format. Sahi format: ABCDE1234F (5 letters + 4 digits + 1 letter)';
            input.classList.add('error');
            return;
        }

        // Valid PAN — 4th character (index 3) person type batata hai
        const typeMap = {
            'P': 'Individual',
            'C': 'Company',
            'H': 'HUF',
            'F': 'Firm',
            'A': 'AOP',
            'T': 'Trust',
            'B': 'BOI',
            'L': 'Local Authority',
            'J': 'Artificial Juridical Person',
            'G': 'Government',
        };
        const type = typeMap[val[3]] || 'Unknown';

        input.classList.remove('error');
        succDiv.style.display = 'block';
        succDiv.textContent   = `✅ Valid PAN — Type: ${type}`;
    }

    // ─── Aadhaar Validation ────────────────────────────────────────────────────
    function validateAadhar(input) {
        const val = input.value.replace(/\D/g, ''); // sirf numbers
        input.value = val;

        const errDiv  = document.getElementById('aadhar_error');
        const succDiv = document.getElementById('aadhar_success');
        errDiv.style.display  = 'none';
        succDiv.style.display = 'none';

        if (val.length === 0) return;

        if (val.length < 12) {
            errDiv.style.display = 'block';
            errDiv.textContent   = `❌ Aadhaar 12 digits ka hona chahiye. (${val.length}/12)`;
            input.classList.add('error');
            return;
        }

        // Saare same digits nahi hone chahiye
        if (/^(.)\1+$/.test(val)) {
            errDiv.style.display = 'block';
            errDiv.textContent   = '❌ Invalid Aadhaar number.';
            input.classList.add('error');
            return;
        }

        // Pehla digit 0 ya 1 nahi ho sakta (UIDAI rule)
        if (val[0] === '0' || val[0] === '1') {
            errDiv.style.display = 'block';
            errDiv.textContent   = '❌ Invalid Aadhaar — pehla digit 0 ya 1 nahi ho sakta.';
            input.classList.add('error');
            return;
        }

        input.classList.remove('error');
        succDiv.style.display = 'block';
        succDiv.textContent   = '✅ Aadhaar number valid hai.';
    }

    // ─── Bank Account Validation ───────────────────────────────────────────────
    function validateAccount(input) {
        const val   = input.value.replace(/\D/g, '');
        input.value = val;

        const errDiv  = document.getElementById('account_error');
        const succDiv = document.getElementById('account_success');
        errDiv.style.display  = 'none';
        succDiv.style.display = 'none';

        if (val.length === 0) return;

        if (val.length < 9) {
            errDiv.style.display = 'block';
            errDiv.textContent   = '❌ Account number kam se kam 9 digits ka hona chahiye.';
            input.classList.add('error');
            return;
        }

        if (/^(.)\1+$/.test(val)) {
            errDiv.style.display = 'block';
            errDiv.textContent   = '❌ Invalid account number — saare digits same nahi ho sakte.';
            input.classList.add('error');
            return;
        }

        input.classList.remove('error');
        succDiv.style.display = 'block';
        succDiv.textContent   = `✅ Account number valid hai (${val.length} digits).`;
    }

    // ─── IFSC Auto Fetch ───────────────────────────────────────────────────────
    let ifscTimer = null;

    document.getElementById('bank_ifsc').addEventListener('input', function () {
        const ifsc = this.value.toUpperCase().trim();
        this.value = ifsc;

        document.getElementById('ifsc_result').style.display  = 'none';
        document.getElementById('ifsc_error').style.display   = 'none';
        document.getElementById('ifsc_loading').style.display = 'none';

        if (ifsc.length !== 11) return;

        clearTimeout(ifscTimer);
        ifscTimer = setTimeout(() => {
            document.getElementById('ifsc_loading').style.display = 'block';

            fetch(`https://ifsc.razorpay.com/${ifsc}`)
                .then(res => {
                    if (!res.ok) throw new Error('Invalid IFSC');
                    return res.json();
                })
                .then(data => {
                    document.getElementById('ifsc_loading').style.display = 'none';
                    document.getElementById('bank_name').value = data.BANK;
                    document.getElementById('ifsc_result').style.display = 'block';
                    document.getElementById('ifsc_result').innerHTML =
                        `✅ <strong>${data.BANK}</strong> — ${data.BRANCH}, ${data.CITY}`;
                })
                .catch(() => {
                    document.getElementById('ifsc_loading').style.display = 'none';
                    document.getElementById('ifsc_error').style.display   = 'block';
                    document.getElementById('bank_name').value = '';
                });
        }, 500);
    });

</script>
@endpush