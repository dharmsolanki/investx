<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — DailyWealth</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root { --gold:#C9A84C; --dark:#0A0C10; --dark3:#181C24; --dark4:#1E2330; --text:#E8EAF0; --muted:#7A8099; --red:#EF4444; --green:#22C55E; --border:rgba(201,168,76,0.18); }
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;}
body::before{content:'';position:fixed;inset:0;background:radial-gradient(ellipse 60% 50% at 50% 30%,rgba(201,168,76,0.07) 0%,transparent 70%);pointer-events:none;}
.auth-box{background:var(--dark3);border:1px solid var(--border);border-radius:20px;padding:2.5rem;width:100%;max-width:480px;}
.logo{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;color:var(--gold);text-align:center;margin-bottom:0.3rem;}
.logo span{color:var(--text);}
.subtitle{text-align:center;font-size:0.85rem;color:var(--muted);margin-bottom:2rem;}
.form-group{margin-bottom:1.1rem;}
.form-label{display:block;font-size:0.82rem;color:var(--muted);margin-bottom:0.4rem;font-weight:500;}
.form-control{width:100%;background:var(--dark4);border:1px solid var(--border);border-radius:8px;padding:0.75rem 1rem;color:var(--text);font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none;transition:border-color 0.2s;}
.form-control:focus{border-color:var(--gold);}
.form-error{font-size:0.75rem;color:var(--red);margin-top:4px;}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
.btn-gold{width:100%;padding:0.85rem;background:var(--gold);color:#0A0C10;border:none;border-radius:10px;font-size:1rem;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.2s;margin-top:0.5rem;}
.btn-gold:hover{background:#E8C97A;}
.link{color:var(--gold);text-decoration:none;font-weight:500;}
.link:hover{text-decoration:underline;}
.divider{text-align:center;font-size:0.82rem;color:var(--muted);margin-top:1.2rem;}
.alert.error{background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:var(--red);padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:0.85rem;}

/* Risk note box */
.risk-note{background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.2);border-radius:10px;padding:0.9rem 1rem;margin-bottom:1.2rem;font-size:0.78rem;color:#F87171;line-height:1.6;}
.risk-note strong{color:var(--red);}

/* Checkbox styling */
.checkbox-group{display:flex;align-items:flex-start;gap:0.6rem;cursor:pointer;}
.checkbox-group input[type="checkbox"]{margin-top:3px;width:15px;height:15px;accent-color:var(--gold);flex-shrink:0;cursor:pointer;}
.checkbox-group span{font-size:0.82rem;color:var(--muted);line-height:1.5;}
</style>
</head>
<body>
<div class="auth-box">
    <div class="logo">Daily<span>Wealth</span></div>
    <div class="subtitle">Free account banayein aur trading community join karein</div>

    @if($errors->any())
        <div class="alert error">❌ {{ $errors->first() }}</div>
    @endif

    {{-- Risk Warning --}}
    <div class="risk-note">
        ⚠️ <strong>Risk Warning:</strong> Forex trading mein substantial risk hota hai.
        Aap apni poori capital kho sakte hain. Returns guaranteed nahi hain.
        Sirf woh funds use karein jo aap afford kar sakein.
    </div>

    <form action="{{ url('/register') }}" method="POST">
        @csrf
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Aapka naam" required>
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number *</label>
                <input class="form-control" type="tel" name="phone" value="{{ old('phone') }}" placeholder="10 digits" maxlength="10" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Email Address *</label>
            <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="aap@email.com" required>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Password *</label>
                <input class="form-control" type="password" name="password" placeholder="Min 8 characters" required>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password *</label>
                <input class="form-control" type="password" name="password_confirmation" placeholder="Dobara enter karein" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Referral Code (optional)</label>
            <input class="form-control" type="text" name="referral_code" value="{{ old('referral_code', request('ref')) }}" placeholder="Friend ka referral code" maxlength="8">
        </div>

        {{-- T&C Checkbox --}}
        <div class="form-group" style="margin-top:0.8rem">
            <label class="checkbox-group">
                <input type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                <span>
                    Main <a href="{{ route('terms') }}" class="link" target="_blank">Terms & Conditions</a> padh chuka/chuki hun aur agree karta/karti hun.
                    Mujhe pata hai ki <strong style="color:var(--text)">forex trading mein risk hai aur returns guaranteed nahi hain.</strong>
                </span>
            </label>
            @error('terms')
                <div class="form-error">⚠️ {{ $message }}</div>
            @enderror
        </div>

        {{-- Risk Acknowledge Checkbox --}}
        <div class="form-group">
            <label class="checkbox-group">
                <input type="checkbox" name="risk_acknowledge" value="1" {{ old('risk_acknowledge') ? 'checked' : '' }} required>
                <span>
                    Main samajhta/samajhti hun ki main apni poori contributed amount kho sakta/sakti hun.
                    Yeh participation meri <strong style="color:var(--text)">apni marzi se hai.</strong>
                </span>
            </label>
            @error('risk_acknowledge')
                <div class="form-error">⚠️ {{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-gold">Community Join Karein →</button>
    </form>

    <div class="divider">Already account hai? <a href="{{ route('login') }}" class="link">Login karein</a></div>
</div>
</body>
</html>