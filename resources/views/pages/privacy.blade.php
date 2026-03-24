<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Privacy Policy — DailyWealth</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#C9A84C;--dark:#0A0C10;--dark2:#111318;--dark3:#181C24;--text:#E8EAF0;--muted:#7A8099;--border:rgba(201,168,76,0.18);}
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
footer{text-align:center;padding:2rem;color:var(--muted);font-size:0.82rem;border-top:1px solid var(--border);}
footer a{color:var(--gold);text-decoration:none;margin:0 0.8rem;}
</style>
</head>
<body>
<nav>
    <a href="{{ route('home') }}" class="logo">Daily<span>Wealth</span></a>
    <a href="{{ route('login') }}" style="color:var(--gold);text-decoration:none;font-size:0.9rem">Login →</a>
</nav>

<div class="container">
    <h1>Privacy Policy</h1>
    <p class="updated">Last Updated: {{ date('d M Y') }}</p>

    <div class="section">
        <h2>1. Information We Collect</h2>
        <p>Hum yeh information collect karte hain:</p>
        <ul>
            <li>Personal details — naam, email, phone number</li>
            <li>KYC documents — Aadhaar, PAN card, bank details</li>
            <li>Financial information — investment history, transaction records</li>
            <li>Device information — IP address, browser type</li>
        </ul>
    </div>

    <div class="section">
        <h2>2. How We Use Your Information</h2>
        <ul>
            <li>Account create karne aur manage karne ke liye</li>
            <li>KYC verification ke liye</li>
            <li>Transactions process karne ke liye (Razorpay payment gateway)</li>
            <li>Customer support provide karne ke liye</li>
            <li>Legal compliance ke liye</li>
            <li>Platform security maintain karne ke liye</li>
        </ul>
    </div>

    <div class="section">
        <h2>3. Payment Information</h2>
        <p>Aapki payment processing Razorpay ke through hoti hai. Hum aapke card details store nahi karte. Razorpay PCI-DSS compliant hai. Razorpay ki privacy policy: <a href="https://razorpay.com/privacy" target="_blank" style="color:var(--gold)">razorpay.com/privacy</a></p>
    </div>

    <div class="section">
        <h2>4. Data Sharing</h2>
        <p>Hum aapka data kisi third party ko sell nahi karte. Data share hota hai sirf:</p>
        <ul>
            <li>Razorpay — payment processing ke liye</li>
            <li>Legal authorities — court order ya regulatory requirement par</li>
            <li>KYC verification agencies</li>
        </ul>
    </div>

    <div class="section">
        <h2>5. Data Security</h2>
        <p>Hum aapki sensitive information (Aadhaar, PAN, bank account) ko encrypt karke store karte hain. SSL/TLS encryption use hoti hai saari communications ke liye.</p>
    </div>

    <div class="section">
        <h2>6. Data Retention</h2>
        <p>Aapka data account active rahne tak store kiya jaata hai. Account delete karne ke baad bhi legal requirements ke liye 7 saal tak records rakhe ja sakte hain.</p>
    </div>

    <div class="section">
        <h2>7. Your Rights</h2>
        <ul>
            <li>Apna data access karne ka right</li>
            <li>Data correction ka right</li>
            <li>Account deletion ka right (legal obligations subject to)</li>
        </ul>
    </div>

    {{-- <div class="section">
        <h2>8. Contact</h2>
        <p>Privacy related queries ke liye: <strong>[EMAIL]</strong></p>
        <p>Address: <strong>[COMPANY ADDRESS]</strong></p>
    </div> --}}
</div>

<footer>
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('about') }}">About</a>
    <a href="{{ route('terms') }}">Terms</a>
    <a href="{{ route('refund') }}">Refund Policy</a>
    <a href="{{ route('contact') }}">Contact</a>
    <br><br>© {{ date('Y') }} DailyWealth. All rights reserved.
</footer>
</body>
</html>