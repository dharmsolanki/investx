<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Refund & Cancellation Policy — InvestX</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#C9A84C;--dark:#0A0C10;--dark3:#181C24;--text:#E8EAF0;--muted:#7A8099;--red:#EF4444;--green:#22C55E;--border:rgba(201,168,76,0.18);}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);}
nav{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:1.2rem 3rem;background:rgba(10,12,16,0.9);backdrop-filter:blur(18px);border-bottom:1px solid var(--border);}
.logo{font-family:'Playfair Display',serif;font-size:1.7rem;font-weight:900;color:var(--gold);text-decoration:none;}
.logo span{color:var(--text);}
.container{max-width:900px;margin:0 auto;padding:8rem 2rem 4rem;}
h1{font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:900;margin-bottom:0.5rem;}
.updated{color:var(--muted);font-size:0.85rem;margin-bottom:3rem;}
.section{background:var(--dark3);border:1px solid var(--border);border-radius:16px;padding:2rem;margin-bottom:1.5rem;}
.section h2{font-size:1.1rem;font-weight:700;color:var(--gold);margin-bottom:1rem;}
.section p,.section li{color:var(--muted);line-height:1.8;font-size:0.92rem;margin-bottom:0.6rem;}
.section ul{padding-left:1.5rem;}
.highlight{background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);border-radius:10px;padding:1rem;margin-bottom:1rem;}
.highlight p{color:var(--green) !important;margin:0;}
.warning{background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:10px;padding:1rem;margin-bottom:1rem;}
.warning p{color:var(--red) !important;margin:0;}
footer{text-align:center;padding:2rem;color:var(--muted);font-size:0.82rem;border-top:1px solid var(--border);}
footer a{color:var(--gold);text-decoration:none;margin:0 0.8rem;}
</style>
</head>
<body>
<nav>
    <a href="{{ route('home') }}" class="logo">Invest<span>X</span></a>
    <a href="{{ route('login') }}" style="color:var(--gold);text-decoration:none;font-size:0.9rem">Login →</a>
</nav>

<div class="container">
    <h1>Refund & Cancellation Policy</h1>
    <p class="updated">Last Updated: {{ date('d M Y') }}</p>

    <div class="section">
        <h2>1. Investment Withdrawal</h2>
        <div class="highlight">
            <p>✅ Koi lock-in period nahi — aap kab bhi apna investment withdraw kar sakte hain.</p>
        </div>
        <p>Withdrawal request submit karne par:</p>
        <ul>
            <li>Principal amount hamesha wapas milega</li>
            <li>Profit pro-rated hoga — jitne din invest kiya utne ka</li>
            <li>Same day withdrawal par sirf principal milega, koi profit/fee nahi</li>
            <li>Processing time: 24 ghante</li>
        </ul>
    </div>

    <div class="section">
        <h2>2. Wallet Top-up Refund</h2>
        <p>Wallet top-up ke liye:</p>
        <ul>
            <li>Successful top-up refundable nahi hai</li>
            <li>Payment fail hone par Razorpay automatically refund karta hai 5-7 business days mein</li>
            <li>Double deduction hone par 24 ghante mein contact karein</li>
        </ul>
    </div>

    <div class="section">
        <h2>3. Payment Failure Refund</h2>
        <p>Agar payment deduct hua but investment create nahi hua:</p>
        <ul>
            <li>Razorpay automatically 5-7 business days mein refund karta hai</li>
            <li>Agar refund nahi aaya toh hamare support ko contact karein</li>
            <li>Payment ID aur screenshot zaroor rakhen</li>
        </ul>
    </div>

    <div class="section">
        <h2>4. Non-Refundable Items</h2>
        <div class="warning">
            <p>⚠️ Platform fee (commission) refundable nahi hai once deducted.</p>
        </div>
        <ul>
            <li>Platform management fee</li>
            <li>KYC processing charges (if any)</li>
            <li>Already settled profits</li>
        </ul>
    </div>

    <div class="section">
        <h2>5. Cancellation Policy</h2>
        <p>Investment cancel karne ke liye Withdrawals page par jaayein aur request submit karein. Admin 24 ghante mein process karega.</p>
    </div>

    <div class="section">
        <h2>6. Refund Process</h2>
        <ul>
            <li>Refund sirf registered bank account mein hoga</li>
            <li>Processing time: 24-48 ghante after approval</li>
            <li>Bank transfer time alag ho sakti hai (1-3 business days)</li>
        </ul>
    </div>

    {{-- <div class="section">
        <h2>7. Contact for Refund</h2>
        <p>Email: <strong>[SUPPORT EMAIL]</strong></p>
        <p>Phone: <strong>[PHONE]</strong></p>
        <p>Support Hours: Monday–Saturday, 10 AM – 6 PM</p>
    </div> --}}
</div>

<footer>
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('about') }}">About</a>
    <a href="{{ route('privacy') }}">Privacy</a>
    <a href="{{ route('terms') }}">Terms</a>
    <a href="{{ route('contact') }}">Contact</a>
    <br><br>© {{ date('Y') }} InvestX. All rights reserved.
</footer>
</body>
</html>