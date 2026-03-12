<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>InvestX — Smart Investment Platform</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --gold: #C9A84C; --gold-light: #E8C97A;
    --dark: #0A0C10; --dark2: #111318; --dark3: #181C24; --dark4: #1E2330;
    --text: #E8EAF0; --muted: #7A8099;
    --green: #22C55E; --red: #EF4444;
    --border: rgba(201,168,76,0.18);
  }
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'DM Sans',sans-serif; background:var(--dark); color:var(--text); overflow-x:hidden; }

  /* NAV */
  nav {
    position:fixed; top:0; left:0; right:0; z-index:100;
    display:flex; align-items:center; justify-content:space-between;
    padding:1.2rem 3rem;
    background:rgba(10,12,16,0.9);
    backdrop-filter:blur(18px);
    border-bottom:1px solid var(--border);
  }
  .logo { font-family:'Playfair Display',serif; font-size:1.7rem; font-weight:900; color:var(--gold); }
  .logo span { color:var(--text); }
  nav ul { display:flex; gap:2rem; list-style:none; }
  nav ul a { color:var(--muted); text-decoration:none; font-size:0.9rem; font-weight:500; transition:color 0.2s; }
  nav ul a:hover { color:var(--gold); }
  .nav-btns { display:flex; gap:0.8rem; }
  .btn-outline { padding:0.5rem 1.4rem; border:1px solid var(--border); background:transparent; color:var(--text); border-radius:6px; font-size:0.88rem; cursor:pointer; font-family:'DM Sans',sans-serif; transition:all 0.2s; text-decoration:none; }
  .btn-outline:hover { border-color:var(--gold); color:var(--gold); }
  .btn-gold { padding:0.5rem 1.4rem; background:var(--gold); color:#0A0C10; border:none; border-radius:6px; font-size:0.88rem; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif; transition:all 0.2s; text-decoration:none; }
  .btn-gold:hover { background:var(--gold-light); }

  /* TICKER */
  .ticker-wrap { margin-top:64px; background:rgba(201,168,76,0.06); border-bottom:1px solid var(--border); padding:0.6rem 0; overflow:hidden; }
  .ticker-inner { display:flex; gap:3rem; animation:tickerScroll 25s linear infinite; white-space:nowrap; }
  @keyframes tickerScroll { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }
  .ticker-item { font-size:0.8rem; display:flex; align-items:center; gap:0.4rem; flex-shrink:0; }
  .ticker-name { color:var(--muted); }
  .ticker-val { color:var(--text); font-weight:500; }
  .ticker-chg.up { color:var(--green); }
  .ticker-chg.dn { color:var(--red); }

  /* HERO */
  .hero { min-height:92vh; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; padding:6rem 2rem 4rem; position:relative; overflow:hidden; }
  .hero::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse 80% 60% at 50% 30%, rgba(201,168,76,0.1) 0%, transparent 70%); pointer-events:none; }
  .hero-grid { position:absolute; inset:0; background-image:linear-gradient(rgba(201,168,76,0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(201,168,76,0.04) 1px, transparent 1px); background-size:60px 60px; pointer-events:none; mask-image:radial-gradient(ellipse at center, black 30%, transparent 80%); }
  .hero-badge { display:inline-flex; align-items:center; gap:0.5rem; background:rgba(201,168,76,0.1); border:1px solid var(--border); border-radius:50px; padding:0.4rem 1.2rem; font-size:0.8rem; color:var(--gold); margin-bottom:2rem; }
  .hero h1 { font-family:'Playfair Display',serif; font-size:clamp(2.8rem,7vw,5.5rem); font-weight:900; line-height:1.1; margin-bottom:1.5rem; max-width:850px; }
  .hero h1 em { font-style:normal; color:var(--gold); }
  .hero p { font-size:1.1rem; color:var(--muted); max-width:540px; line-height:1.7; margin-bottom:3rem; }
  .hero-cta { display:flex; gap:1rem; justify-content:center; flex-wrap:wrap; }
  .btn-lg { padding:0.9rem 2.4rem; font-size:1rem; font-weight:600; border-radius:8px; cursor:pointer; font-family:'DM Sans',sans-serif; transition:all 0.25s; text-decoration:none; display:inline-block; }
  .btn-lg.gold { background:var(--gold); color:#0A0C10; border:none; }
  .btn-lg.gold:hover { background:var(--gold-light); transform:translateY(-2px); box-shadow:0 12px 40px rgba(201,168,76,0.3); }
  .btn-lg.ghost { background:transparent; color:var(--text); border:1px solid rgba(255,255,255,0.15); }
  .btn-lg.ghost:hover { border-color:var(--gold); color:var(--gold); }
  .hero-stats { display:flex; gap:3rem; margin-top:5rem; padding-top:2.5rem; border-top:1px solid var(--border); flex-wrap:wrap; justify-content:center; }
  .stat-num { font-family:'Playfair Display',serif; font-size:2.2rem; font-weight:700; color:var(--gold); display:block; }
  .stat-label { font-size:0.82rem; color:var(--muted); margin-top:0.3rem; }

  /* SECTIONS */
  section { padding:6rem 2rem; }
  .container { max-width:1100px; margin:0 auto; }
  .section-label { font-size:0.78rem; font-weight:600; letter-spacing:2px; color:var(--gold); text-transform:uppercase; margin-bottom:0.8rem; }
  .section-title { font-family:'Playfair Display',serif; font-size:clamp(2rem,4vw,3rem); font-weight:800; line-height:1.2; margin-bottom:1rem; }
  .section-sub { font-size:1rem; color:var(--muted); max-width:500px; line-height:1.7; }
  .section-head { margin-bottom:4rem; }

  /* HOW IT WORKS */
  .how-bg { background:var(--dark2); }
  .steps-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:2rem; }
  .step-card { background:var(--dark3); border:1px solid var(--border); border-radius:16px; padding:2rem; position:relative; overflow:hidden; transition:transform 0.3s, border-color 0.3s; }
  .step-card:hover { transform:translateY(-6px); border-color:var(--gold); }
  .step-card::before { content:''; position:absolute; top:0; left:0; right:0; height:2px; background:linear-gradient(90deg,var(--gold),transparent); }
  .step-num { font-family:'Playfair Display',serif; font-size:3.5rem; font-weight:900; color:rgba(201,168,76,0.15); line-height:1; margin-bottom:0.8rem; }
  .step-icon { width:48px; height:48px; background:rgba(201,168,76,0.12); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; margin-bottom:1.2rem; }
  .step-card h3 { font-size:1.1rem; font-weight:600; margin-bottom:0.6rem; }
  .step-card p { font-size:0.88rem; color:var(--muted); line-height:1.6; }

  /* PLANS */
  .plans-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:1.5rem; }
  .plan-card { background:var(--dark3); border:1px solid var(--border); border-radius:20px; padding:2.5rem; position:relative; transition:transform 0.3s; }
  .plan-card.featured { background:linear-gradient(135deg,rgba(201,168,76,0.12),rgba(201,168,76,0.04)); border-color:var(--gold); transform:scale(1.03); }
  .plan-card:hover { transform:translateY(-6px); }
  .plan-card.featured:hover { transform:scale(1.03) translateY(-6px); }
  .plan-badge { position:absolute; top:-13px; left:50%; transform:translateX(-50%); background:var(--gold); color:#0A0C10; font-size:0.7rem; font-weight:700; padding:3px 14px; border-radius:50px; letter-spacing:1px; text-transform:uppercase; white-space:nowrap; }
  .plan-name { font-size:0.82rem; font-weight:600; letter-spacing:2px; text-transform:uppercase; color:var(--muted); margin-bottom:0.8rem; }
  .plan-roi { font-family:'Playfair Display',serif; font-size:3.2rem; font-weight:900; color:var(--gold); line-height:1; margin-bottom:0.4rem; }
  .plan-period { font-size:0.85rem; color:var(--muted); margin-bottom:1.5rem; }
  .plan-features { list-style:none; margin-bottom:1.5rem; }
  .plan-features li { padding:0.5rem 0; font-size:0.88rem; color:var(--muted); display:flex; align-items:center; gap:0.6rem; border-bottom:1px solid rgba(255,255,255,0.05); }
  .plan-features li span { color:var(--green); }
  .plan-features li strong { color:var(--text); }
  .plan-min { font-size:0.82rem; color:var(--muted); margin-bottom:1.5rem; }
  .plan-min strong { color:var(--gold); font-size:1.1rem; }
  .btn-plan { width:100%; padding:0.85rem; border-radius:10px; font-size:0.95rem; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif; transition:all 0.2s; text-decoration:none; display:block; text-align:center; }
  .btn-plan.outline { background:transparent; border:1px solid var(--border); color:var(--text); }
  .btn-plan.outline:hover { border-color:var(--gold); color:var(--gold); }
  .btn-plan.filled { background:var(--gold); border:none; color:#0A0C10; }
  .btn-plan.filled:hover { background:var(--gold-light); }

  /* SECURITY */
  .security-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1.5rem; }
  .sec-card { background:var(--dark3); border:1px solid var(--border); border-radius:14px; padding:1.8rem; text-align:center; transition:transform 0.3s; }
  .sec-card:hover { transform:translateY(-4px); border-color:var(--gold); }
  .sec-icon { font-size:2rem; margin-bottom:1rem; display:block; }
  .sec-card h4 { font-size:0.95rem; font-weight:600; margin-bottom:0.4rem; }
  .sec-card p { font-size:0.82rem; color:var(--muted); line-height:1.5; }

  /* TESTIMONIALS */
  .testi-bg { background:var(--dark2); }
  .testi-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:1.5rem; }
  .testi-card { background:var(--dark3); border:1px solid var(--border); border-radius:16px; padding:2rem; }
  .stars { color:var(--gold); font-size:0.9rem; margin-bottom:1rem; }
  .testi-text { font-size:0.9rem; color:var(--muted); line-height:1.7; margin-bottom:1.5rem; font-style:italic; }
  .testi-user { display:flex; align-items:center; gap:0.8rem; }
  .avatar { width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg,var(--gold),var(--dark4)); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.9rem; color:#0A0C10; }
  .testi-name { font-size:0.9rem; font-weight:600; }
  .testi-loc { font-size:0.75rem; color:var(--muted); }

  /* FAQ */
  .faq-list { max-width:700px; margin:0 auto; }
  .faq-item { border:1px solid var(--border); border-radius:12px; margin-bottom:0.8rem; overflow:hidden; }
  .faq-q { padding:1.2rem 1.5rem; font-size:0.95rem; font-weight:600; cursor:pointer; display:flex; justify-content:space-between; align-items:center; background:var(--dark3); transition:color 0.2s; }
  .faq-q:hover { color:var(--gold); }
  .faq-a { padding:0 1.5rem 1.2rem; font-size:0.88rem; color:var(--muted); line-height:1.7; background:var(--dark3); display:none; }
  .faq-item.open .faq-a { display:block; }
  .faq-item.open .faq-q { color:var(--gold); }

  /* FOOTER */
  footer { background:var(--dark2); border-top:1px solid var(--border); padding:4rem 2rem 2rem; }
  .footer-grid { max-width:1100px; margin:0 auto; display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:3rem; margin-bottom:3rem; }
  .footer-logo { font-family:'Playfair Display',serif; font-size:1.5rem; font-weight:900; color:var(--gold); margin-bottom:1rem; }
  .footer-logo span { color:var(--text); }
  .footer-desc { font-size:0.85rem; color:var(--muted); line-height:1.7; max-width:280px; }
  .footer-col h5 { font-size:0.82rem; font-weight:600; letter-spacing:1px; text-transform:uppercase; color:var(--text); margin-bottom:1.2rem; }
  .footer-col ul { list-style:none; }
  .footer-col ul li { margin-bottom:0.6rem; }
  .footer-col ul a { color:var(--muted); text-decoration:none; font-size:0.85rem; transition:color 0.2s; }
  .footer-col ul a:hover { color:var(--gold); }
  .footer-bottom { max-width:1100px; margin:0 auto; padding-top:2rem; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; font-size:0.8rem; color:var(--muted); }

  @media(max-width:768px) {
    nav { padding:1rem 1.2rem; }
    nav ul { display:none; }
    .footer-grid { grid-template-columns:1fr 1fr; }
    .hero-stats { gap:1.5rem; }
  }
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <div class="logo">Invest<span>X</span></div>
  <ul>
    <li><a href="#how">Kaise Kaam Karta Hai</a></li>
    <li><a href="#plans">Plans</a></li>
    <li><a href="#security">Security</a></li>
    <li><a href="#faq">FAQ</a></li>
  </ul>
  <div class="nav-btns">
    <a href="{{ route('login') }}" class="btn-outline">Login</a>
    <a href="{{ route('register') }}" class="btn-gold">Register Karein</a>
  </div>
</nav>

<!-- TICKER -->
<div class="ticker-wrap">
  <div class="ticker-inner">
    <div class="ticker-item"><span class="ticker-name">BTC/USD</span><span class="ticker-val">₹67,42,150</span><span class="ticker-chg up">▲ 2.4%</span></div>
    <div class="ticker-item"><span class="ticker-name">ETH/USD</span><span class="ticker-val">₹3,18,200</span><span class="ticker-chg up">▲ 1.8%</span></div>
    <div class="ticker-item"><span class="ticker-name">NIFTY 50</span><span class="ticker-val">22,450</span><span class="ticker-chg dn">▼ 0.3%</span></div>
    <div class="ticker-item"><span class="ticker-name">SENSEX</span><span class="ticker-val">73,840</span><span class="ticker-chg up">▲ 0.5%</span></div>
    <div class="ticker-item"><span class="ticker-name">GOLD</span><span class="ticker-val">₹68,200/10g</span><span class="ticker-chg up">▲ 0.9%</span></div>
    <div class="ticker-item"><span class="ticker-name">USD/INR</span><span class="ticker-val">₹83.42</span><span class="ticker-chg dn">▼ 0.1%</span></div>
    <div class="ticker-item"><span class="ticker-name">BTC/USD</span><span class="ticker-val">₹67,42,150</span><span class="ticker-chg up">▲ 2.4%</span></div>
    <div class="ticker-item"><span class="ticker-name">ETH/USD</span><span class="ticker-val">₹3,18,200</span><span class="ticker-chg up">▲ 1.8%</span></div>
    <div class="ticker-item"><span class="ticker-name">NIFTY 50</span><span class="ticker-val">22,450</span><span class="ticker-chg dn">▼ 0.3%</span></div>
    <div class="ticker-item"><span class="ticker-name">SENSEX</span><span class="ticker-val">73,840</span><span class="ticker-chg up">▲ 0.5%</span></div>
    <div class="ticker-item"><span class="ticker-name">GOLD</span><span class="ticker-val">₹68,200/10g</span><span class="ticker-chg up">▲ 0.9%</span></div>
  </div>
</div>

<!-- HERO -->
<section class="hero">
  <div class="hero-grid"></div>
  <div class="hero-badge">🔒 SEBI Registered &nbsp;|&nbsp; 50,000+ Active Investors</div>
  <h1>Apna Paisa <em>Badhaiye</em><br>— Hum Invest Karenge</h1>
  <p>Hamari expert team aapka paisa stocks, crypto aur mutual funds mein invest karti hai. Profit ka bada hissa aapko milta hai, chhota hissa hamara commission.</p>
  <div class="hero-cta">
    <a href="{{ route('register') }}" class="btn-lg gold">Abhi Shuru Karein →</a>
    <a href="#plans" class="btn-lg ghost">Plans Dekhein</a>
  </div>
  <div class="hero-stats">
    <div><span class="stat-num">₹42Cr+</span><div class="stat-label">Total Invested</div></div>
    <div><span class="stat-num">18–32%</span><div class="stat-label">Annual Returns</div></div>
    <div><span class="stat-num">50K+</span><div class="stat-label">Happy Investors</div></div>
    <div><span class="stat-num">99.8%</span><div class="stat-label">Withdrawal Success</div></div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-bg" id="how">
  <div class="container">
    <div class="section-head">
      <div class="section-label">Process</div>
      <h2 class="section-title">Sirf 4 Steps Mein Shuru Karein</h2>
      <p class="section-sub">Koi complication nahi — register karein, invest karein, profit paayein, withdraw karein.</p>
    </div>
    <div class="steps-grid">
      <div class="step-card">
        <div class="step-num">01</div>
        <div class="step-icon">👤</div>
        <h3>Register & KYC</h3>
        <p>Free account banayein. Aadhar/PAN se KYC complete karein. 5 minute mein verified ho jayein.</p>
      </div>
      <div class="step-card">
        <div class="step-num">02</div>
        <div class="step-icon">💳</div>
        <h3>Amount Deposit Karein</h3>
        <p>UPI, Bank Transfer ya Net Banking se minimum ₹1,000 se invest karein. Instant credit.</p>
      </div>
      <div class="step-card">
        <div class="step-num">03</div>
        <div class="step-icon">📈</div>
        <h3>Hamari Team Invest Karti Hai</h3>
        <p>Experts aapka paisa diversified portfolio mein lagaate hain — stocks, crypto, bonds.</p>
      </div>
      <div class="step-card">
        <div class="step-num">04</div>
        <div class="step-icon">💰</div>
        <h3>Profit Withdraw Karein</h3>
        <p>Maturity pe principal + profit aapke account mein. Hamara sirf 15–20% commission kata jaata hai.</p>
      </div>
    </div>
  </div>
</section>

<!-- PLANS -->
<section id="plans">
  <div class="container">
    <div class="section-head">
      <div class="section-label">Investment Plans</div>
      <h2 class="section-title">Apna Plan Chunein</h2>
      <p class="section-sub">Har investor ke liye alag plan. Jitna invest karein, utna zyada return.</p>
    </div>
    <div class="plans-grid">
      <div class="plan-card">
        <div class="plan-name">Starter Plan</div>
        <div class="plan-roi">18%</div>
        <div class="plan-period">Salana Return (Expected)</div>
        <div class="plan-min">Minimum: <strong>₹1,000</strong></div>
        <ul class="plan-features">
          <li><span>✓</span> Lock-in: <strong>3 Mahine</strong></li>
          <li><span>✓</span> Commission: <strong>20%</strong></li>
          <li><span>✓</span> Monthly Profit Update</li>
          <li><span>✓</span> UPI Withdrawal</li>
        </ul>
        <a href="{{ route('register') }}" class="btn-plan outline">Shuru Karein</a>
      </div>
      <div class="plan-card featured">
        <div class="plan-badge">⭐ Most Popular</div>
        <div class="plan-name">Growth Plan</div>
        <div class="plan-roi">25%</div>
        <div class="plan-period">Salana Return (Expected)</div>
        <div class="plan-min">Minimum: <strong>₹10,000</strong></div>
        <ul class="plan-features">
          <li><span>✓</span> Lock-in: <strong>6 Mahine</strong></li>
          <li><span>✓</span> Commission: <strong>17%</strong></li>
          <li><span>✓</span> Weekly Profit Update</li>
          <li><span>✓</span> Priority Withdrawal</li>
        </ul>
        <a href="{{ route('register') }}" class="btn-plan filled">Shuru Karein</a>
      </div>
      <div class="plan-card">
        <div class="plan-name">Elite Plan</div>
        <div class="plan-roi">32%</div>
        <div class="plan-period">Salana Return (Expected)</div>
        <div class="plan-min">Minimum: <strong>₹1,00,000</strong></div>
        <ul class="plan-features">
          <li><span>✓</span> Lock-in: <strong>12 Mahine</strong></li>
          <li><span>✓</span> Commission: <strong>15%</strong></li>
          <li><span>✓</span> Daily Dashboard</li>
          <li><span>✓</span> Same-Day Withdrawal</li>
        </ul>
        <a href="{{ route('register') }}" class="btn-plan outline">Shuru Karein</a>
      </div>
    </div>
  </div>
</section>

<!-- SECURITY -->
<section id="security" style="background:var(--dark2)">
  <div class="container">
    <div class="section-head" style="text-align:center">
      <div class="section-label" style="text-align:center">Security</div>
      <h2 class="section-title">Aapka Paisa 100% Safe Hai</h2>
    </div>
    <div class="security-grid">
      <div class="sec-card"><span class="sec-icon">🔐</span><h4>256-bit SSL</h4><p>Har transaction bank-level encryption se protected hai.</p></div>
      <div class="sec-card"><span class="sec-icon">📋</span><h4>SEBI Registered</h4><p>Fully registered investment firm. Sab legal aur compliant.</p></div>
      <div class="sec-card"><span class="sec-icon">🔑</span><h4>2FA Authentication</h4><p>OTP + double layer security aapke account ke liye.</p></div>
      <div class="sec-card"><span class="sec-icon">📊</span><h4>Full Transparency</h4><p>Real-time dekho aapka paisa kahan invest hua.</p></div>
      <div class="sec-card"><span class="sec-icon">🏛️</span><h4>Escrow Protected</h4><p>Funds third-party escrow mein safe rehte hain.</p></div>
      <div class="sec-card"><span class="sec-icon">📱</span><h4>Instant Alerts</h4><p>Har transaction pe SMS + email alert milta hai.</p></div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="testi-bg">
  <div class="container">
    <div class="section-head">
      <div class="section-label">Reviews</div>
      <h2 class="section-title">Investors Kya Kehte Hain</h2>
    </div>
    <div class="testi-grid">
      <div class="testi-card">
        <div class="stars">★★★★★</div>
        <p class="testi-text">"Maine ₹25,000 lagaye the, 6 mahine mein ₹5,800 ka profit mila. Sab kuch transparent tha aur withdrawal fast hua."</p>
        <div class="testi-user"><div class="avatar">RK</div><div><div class="testi-name">Rajesh Kumar</div><div class="testi-loc">Jaipur, Rajasthan</div></div></div>
      </div>
      <div class="testi-card">
        <div class="stars">★★★★★</div>
        <p class="testi-text">"Elite Plan try kiya. 1 lakh pe 28,000 ka return mila 1 saal mein. Ab regularly invest karta hun."</p>
        <div class="testi-user"><div class="avatar">PS</div><div><div class="testi-name">Priya Sharma</div><div class="testi-loc">Surat, Gujarat</div></div></div>
      </div>
      <div class="testi-card">
        <div class="stars">★★★★☆</div>
        <p class="testi-text">"Dashboard bahut clear hai. Har din dekh sakta hun kitna profit aa raha hai. Support team bhi responsive hai."</p>
        <div class="testi-user"><div class="avatar">AM</div><div><div class="testi-name">Amit Mehta</div><div class="testi-loc">Ahmedabad, Gujarat</div></div></div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section id="faq">
  <div class="container">
    <div class="section-head" style="text-align:center">
      <div class="section-label" style="text-align:center">FAQ</div>
      <h2 class="section-title">Common Questions</h2>
    </div>
    <div class="faq-list">
      <div class="faq-item open">
        <div class="faq-q" onclick="this.parentElement.classList.toggle('open')">Minimum kitna invest kar sakta hun? <span>▼</span></div>
        <div class="faq-a">Starter Plan mein minimum ₹1,000 se shuru kar sakte hain. Growth Plan mein ₹10,000 aur Elite Plan mein ₹1,00,000 minimum hai.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="this.parentElement.classList.toggle('open')">Paisa kab aur kaise milega? <span>▼</span></div>
        <div class="faq-a">Lock-in period complete hone ke baad withdrawal request kar sakte hain. 4 ghante mein aapke bank account mein transfer ho jaata hai.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="this.parentElement.classList.toggle('open')">Commission kaise calculate hota hai? <span>▼</span></div>
        <div class="faq-a">Commission sirf PROFIT pe lagta hai — principal pe nahi. Example: ₹10,000 invest kiya, ₹2,500 profit aaya, Growth Plan ka 17% = ₹425 commission. Aapko ₹12,075 milenge.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="this.parentElement.classList.toggle('open')">Kya returns guaranteed hain? <span>▼</span></div>
        <div class="faq-a">Returns market-linked hain isliye exact guarantee nahi. Lekin hamari team ka track record consistently 18–32% annual returns ka raha hai.</div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-grid">
    <div>
      <div class="footer-logo">Invest<span>X</span></div>
      <p class="footer-desc">India ka trusted investment platform. Aapka paisa, hamari zimmedari. SEBI registered, RBI compliant.</p>
    </div>
    <div class="footer-col">
      <h5>Company</h5>
      <ul><li><a href="#">Hamare Baare Mein</a></li><li><a href="#">Team</a></li><li><a href="#">Careers</a></li></ul>
    </div>
    <div class="footer-col">
      <h5>Platform</h5>
      <ul><li><a href="{{ route('plans') }}">Investment Plans</a></li><li><a href="{{ route('login') }}">Dashboard</a></li><li><a href="#">Mobile App</a></li></ul>
    </div>
    <div class="footer-col">
      <h5>Support</h5>
      <ul><li><a href="#">Help Center</a></li><li><a href="#">Contact Us</a></li><li><a href="#">Terms & Conditions</a></li><li><a href="#">Privacy Policy</a></li></ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© {{ date('Y') }} InvestX Pvt. Ltd. All rights reserved.</span>
    <span>⚠️ Investments are subject to market risks.</span>
  </div>
</footer>

</body>
</html>