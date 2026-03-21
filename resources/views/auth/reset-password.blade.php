<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password — InvestX</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root { --gold:#C9A84C; --dark:#0A0C10; --dark2:#111318; --dark3:#181C24; --dark4:#1E2330; --text:#E8EAF0; --muted:#7A8099; --red:#EF4444; --border:rgba(201,168,76,0.18); }
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);min-height:100vh;display:flex;align-items:center;justify-content:center;}
body::before{content:'';position:fixed;inset:0;background:radial-gradient(ellipse 60% 50% at 50% 30%,rgba(201,168,76,0.08) 0%,transparent 70%);pointer-events:none;}
.auth-box{background:var(--dark3);border:1px solid var(--border);border-radius:20px;padding:2.5rem;width:100%;max-width:420px;}
.logo{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;color:var(--gold);text-align:center;margin-bottom:0.3rem;}
.logo span{color:var(--text);}
.subtitle{text-align:center;font-size:0.85rem;color:var(--muted);margin-bottom:2rem;}
.form-group{margin-bottom:1.2rem;}
.form-label{display:block;font-size:0.82rem;color:var(--muted);margin-bottom:0.4rem;font-weight:500;}
.form-control{width:100%;background:var(--dark4);border:1px solid var(--border);border-radius:8px;padding:0.75rem 1rem;color:var(--text);font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none;transition:border-color 0.2s;}
.form-control:focus{border-color:var(--gold);}
.btn-gold{width:100%;padding:0.85rem;background:var(--gold);color:#0A0C10;border:none;border-radius:10px;font-size:1rem;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.2s;margin-top:0.5rem;}
.btn-gold:hover{background:#E8C97A;transform:translateY(-1px);}
.divider{text-align:center;font-size:0.82rem;color:var(--muted);margin:1.2rem 0;}
.link{color:var(--gold);text-decoration:none;font-weight:500;}
.alert{padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:0.85rem;}
.alert.error{background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:var(--red);}
.alert.success{background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);color:#22C55E;}
</style>
</head>
<body>
<div class="auth-box">
    <div class="logo">Invest<span>X</span></div>
    <div class="subtitle">Naya password set karein</div>

    @if(session('error'))
        <div class="alert error">❌ {{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert error">❌ {{ $errors->first() }}</div>
    @endif

    <form action="{{ route('password.reset') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="form-group">
            <label class="form-label">Naya Password</label>
            <input class="form-control" type="password" name="password"
                   placeholder="Min 8 characters" required>
        </div>
        <div class="form-group">
            <label class="form-label">Password Confirm Karein</label>
            <input class="form-control" type="password" name="password_confirmation"
                   placeholder="Dobara likhein" required>
        </div>
        <button type="submit" class="btn-gold">Password Reset Karein →</button>
    </form>

    <div class="divider"><a href="{{ route('login') }}" class="link">← Wapas Login</a></div>
</div>
</body>
</html>