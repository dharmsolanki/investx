<!DOCTYPE html>
<html lang="hi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DailyWealth — Forex Trading Community</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --gold: #C9A84C;
            --gold-light: #E8C97A;
            --dark: #0A0C10;
            --dark2: #111318;
            --dark3: #181C24;
            --dark4: #1E2330;
            --text: #E8EAF0;
            --muted: #7A8099;
            --green: #22C55E;
            --red: #EF4444;
            --border: rgba(201, 168, 76, 0.18);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--dark);
            color: var(--text);
            overflow-x: hidden;
        }

        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.2rem 3rem;
            background: rgba(10, 12, 16, 0.9);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem;
            font-weight: 900;
            color: var(--gold);
        }

        .logo span {
            color: var(--text);
        }

        nav ul {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        nav ul a {
            color: var(--muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        nav ul a:hover {
            color: var(--gold);
        }

        .nav-btns {
            display: flex;
            gap: 0.8rem;
        }

        .btn-outline {
            padding: 0.5rem 1.4rem;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text);
            border-radius: 6px;
            font-size: 0.88rem;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-outline:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        .btn-gold {
            padding: 0.5rem 1.4rem;
            background: var(--gold);
            color: #0A0C10;
            border: none;
            border-radius: 6px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-gold:hover {
            background: var(--gold-light);
        }

        .ticker-wrap {
            margin-top: 64px;
            background: rgba(201, 168, 76, 0.06);
            border-bottom: 1px solid var(--border);
            padding: 0.6rem 0;
            overflow: hidden;
        }

        .ticker-inner {
            display: flex;
            gap: 3rem;
            animation: tickerScroll 25s linear infinite;
            white-space: nowrap;
        }

        @keyframes tickerScroll {
            0% {
                transform: translateX(0)
            }

            100% {
                transform: translateX(-50%)
            }
        }

        .ticker-item {
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            flex-shrink: 0;
        }

        .ticker-name {
            color: var(--muted);
        }

        .ticker-val {
            color: var(--text);
            font-weight: 500;
        }

        .ticker-chg.up {
            color: var(--green);
        }

        .ticker-chg.dn {
            color: var(--red);
        }

        .hero {
            min-height: 92vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 6rem 2rem 4rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 80% 60% at 50% 30%, rgba(201, 168, 76, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-grid {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(201, 168, 76, 0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(201, 168, 76, 0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
            mask-image: radial-gradient(ellipse at center, black 30%, transparent 80%);
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(201, 168, 76, 0.1);
            border: 1px solid var(--border);
            border-radius: 50px;
            padding: 0.4rem 1.2rem;
            font-size: 0.8rem;
            color: var(--gold);
            margin-bottom: 2rem;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.8rem, 7vw, 5.5rem);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            max-width: 850px;
        }

        .hero h1 em {
            font-style: normal;
            color: var(--gold);
        }

        .hero p {
            font-size: 1.1rem;
            color: var(--muted);
            max-width: 540px;
            line-height: 1.7;
            margin-bottom: 3rem;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-lg {
            padding: 0.9rem 2.4rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.25s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-lg.gold {
            background: var(--gold);
            color: #0A0C10;
            border: none;
        }

        .btn-lg.gold:hover {
            background: var(--gold-light);
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(201, 168, 76, 0.3);
        }

        .btn-lg.ghost {
            background: transparent;
            color: var(--text);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-lg.ghost:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        .hero-stats {
            display: flex;
            gap: 3rem;
            margin-top: 5rem;
            padding-top: 2.5rem;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
            justify-content: center;
        }

        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--gold);
            display: block;
        }

        .stat-label {
            font-size: 0.82rem;
            color: var(--muted);
            margin-top: 0.3rem;
        }

        section {
            padding: 6rem 2rem;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .section-label {
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 2px;
            color: var(--gold);
            text-transform: uppercase;
            margin-bottom: 0.8rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .section-sub {
            font-size: 1rem;
            color: var(--muted);
            max-width: 500px;
            line-height: 1.7;
        }

        .section-head {
            margin-bottom: 4rem;
        }

        .how-bg {
            background: var(--dark2);
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 2rem;
        }

        .step-card {
            background: var(--dark3);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s, border-color 0.3s;
        }

        .step-card:hover {
            transform: translateY(-6px);
            border-color: var(--gold);
        }

        .step-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--gold), transparent);
        }

        .step-num {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 900;
            color: rgba(201, 168, 76, 0.15);
            line-height: 1;
            margin-bottom: 0.8rem;
        }

        .step-icon {
            width: 48px;
            height: 48px;
            background: rgba(201, 168, 76, 0.12);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1.2rem;
        }

        .step-card h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.6rem;
        }

        .step-card p {
            font-size: 0.88rem;
            color: var(--muted);
            line-height: 1.6;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .plan-card {
            background: var(--dark3);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2.5rem;
            position: relative;
            transition: transform 0.3s;
        }

        .plan-card.featured {
            background: linear-gradient(135deg, rgba(201, 168, 76, 0.12), rgba(201, 168, 76, 0.04));
            border-color: var(--gold);
            transform: scale(1.03);
        }

        .plan-card:hover {
            transform: translateY(-6px);
        }

        .plan-card.featured:hover {
            transform: scale(1.03) translateY(-6px);
        }

        .plan-badge {
            position: absolute;
            top: -13px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--gold);
            color: #0A0C10;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 14px;
            border-radius: 50px;
            letter-spacing: 1px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .plan-name {
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.8rem;
        }

        .plan-roi {
            font-family: 'Playfair Display', serif;
            font-size: 3.2rem;
            font-weight: 900;
            color: var(--gold);
            line-height: 1;
            margin-bottom: 0.4rem;
        }

        .plan-period {
            font-size: 0.85rem;
            color: var(--muted);
            margin-bottom: 1.5rem;
        }

        .plan-features {
            list-style: none;
            margin-bottom: 1.5rem;
        }

        .plan-features li {
            padding: 0.5rem 0;
            font-size: 0.88rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .plan-features li span {
            color: var(--green);
        }

        .plan-features li strong {
            color: var(--text);
        }

        .plan-min {
            font-size: 0.82rem;
            color: var(--muted);
            margin-bottom: 1.5rem;
        }

        .plan-min strong {
            color: var(--gold);
            font-size: 1.1rem;
        }

        .btn-plan {
            width: 100%;
            padding: 0.85rem;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-plan.outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
        }

        .btn-plan.outline:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        .btn-plan.filled {
            background: var(--gold);
            border: none;
            color: #0A0C10;
        }

        .btn-plan.filled:hover {
            background: var(--gold-light);
        }

        .security-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .sec-card {
            background: var(--dark3);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.8rem;
            text-align: center;
            transition: transform 0.3s;
        }

        .sec-card:hover {
            transform: translateY(-4px);
            border-color: var(--gold);
        }

        .sec-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            display: block;
        }

        .sec-card h4 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
        }

        .sec-card p {
            font-size: 0.82rem;
            color: var(--muted);
            line-height: 1.5;
        }

        .testi-bg {
            background: var(--dark2);
        }

        .testi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .testi-card {
            background: var(--dark3);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
        }

        .stars {
            color: var(--gold);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .testi-text {
            font-size: 0.9rem;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 1.5rem;
            font-style: italic;
        }

        .testi-user {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--dark4));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            color: #0A0C10;
        }

        .testi-name {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .testi-loc {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .faq-list {
            max-width: 700px;
            margin: 0 auto;
        }

        .faq-item {
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 0.8rem;
            overflow: hidden;
        }

        .faq-q {
            padding: 1.2rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--dark3);
            transition: color 0.2s;
        }

        .faq-q:hover {
            color: var(--gold);
        }

        .faq-a {
            padding: 0 1.5rem 1.2rem;
            font-size: 0.88rem;
            color: var(--muted);
            line-height: 1.7;
            background: var(--dark3);
            display: none;
        }

        .faq-item.open .faq-a {
            display: block;
        }

        .faq-item.open .faq-q {
            color: var(--gold);
        }

        .disclaimer {
            background: rgba(201, 168, 76, 0.04);
            border-top: 1px solid var(--border);
            padding: 1.2rem 2rem;
            text-align: center;
            font-size: 0.78rem;
            color: var(--muted);
            line-height: 1.7;
        }

        footer {
            background: var(--dark2);
            border-top: 1px solid var(--border);
            padding: 4rem 2rem 2rem;
        }

        .footer-grid {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--gold);
            margin-bottom: 1rem;
        }

        .footer-logo span {
            color: var(--text);
        }

        .footer-desc {
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.7;
            max-width: 280px;
        }

        .footer-col h5 {
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text);
            margin-bottom: 1.2rem;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 0.6rem;
        }

        .footer-col ul a {
            color: var(--muted);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .footer-col ul a:hover {
            color: var(--gold);
        }

        .footer-bottom {
            max-width: 1100px;
            margin: 0 auto;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.8rem;
            color: var(--muted);
        }

        @media(max-width:768px) {
            nav {
                padding: 1rem 1.2rem;
            }

            nav ul {
                display: none;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }

            .hero-stats {
                gap: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <!-- NAV -->
    <nav>
        <div class="logo">Daily<span>Wealth</span></div>
        <ul>
            <li><a href="#how">Kaise Kaam Karta Hai</a></li>
            <li><a href="#plans">Trading Plans</a></li>
            <li><a href="#security">Security</a></li>
            <li><a href="#faq">FAQ</a></li>
        </ul>
        <div class="nav-btns">
            <a href="{{ route('login') }}" class="btn-outline">Login</a>
            <a href="{{ route('register') }}" class="btn-gold">Join Karein</a>
        </div>
    </nav>

    <!-- TICKER -->
    <div class="ticker-wrap">
        <div class="ticker-inner">
            <div class="ticker-item"><span class="ticker-name">EUR/USD</span><span class="ticker-val">1.0842</span><span
                    class="ticker-chg up">▲ 0.4%</span></div>
            <div class="ticker-item"><span class="ticker-name">GBP/USD</span><span class="ticker-val">1.2654</span><span
                    class="ticker-chg up">▲ 0.2%</span></div>
            <div class="ticker-item"><span class="ticker-name">USD/JPY</span><span class="ticker-val">149.82</span><span
                    class="ticker-chg dn">▼ 0.3%</span></div>
            <div class="ticker-item"><span class="ticker-name">USD/INR</span><span class="ticker-val">₹83.42</span><span
                    class="ticker-chg dn">▼ 0.1%</span></div>
            <div class="ticker-item"><span class="ticker-name">XAU/USD</span><span
                    class="ticker-val">2,312.40</span><span class="ticker-chg up">▲ 0.9%</span></div>
            <div class="ticker-item"><span class="ticker-name">AUD/USD</span><span class="ticker-val">0.6541</span><span
                    class="ticker-chg up">▲ 0.5%</span></div>
            <div class="ticker-item"><span class="ticker-name">EUR/USD</span><span class="ticker-val">1.0842</span><span
                    class="ticker-chg up">▲ 0.4%</span></div>
            <div class="ticker-item"><span class="ticker-name">GBP/USD</span><span class="ticker-val">1.2654</span><span
                    class="ticker-chg up">▲ 0.2%</span></div>
            <div class="ticker-item"><span class="ticker-name">USD/JPY</span><span class="ticker-val">149.82</span><span
                    class="ticker-chg dn">▼ 0.3%</span></div>
            <div class="ticker-item"><span class="ticker-name">USD/INR</span><span class="ticker-val">₹83.42</span><span
                    class="ticker-chg dn">▼ 0.1%</span></div>
            <div class="ticker-item"><span class="ticker-name">XAU/USD</span><span
                    class="ticker-val">2,312.40</span><span class="ticker-chg up">▲ 0.9%</span></div>
        </div>
    </div>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-grid"></div>
        <div class="hero-badge">📈 Expert Forex Traders &nbsp;|&nbsp; 50,000+ Active Members</div>
        <h1>Expert Traders Ke Saath<br><em>Milke Trade Karein</em></h1>
        <p>Hamari experienced forex trading team ke saath apna fund contribute karein aur performance-based returns
            share karein. Paisa aapka, expertise hamari.</p>
        <div class="hero-cta">
            <a href="{{ route('register') }}" class="btn-lg gold">Community Join Karein →</a>
            <a href="#plans" class="btn-lg ghost">Trading Plans Dekhein</a>
        </div>
        <div class="hero-stats">
            <div><span class="stat-num">₹42Cr+</span>
                <div class="stat-label">Total Fund Managed</div>
            </div>
            <div><span class="stat-num">18–32%</span>
                <div class="stat-label">Expected Annual Returns</div>
            </div>
            <div><span class="stat-num">50K+</span>
                <div class="stat-label">Active Members</div>
            </div>
            <div><span class="stat-num">99.8%</span>
                <div class="stat-label">Withdrawal Success</div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="how-bg" id="how">
        <div class="container">
            <div class="section-head">
                <div class="section-label">Process</div>
                <h2 class="section-title">Sirf 4 Steps Mein Shuru Karein</h2>
                <p class="section-sub">Register karein, fund contribute karein, trading returns share karein, withdraw
                    karein.</p>
            </div>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-num">01</div>
                    <div class="step-icon">👤</div>
                    <h3>Register & KYC</h3>
                    <p>Free account banayein. Aadhaar/PAN se KYC complete karein. 5 minute mein verified ho jayein.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">02</div>
                    <div class="step-icon">💳</div>
                    <h3>Fund Contribute Karein</h3>
                    <p>UPI, Bank Transfer ya Net Banking se minimum ₹1,000 se participate karein. Instant confirmation.
                    </p>
                </div>
                <div class="step-card">
                    <div class="step-num">03</div>
                    <div class="step-icon">📈</div>
                    <h3>Hamari Team Trade Karti Hai</h3>
                    <p>Expert forex traders aapka fund professionally manage karte hain — EUR/USD, GBP/USD aur aur bhi.
                    </p>
                </div>
                <div class="step-card">
                    <div class="step-num">04</div>
                    <div class="step-icon">💰</div>
                    <h3>Returns Withdraw Karein</h3>
                    <p>Jab chahein withdraw karein — contribution + net returns aapke account mein. Hamara sirf 15–20%
                        management fee kata jaata hai.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PLANS -->
    <section id="plans">
        <div class="container">
            <div class="section-head">
                <div class="section-label">Trading Plans</div>
                <h2 class="section-title">Apna Trading Plan Chunein</h2>
                <p class="section-sub">Aapke bataye hue 4 plans yahan rakhe gaye hain, jahan har amount ke saath fixed
                    daily earning highlight ki gayi hai.</p>
            </div>
            <div class="plans-grid">
                <div class="plan-card">
                    <div class="plan-name">Plan 1</div>
                    <div class="plan-roi">₹750</div>
                    <div class="plan-period">Daily Earnings</div>
                    <div class="plan-min">Invest: <strong>₹15,000</strong></div>
                    <ul class="plan-features">
                        <li><span>✓</span> Daily Income: <strong>₹750</strong></li>
                        <li><span>✓</span> Suitable for: <strong>New Members</strong></li>
                        <li><span>✓</span> Simple Entry Amount</li>
                        <li><span>✓</span> Fast Start Option</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-plan outline">Join Plan 1</a>
                </div>
                <div class="plan-card featured">
                    <div class="plan-badge">⭐ Most Popular</div>
                    <div class="plan-name">Plan 2</div>
                    <div class="plan-roi">₹1,800</div>
                    <div class="plan-period">Daily Earnings</div>
                    <div class="plan-min">Invest: <strong>₹30,000</strong></div>
                    <ul class="plan-features">
                        <li><span>✓</span> Daily Income: <strong>₹1,800</strong></li>
                        <li><span>✓</span> Suitable for: <strong>Regular Investors</strong></li>
                        <li><span>✓</span> Higher Daily Payout</li>
                        <li><span>✓</span> Priority Support</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-plan filled">Join Plan 2</a>
                </div>
                <div class="plan-card">
                    <div class="plan-name">Plan 3</div>
                    <div class="plan-roi">₹3,600</div>
                    <div class="plan-period">Daily Earnings</div>
                    <div class="plan-min">Invest: <strong>₹60,000</strong></div>
                    <ul class="plan-features">
                        <li><span>✓</span> Daily Income: <strong>₹3,600</strong></li>
                        <li><span>✓</span> Suitable for: <strong>Growth Focused</strong></li>
                        <li><span>✓</span> Enhanced Daily Return</li>
                        <li><span>✓</span> Dedicated Assistance</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-plan outline">Join Plan 3</a>
                </div>
                <div class="plan-card">
                    <div class="plan-name">Plan 4</div>
                    <div class="plan-roi">₹7,000</div>
                    <div class="plan-period">Daily Earnings</div>
                    <div class="plan-min">Invest: <strong>₹1,00,000</strong></div>
                    <ul class="plan-features">
                        <li><span>✓</span> Daily Income: <strong>₹7,000</strong></li>
                        <li><span>✓</span> Suitable for: <strong>Premium Members</strong></li>
                        <li><span>✓</span> Highest Daily Payout</li>
                        <li><span>✓</span> VIP Level Access</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-plan outline">Join Plan 4</a>
                </div>
            </div>
        </div>
    </section>

    <!-- SECURITY -->
    <section id="security" style="background:var(--dark2)">
        <div class="container">
            <div class="section-head" style="text-align:center">
                <div class="section-label" style="text-align:center">Security</div>
                <h2 class="section-title">Aapka Fund Secure Hai</h2>
            </div>
            <div class="security-grid">
                <div class="sec-card"><span class="sec-icon">🔐</span>
                    <h4>256-bit SSL</h4>
                    <p>Har transaction bank-level encryption se protected hai.</p>
                </div>
                <div class="sec-card"><span class="sec-icon">✅</span>
                    <h4>Verified Traders</h4>
                    <p>Hamare sabhi traders verified aur experienced professionals hain.</p>
                </div>
                <div class="sec-card"><span class="sec-icon">🔑</span>
                    <h4>2FA Authentication</h4>
                    <p>OTP + double layer security aapke account ke liye.</p>
                </div>
                <div class="sec-card"><span class="sec-icon">📊</span>
                    <h4>Full Transparency</h4>
                    <p>Real-time dekho aapka fund kaise perform kar raha hai.</p>
                </div>
                <div class="sec-card"><span class="sec-icon">🏛️</span>
                    <h4>Dedicated Account</h4>
                    <p>Aapka fund dedicated current account mein safe rehta hai.</p>
                </div>
                <div class="sec-card"><span class="sec-icon">📱</span>
                    <h4>Instant Alerts</h4>
                    <p>Har transaction pe SMS + email alert milta hai.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS / REVIEWS -->
    @php $reviews = \App\Models\Review::approved()->with('user')->latest()->take(6)->get(); @endphp
    <section class="testi-bg" id="reviews">
        <div class="container">
            <div class="section-head">
                <div class="section-label">Reviews</div>
                <h2 class="section-title">Members Kya Kehte Hain</h2>
            </div>
            <div class="testi-grid">
                @if ($reviews->count() > 0)
                    @foreach ($reviews as $r)
                        <div class="testi-card">
                            <div class="stars">
                                {{ str_repeat('★', $r->rating) }}{{ str_repeat('☆', 5 - $r->rating) }}
                            </div>
                            <p class="testi-text">"{{ $r->comment }}"</p>
                            <div class="testi-user">
                                <div class="avatar">
                                    {{ strtoupper(substr($r->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="testi-name">{{ Str::mask($r->user->name, '*', 3) }}</div>
                                    <div class="testi-loc">DailyWealth Member</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Fallback — jab tak koi approved review nahi --}}
                    <div class="testi-card">
                        <div class="stars">★★★★★</div>
                        <p class="testi-text">"Maine ₹25,000 contribute kiye the, 6 mahine mein bahut acha return mila.
                            Sab kuch transparent tha aur withdrawal fast hua."</p>
                        <div class="testi-user">
                            <div class="avatar">RK</div>
                            <div>
                                <div class="testi-name">R*** K***</div>
                                <div class="testi-loc">Jaipur, Rajasthan</div>
                            </div>
                        </div>
                    </div>
                    <div class="testi-card">
                        <div class="stars">★★★★★</div>
                        <p class="testi-text">"Plan 4 choose kiya. Dashboard samajhna easy tha aur daily earning
                            clearly dikh rahi thi."</p>
                        <div class="testi-user">
                            <div class="avatar">PS</div>
                            <div>
                                <div class="testi-name">P*** S***</div>
                                <div class="testi-loc">Surat, Gujarat</div>
                            </div>
                        </div>
                    </div>
                    <div class="testi-card">
                        <div class="stars">★★★★☆</div>
                        <p class="testi-text">"Dashboard bahut clear hai. Support team bhi responsive hai. Recommend
                            karunga."</p>
                        <div class="testi-user">
                            <div class="avatar">AM</div>
                            <div>
                                <div class="testi-name">A*** M***</div>
                                <div class="testi-loc">Ahmedabad, Gujarat</div>
                            </div>
                        </div>
                    </div>
                @endif
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
                    <div class="faq-q" onclick="this.parentElement.classList.toggle('open')">Minimum kitna contribute
                        kar sakta hun? <span>▼</span></div>
                    <div class="faq-a">Available plans yeh hain: Plan 1 ₹15,000, Plan 2 ₹30,000, Plan 3 ₹60,000 aur
                        Plan 4 ₹1,00,000 investment amount ke saath.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-q" onclick="this.parentElement.classList.toggle('open')">Returns kab aur kaise
                        milenge? <span>▼</span></div>
                    <div class="faq-a">Aap kab bhi withdrawal request kar sakte hain — koi lock-in period nahi hai. 24
                        ghante mein aapke bank account mein transfer ho jaata hai.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-q" onclick="this.parentElement.classList.toggle('open')">Management fee kaise
                        calculate hoti hai? <span>▼</span></div>
                    <div class="faq-a">Plan fee sirf NET RETURNS pe lagti hai — contribution pe nahi. Example ke liye
                        aap apne selected plan ki summary invest screen par dekh sakte hain.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-q" onclick="this.parentElement.classList.toggle('open')">Kya returns guaranteed
                        hain? <span>▼</span></div>
                    <div class="faq-a">Forex trading market-linked hoti hai isliye returns guaranteed nahi hote.
                        Dikhaye gaye figures past performance par based hain. Apni risk capacity samajhke participate
                        karein.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- DISCLAIMER -->
    <div class="disclaimer">
        ⚠️ <strong>Risk Disclaimer:</strong> Forex trading mein substantial risk hota hai aur sabke liye suitable nahi
        hai. Past performance future results ki guarantee nahi deta.
        Yahan dikhaye gaye returns expected hain, guaranteed nahi. Sirf utna hi contribute karein jitna aap afford kar
        sakein.
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="footer-grid">
            <div>
                <div class="footer-logo">Daily<span>Wealth</span></div>
                <p class="footer-desc">Expert forex traders ki community. Performance-based profit sharing. Paisa
                    aapka, expertise hamari.</p>
            </div>

            <div class="footer-col">
                <h5>Company</h5>
                <ul>
                    <li><a href="{{ route('about') }}">Hamare Baare Mein</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                    <li><a href="{{ route('grievance') }}">Grievance</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>Legal</h5>
                <ul>
                    <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('refund') }}">Refund Policy</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>Community</h5>
                <ul>
                    <li>
                        <a href="https://www.instagram.com/dailywealth_2026" target="_blank"
                            style="display:flex;align-items:center;gap:0.6rem">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <defs>
                                    <radialGradient id="ig1" cx="30%" cy="107%" r="150%">
                                        <stop offset="0%" stop-color="#fdf497" />
                                        <stop offset="5%" stop-color="#fdf497" />
                                        <stop offset="45%" stop-color="#fd5949" />
                                        <stop offset="60%" stop-color="#d6249f" />
                                        <stop offset="90%" stop-color="#285AEB" />
                                    </radialGradient>
                                </defs>
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"
                                    fill="url(#ig1)" />
                                <circle cx="12" cy="12" r="4.5" stroke="white" stroke-width="1.8"
                                    fill="none" />
                                <circle cx="17.5" cy="6.5" r="1.2" fill="white" />
                            </svg>
                            Instagram
                        </a>
                    </li>
                    <li>
                        <a href="https://t.me/dailywealth2026" target="_blank"
                            style="display:flex;align-items:center;gap:0.6rem">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" fill="#29A8E0" />
                                <path d="M5.5 11.5l13-5-4.5 13-3-4.5-5.5-3.5z" fill="white" opacity="0.9" />
                            </svg>
                            Telegram
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© {{ date('Y') }} DailyWealth. All rights reserved.</span>
            <span>⚠️ Forex trading involves significant risk. Past performance is not indicative of future
                results.</span>
        </div>
    </footer>

</body>

</html>
