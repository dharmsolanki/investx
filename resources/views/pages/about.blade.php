<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us — DailyWealth</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#C9A84C;--dark:#0A0C10;--dark2:#111318;--dark3:#181C24;--dark4:#1E2330;--text:#E8EAF0;--muted:#7A8099;--border:rgba(201,168,76,0.18);}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);}
nav{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:1.2rem 3rem;background:rgba(10,12,16,0.9);backdrop-filter:blur(18px);border-bottom:1px solid var(--border);}
.logo{font-family:'Playfair Display',serif;font-size:1.7rem;font-weight:900;color:var(--gold);text-decoration:none;}
.logo span{color:var(--text);}
.container{max-width:900px;margin:0 auto;padding:8rem 2rem 4rem;}
h1{font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:900;margin-bottom:0.5rem;}
.subtitle{color:var(--muted);margin-bottom:3rem;font-size:1rem;}
.section{background:var(--dark3);border:1px solid var(--border);border-radius:16px;padding:2rem;margin-bottom:1.5rem;}
.section h2{font-size:1.1rem;font-weight:700;color:var(--gold);margin-bottom:1rem;}
.section p{color:var(--muted);line-height:1.8;font-size:0.92rem;margin-bottom:0.8rem;}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem;}
.info-item .label{font-size:0.75rem;color:var(--muted);margin-bottom:0.3rem;text-transform:uppercase;letter-spacing:1px;}
.info-item .value{font-size:0.92rem;font-weight:600;}
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
    <h1>About Us</h1>
    <p class="subtitle">DailyWealth ke baare mein jaanein</p>

    <div class="section">
        <h2>🏢 Company Overview</h2>
        <p>DailyWealth ek professional forex trading community platform hai jahan expert traders aur investors milke trading karte hain aur returns share karte hain.</p>
        <p>Hamari team experienced forex traders se bani hai jo EUR/USD, GBP/USD, XAU/USD jaise major pairs mein trade karti hai aur members ke saath performance-based profit share karti hai.</p>
    </div>

    <div class="section">
        <h2>📋 Company Details</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="label">Company Name</div>
                <div class="value">Meridian Flow FZ-LLC</div>
            </div>
            <div class="info-item">
                <div class="label">Registration No.</div>
                <div class="value">0000004082916</div>
            </div>
            <div class="info-item">
                <div class="label">License No.</div>
                <div class="value">45034321</div>
            </div>
            <div class="info-item">
                <div class="label">Founded</div>
                <div class="value">03 Feb 2026</div>
            </div>
            <div class="info-item">
                <div class="label">Business Type</div>
                <div class="value">E-Commerce (Products & Services E-Trading)</div>
            </div>
            <div class="info-item">
                <div class="label">Manager</div>
                <div class="value">Jainil Mahipalsinh Chauhan</div>
            </div>
            <div class="info-item">
                <div class="label">Registered Address</div>
                <div class="value">Compass Building, Al Shohada Road, Al Hamra Industrial Zone-FZ, Ras Al Khaimah, UAE</div>
            </div>
            <div class="info-item">
                <div class="label">Email</div>
                <div class="value"><a href="mailto:jayrajmchauhan6271@gmail.com" style="color:var(--gold)">jayrajmchauhan6271@gmail.com</a></div>
            </div>
            <div class="info-item">
                <div class="label">Phone</div>
                <div class="value"><a href="tel:+971524185587" style="color:var(--gold)">+971 52 418 5587</a></div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>🎯 Hamara Mission</h2>
        <p>Hamara mission hai ki har aam investor ko expert forex trading ka faida mil sake bina khud trading seekhe. Paisa aapka, expertise hamari.</p>
    </div>

    <div class="section">
        <h2>🔐 Security & Trust</h2>
        <p>Aapke funds 100% secure hain. Har transaction SSL-encrypted hai. Hamare sabhi traders KYC-verified professionals hain.</p>
    </div>
</div>

<footer>
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('privacy') }}">Privacy Policy</a>
    <a href="{{ route('terms') }}">Terms</a>
    <a href="{{ route('refund') }}">Refund Policy</a>
    <a href="{{ route('contact') }}">Contact</a>
    <br><br>© {{ date('Y') }} Meridian Flow FZ-LLC. All rights reserved.
</footer>
</body>
</html>