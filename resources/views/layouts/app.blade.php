<!DOCTYPE html>
<html lang="hi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) — DailyWealth</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold: #C9A84C; --gold-light: #E8C97A;
            --dark: #0A0C10; --dark2: #111318; --dark3: #181C24; --dark4: #1E2330;
            --text: #E8EAF0; --muted: #7A8099;
            --green: #22C55E; --red: #EF4444; --blue: #3B82F6;
            --border: rgba(201,168,76,0.18);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DM Sans',sans-serif; background:var(--dark); color:var(--text); min-height:100vh; display:flex; }

        /* SIDEBAR */
        .sidebar {
            width:250px; flex-shrink:0;
            background:var(--dark2);
            border-right:1px solid var(--border);
            display:flex; flex-direction:column;
            position:fixed; top:0; left:0; bottom:0; z-index:50;
        }
        .sidebar-logo { padding:1.8rem 1.5rem; font-family:'Playfair Display',serif; font-size:1.5rem; font-weight:900; color:var(--gold); border-bottom:1px solid var(--border); }
        .sidebar-logo span { color:var(--text); }
        .sidebar-user { padding:1.2rem 1.5rem; border-bottom:1px solid var(--border); }
        .sidebar-user .name { font-size:0.9rem; font-weight:600; }
        .sidebar-user .email { font-size:0.75rem; color:var(--muted); margin-top:2px; }
        .kyc-badge { display:inline-block; font-size:0.65rem; font-weight:600; padding:2px 8px; border-radius:20px; margin-top:5px; text-transform:uppercase; }
        .kyc-badge.verified  { background:rgba(34,197,94,0.15); color:var(--green); }
        .kyc-badge.pending   { background:rgba(201,168,76,0.15); color:var(--gold); }
        .kyc-badge.submitted { background:rgba(59,130,246,0.15); color:var(--blue); }
        .kyc-badge.rejected  { background:rgba(239,68,68,0.15); color:var(--red); }

        .sidebar-nav { flex:1; padding:1rem 0; overflow-y:auto; }
        .nav-section { padding:0.6rem 1.5rem 0.3rem; font-size:0.65rem; font-weight:600; letter-spacing:1.5px; text-transform:uppercase; color:var(--muted); }
        .sidebar-nav a { display:flex; align-items:center; gap:0.75rem; padding:0.65rem 1.5rem; color:var(--muted); text-decoration:none; font-size:0.88rem; font-weight:500; transition:all 0.2s; border-left:2px solid transparent; }
        .sidebar-nav a:hover, .sidebar-nav a.active { color:var(--gold); background:rgba(201,168,76,0.06); border-left-color:var(--gold); }
        .sidebar-nav a .icon { font-size:1rem; width:20px; text-align:center; }
        .sidebar-bottom { padding:1rem 1.5rem; border-top:1px solid var(--border); }
        .sidebar-bottom form button { width:100%; padding:0.6rem; background:rgba(239,68,68,0.1); color:var(--red); border:1px solid rgba(239,68,68,0.2); border-radius:8px; font-size:0.85rem; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif; transition:all 0.2s; }
        .sidebar-bottom form button:hover { background:rgba(239,68,68,0.2); }

        /* MAIN */
        .main { margin-left:250px; flex:1; display:flex; flex-direction:column; min-height:100vh; }
        .topbar { background:var(--dark2); border-bottom:1px solid var(--border); padding:1rem 2rem; display:flex; align-items:center; justify-content:space-between; }
        .topbar h1 { font-size:1.2rem; font-weight:600; }
        .topbar-right { display:flex; align-items:center; gap:1rem; }
        .balance-pill { background:rgba(201,168,76,0.1); border:1px solid var(--border); border-radius:50px; padding:0.35rem 1rem; font-size:0.82rem; color:var(--gold); font-weight:600; }
        .content { padding:2rem; flex:1; }

        /* ALERTS */
        .alert { padding:0.85rem 1.2rem; border-radius:10px; margin-bottom:1.5rem; font-size:0.88rem; display:flex; align-items:center; gap:0.6rem; }
        .alert.success { background:rgba(34,197,94,0.12); border:1px solid rgba(34,197,94,0.3); color:var(--green); }
        .alert.error   { background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.3); color:var(--red); }
        .alert.warning { background:rgba(201,168,76,0.12); border:1px solid var(--border); color:var(--gold); }
        .alert.info    { background:rgba(59,130,246,0.12); border:1px solid rgba(59,130,246,0.3); color:var(--blue); }

        /* CARDS */
        .card { background:var(--dark3); border:1px solid var(--border); border-radius:16px; padding:1.5rem; }
        .card-title { font-size:1rem; font-weight:600; margin-bottom:1.2rem; }

        /* STAT CARDS */
        .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:2rem; }
        .stat-card { background:var(--dark3); border:1px solid var(--border); border-radius:14px; padding:1.3rem; transition:transform 0.2s; }
        .stat-card:hover { transform:translateY(-3px); }
        .stat-card .label { font-size:0.75rem; color:var(--muted); margin-bottom:0.5rem; }
        .stat-card .value { font-family:'Playfair Display',serif; font-size:1.6rem; font-weight:700; }
        .stat-card .change { font-size:0.72rem; margin-top:0.3rem; }
        .stat-card .change.up { color:var(--green); }
        .stat-card .change.dn { color:var(--red); }

        /* TABLE */
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; font-size:0.85rem; }
        th { padding:0.7rem 1rem; text-align:left; font-size:0.72rem; font-weight:600; letter-spacing:1px; text-transform:uppercase; color:var(--muted); border-bottom:1px solid var(--border); }
        td { padding:0.85rem 1rem; border-bottom:1px solid rgba(255,255,255,0.04); color:var(--text); }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:rgba(255,255,255,0.02); }

        /* BADGES */
        .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:0.7rem; font-weight:600; text-transform:uppercase; }
        .badge.active     { background:rgba(34,197,94,0.15); color:var(--green); }
        .badge.matured    { background:rgba(201,168,76,0.15); color:var(--gold); }
        .badge.withdrawn  { background:rgba(107,114,128,0.15); color:var(--muted); }
        .badge.pending    { background:rgba(251,191,36,0.15); color:#FBB924; }
        .badge.completed  { background:rgba(34,197,94,0.15); color:var(--green); }
        .badge.failed     { background:rgba(239,68,68,0.15); color:var(--red); }
        .badge.deposit    { background:rgba(59,130,246,0.15); color:var(--blue); }
        .badge.profit     { background:rgba(34,197,94,0.15); color:var(--green); }
        .badge.commission { background:rgba(239,68,68,0.15); color:var(--red); }
        .badge.withdrawal { background:rgba(251,191,36,0.15); color:#FBB924; }

        /* FORMS */
        .form-group { margin-bottom:1.2rem; }
        .form-label { display:block; font-size:0.82rem; color:var(--muted); margin-bottom:0.5rem; font-weight:500; }
        .form-control { width:100%; background:var(--dark4); border:1px solid var(--border); border-radius:8px; padding:0.75rem 1rem; color:var(--text); font-size:0.9rem; font-family:'DM Sans',sans-serif; outline:none; transition:border-color 0.2s; }
        .form-control:focus { border-color:var(--gold); }
        .form-control.error { border-color:var(--red); }
        .form-error { font-size:0.78rem; color:var(--red); margin-top:0.3rem; }

        /* BUTTONS */
        .btn { padding:0.7rem 1.5rem; border-radius:8px; font-size:0.88rem; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif; border:none; transition:all 0.2s; display:inline-flex; align-items:center; gap:0.5rem; text-decoration:none; }
        .btn-gold { background:var(--gold); color:#0A0C10; }
        .btn-gold:hover { background:var(--gold-light); transform:translateY(-1px); }
        .btn-outline { background:transparent; border:1px solid var(--border); color:var(--text); }
        .btn-outline:hover { border-color:var(--gold); color:var(--gold); }
        .btn-danger { background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); color:var(--red); }
        .btn-danger:hover { background:rgba(239,68,68,0.2); }
        .btn-sm { padding:0.4rem 0.9rem; font-size:0.8rem; }
        .btn-block { width:100%; justify-content:center; }

        /* PROGRESS BAR */
        .progress { height:6px; background:var(--dark4); border-radius:3px; overflow:hidden; }
        .progress-bar { height:100%; background:linear-gradient(90deg,var(--gold),var(--gold-light)); border-radius:3px; transition:width 0.5s; }

        /* GRID */
        .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; }
        .grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.5rem; }

        /* HAMBURGER */
        .hamburger { display:none; align-items:center; justify-content:center; width:38px; height:38px; background:var(--dark4); border:1px solid var(--border); border-radius:8px; cursor:pointer; font-size:1.2rem; flex-shrink:0; }

        /* OVERLAY */
        .overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:149; }
        .overlay.show { display:block; }

        @media(max-width:1024px) {
            .stats-grid { grid-template-columns:repeat(2,1fr); }
            .grid-2 { grid-template-columns:1fr; }
        }

        @media(max-width:768px) {
            .sidebar { transform:translateX(-100%); transition:transform 0.3s ease; z-index:150; }
            .sidebar.open { transform:translateX(0); }
            .main { margin-left:0; }
            .stats-grid { grid-template-columns:1fr 1fr; }
            .hamburger { display:flex; }
            .topbar { gap:1rem; }
            .content { padding:1rem; }
            .grid-3 { grid-template-columns:1fr; }
        }
    </style>
    @stack('styles')
</head>

<body>

<!-- Overlay -->
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">Daily<span>Wealth</span></div>

    <div class="sidebar-user">
        <div class="name">{{ Auth::user()->name }}</div>
        <div class="email">{{ Auth::user()->email }}</div>
        <span class="kyc-badge {{ Auth::user()->kyc_status }}">
            {{ ucfirst(Auth::user()->kyc_status) }}
        </span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="icon">📊</span> Dashboard
        </a>
        <a href="{{ route('plans') }}" class="{{ request()->routeIs('plans') ? 'active' : '' }}">
            <span class="icon">📈</span> Trading Plans
        </a>
        <a href="{{ route('investments.my') }}" class="{{ request()->routeIs('investments.*') ? 'active' : '' }}">
            <span class="icon">💼</span> My Participations
        </a>
        <a href="{{ route('withdrawals.index') }}" class="{{ request()->routeIs('withdrawals.*') ? 'active' : '' }}">
            <span class="icon">💸</span> Withdrawals
        </a>
        <a href="{{ route('wallet.index') }}" class="{{ request()->routeIs('wallet.*') ? 'active' : '' }}">
            <span class="icon">👛</span> Wallet
        </a>

        <div class="nav-section">Account</div>
        <a href="{{ route('kyc') }}" class="{{ request()->routeIs('kyc') ? 'active' : '' }}">
            <span class="icon">🪪</span> KYC Verification
        </a>

        @if(Auth::user()->is_admin)
        <div class="nav-section">Admin</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="icon">⚙️</span> Admin Panel
        </a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="icon">👥</span> Users
        </a>
        <a href="{{ route('admin.withdrawals.index') }}" class="{{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">
            <span class="icon">💸</span> Withdrawals
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <span><span class="icon">⭐</span> Reviews</span>
            @php $pendingReviews = \App\Models\Review::where('status','pending')->count(); @endphp
            @if($pendingReviews > 0)
            <span style="margin-left:auto;background:var(--gold);color:#0A0C10;font-size:0.65rem;font-weight:700;padding:1px 7px;border-radius:50px">
                {{ $pendingReviews }}
            </span>
            @endif
        </a>
        @endif
    </nav>

    <div style="padding:0.8rem 1.5rem;border-bottom:1px solid var(--border);display:flex;gap:0.8rem">
        <a href="https://www.instagram.com/dailywealth_2026" target="_blank"
           style="flex:1;text-align:center;padding:0.6rem;background:var(--dark4);border:1px solid var(--border);border-radius:8px;text-decoration:none;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:0.5rem;font-size:0.78rem;color:var(--muted)"
           onmouseover="this.style.borderColor='#d6249f';this.style.color='#d6249f'"
           onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                <defs>
                    <radialGradient id="ig2" cx="30%" cy="107%" r="150%">
                        <stop offset="0%" stop-color="#fdf497"/>
                        <stop offset="5%" stop-color="#fdf497"/>
                        <stop offset="45%" stop-color="#fd5949"/>
                        <stop offset="60%" stop-color="#d6249f"/>
                        <stop offset="90%" stop-color="#285AEB"/>
                    </radialGradient>
                </defs>
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="url(#ig2)"/>
                <circle cx="12" cy="12" r="4.5" stroke="white" stroke-width="1.8" fill="none"/>
                <circle cx="17.5" cy="6.5" r="1.2" fill="white"/>
            </svg>
            Instagram
        </a>
        <a href="https://t.me/dailywealth2026" target="_blank"
           style="flex:1;text-align:center;padding:0.6rem;background:var(--dark4);border:1px solid var(--border);border-radius:8px;text-decoration:none;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:0.5rem;font-size:0.78rem;color:var(--muted)"
           onmouseover="this.style.borderColor='#29A8E0';this.style.color='#29A8E0'"
           onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" fill="#29A8E0"/>
                <path d="M5.5 11.8l13-5.3-4.8 13.5-2.7-5-5.5-3.2z" fill="white"/>
                <path d="M9 14.5l.5-3.5 6-5.5" stroke="#29A8E0" stroke-width="1" fill="none"/>
            </svg>
            Telegram
        </a>
    </div>

    <div class="sidebar-bottom">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">🚪 Logout</button>
        </form>
    </div>
</aside>

<!-- MAIN -->
<div class="main">
    <div class="topbar">
        <div class="hamburger" onclick="toggleSidebar()">☰</div>
        <h1>@yield('page-title', 'Dashboard')</h1>
        <div class="topbar-right">
            <div class="balance-pill">
                💰 ₹{{ number_format(Auth::user()->wallet_balance, 2) }}
            </div>
            <div style="font-size:0.82rem;color:var(--muted)">
                Hi, {{ Str::words(Auth::user()->name, 1, '') }} 👋
            </div>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert error">❌ {{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert warning">⚠️ {{ session('warning') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('show');
}
</script>

@stack('scripts')
</body>
</html>