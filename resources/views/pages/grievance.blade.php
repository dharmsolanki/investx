<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Grievance Redressal — InvestX</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#C9A84C;--dark:#0A0C10;--dark3:#181C24;--dark4:#1E2330;--text:#E8EAF0;--muted:#7A8099;--border:rgba(201,168,76,0.18);}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);}
nav{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:1.2rem 3rem;background:rgba(10,12,16,0.9);backdrop-filter:blur(18px);border-bottom:1px solid var(--border);}
.logo{font-family:'Playfair Display',serif;font-size:1.7rem;font-weight:900;color:var(--gold);text-decoration:none;}
.logo span{color:var(--text);}
.container{max-width:900px;margin:0 auto;padding:8rem 2rem 4rem;}
h1{font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:900;margin-bottom:0.5rem;}
.subtitle{color:var(--muted);margin-bottom:3rem;}
.section{background:var(--dark3);border:1px solid var(--border);border-radius:16px;padding:2rem;margin-bottom:1.5rem;}
.section h2{font-size:1.1rem;font-weight:700;color:var(--gold);margin-bottom:1rem;}
.section p,.section li{color:var(--muted);line-height:1.8;font-size:0.92rem;margin-bottom:0.6rem;}
.section ul{padding-left:1.5rem;}
.officer-card{background:var(--dark4);border:1px solid var(--border);border-radius:12px;padding:1.5rem;}
.officer-card .name{font-size:1.1rem;font-weight:700;margin-bottom:0.3rem;}
.officer-card .role{font-size:0.8rem;color:var(--gold);margin-bottom:1rem;text-transform:uppercase;letter-spacing:1px;}
.officer-detail{display:flex;align-items:center;gap:0.6rem;font-size:0.88rem;margin-bottom:0.5rem;color:var(--muted);}
.step-item{display:flex;gap:1rem;margin-bottom:1.2rem;align-items:start;}
.step-num{background:var(--gold);color:#0A0C10;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.8rem;flex-shrink:0;margin-top:2px;}
.step-content h3{font-size:0.92rem;font-weight:600;margin-bottom:0.3rem;}
.step-content p{font-size:0.85rem;color:var(--muted);margin:0;}
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
    <h1>Grievance Redressal</h1>
    <p class="subtitle">Aapki complaints aur issues ka timely resolution hamari priority hai</p>

    <div class="section">
        <h2>👤 Grievance Officer</h2>
        <div class="officer-card">
            <div class="name">[GRIEVANCE OFFICER NAME]</div>
            <div class="role">Grievance Officer — InvestX</div>
            <div class="officer-detail">📧 <span>[GRIEVANCE EMAIL]</span></div>
            <div class="officer-detail">📱 <span>[PHONE]</span></div>
            <div class="officer-detail">🏢 <span>[COMPANY ADDRESS]</span></div>
            <div class="officer-detail">🕐 <span>Monday–Friday, 10 AM – 6 PM</span></div>
        </div>
    </div>

    <div class="section">
        <h2>📋 Grievance Process</h2>
        <div class="step-item">
            <div class="step-num">1</div>
            <div class="step-content">
                <h3>Complaint Submit Karein</h3>
                <p>Email karein grievance officer ko saari details ke saath — transaction ID, screenshot, description.</p>
            </div>
        </div>
        <div class="step-item">
            <div class="step-num">2</div>
            <div class="step-content">
                <h3>Acknowledgement (24 Hours)</h3>
                <p>Aapko 24 ghante mein complaint ka acknowledgement milega ek ticket number ke saath.</p>
            </div>
        </div>
        <div class="step-item">
            <div class="step-num">3</div>
            <div class="step-content">
                <h3>Investigation (3–5 Business Days)</h3>
                <p>Hamaari team aapki complaint investigate karegi aur zaroorat padne par additional information maangi ja sakti hai.</p>
            </div>
        </div>
        <div class="step-item">
            <div class="step-num">4</div>
            <div class="step-content">
                <h3>Resolution (7 Business Days)</h3>
                <p>7 business days mein final resolution provide kiya jayega. Complex cases mein 15 days lag sakte hain.</p>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>📝 Complaint Submit Karne Ke Liye Yeh Include Karein</h2>
        <ul>
            <li>Aapka registered email aur phone number</li>
            <li>Transaction ID / Payment ID (agar applicable)</li>
            <li>Issue ka detailed description</li>
            <li>Screenshots (agar koi error tha)</li>
            <li>Expected resolution kya chahiye</li>
        </ul>
    </div>

    <div class="section">
        <h2>⚡ Escalation</h2>
        <p>Agar 15 business days mein satisfactory resolution nahi milta toh aap Razorpay ke Payment Aggregator Grievance Portal par escalate kar sakte hain:</p>
        <p><a href="https://razorpay.com/grievances" target="_blank" style="color:var(--gold)">razorpay.com/grievances</a></p>
        <p style="margin-top:0.8rem">Aap RBI Ombudsman se bhi contact kar sakte hain: <a href="https://cms.rbi.org.in" target="_blank" style="color:var(--gold)">cms.rbi.org.in</a></p>
    </div>
</div>

<footer>
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('about') }}">About</a>
    <a href="{{ route('privacy') }}">Privacy</a>
    <a href="{{ route('refund') }}">Refund</a>
    <a href="{{ route('contact') }}">Contact</a>
    <br><br>© {{ date('Y') }} InvestX. All rights reserved.
</footer>
</body>
</html>