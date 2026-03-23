<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us — InvestX</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#C9A84C;--dark:#0A0C10;--dark3:#181C24;--dark4:#1E2330;--text:#E8EAF0;--muted:#7A8099;--green:#22C55E;--red:#EF4444;--border:rgba(201,168,76,0.18);}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);}
nav{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:1.2rem 3rem;background:rgba(10,12,16,0.9);backdrop-filter:blur(18px);border-bottom:1px solid var(--border);}
.logo{font-family:'Playfair Display',serif;font-size:1.7rem;font-weight:900;color:var(--gold);text-decoration:none;}
.logo span{color:var(--text);}
.container{max-width:900px;margin:0 auto;padding:8rem 2rem 4rem;}
h1{font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:900;margin-bottom:0.5rem;}
.subtitle{color:var(--muted);margin-bottom:3rem;}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;}
.card{background:var(--dark3);border:1px solid var(--border);border-radius:16px;padding:2rem;}
.card h2{font-size:1rem;font-weight:700;color:var(--gold);margin-bottom:1.2rem;}
.contact-item{display:flex;align-items:start;gap:0.8rem;margin-bottom:1.2rem;}
.contact-icon{font-size:1.3rem;margin-top:2px;}
.contact-label{font-size:0.75rem;color:var(--muted);margin-bottom:0.2rem;text-transform:uppercase;letter-spacing:1px;}
.contact-value{font-size:0.92rem;font-weight:600;}
.contact-value a{color:var(--gold);text-decoration:none;}
.hours-row{display:flex;justify-content:space-between;font-size:0.88rem;padding:0.4rem 0;border-bottom:1px solid rgba(255,255,255,0.05);}
.hours-row span:first-child{color:var(--muted);}
.hours-row span:last-child{font-weight:600;}
.form-input{width:100%;background:var(--dark4);border:1px solid var(--border);border-radius:8px;padding:0.75rem 1rem;color:var(--text);font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none;transition:border-color 0.2s;}
.form-input:focus{border-color:var(--gold);}
.form-label{display:block;font-size:0.78rem;color:var(--muted);margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:1px;}
footer{text-align:center;padding:2rem;color:var(--muted);font-size:0.82rem;border-top:1px solid var(--border);margin-top:3rem;}
footer a{color:var(--gold);text-decoration:none;margin:0 0.8rem;}
</style>
</head>
<body>
<nav>
    <a href="{{ route('home') }}" class="logo">Invest<span>X</span></a>
    <a href="{{ route('login') }}" style="color:var(--gold);text-decoration:none;font-size:0.9rem">Login →</a>
</nav>

<div class="container">
    <h1>Contact Us</h1>
    <p class="subtitle">Kisi bhi query ke liye hum yahan hain</p>

    <div class="grid">
        <div class="card">
            <h2>📞 Contact Details</h2>
            {{-- <div class="contact-item">
                <span class="contact-icon">📧</span>
                <div>
                    <div class="contact-label">Email</div>
                    <div class="contact-value"><a href="mailto:[EMAIL]">[EMAIL]</a></div>
                </div>
            </div>
            <div class="contact-item">
                <span class="contact-icon">📱</span>
                <div>
                    <div class="contact-label">Phone / WhatsApp</div>
                    <div class="contact-value"><a href="tel:[PHONE]">[PHONE]</a></div>
                </div>
            </div>
            <div class="contact-item">
                <span class="contact-icon">🏢</span>
                <div>
                    <div class="contact-label">Address</div>
                    <div class="contact-value">[FULL ADDRESS]</div>
                </div>
            </div> --}}
            <div class="contact-item">
                <span class="contact-icon">✈️</span>
                <div>
                    <div class="contact-label">Telegram</div>
                    <div class="contact-value"><a href="https://t.me/dailywealth2026">t.me/dailywealth2026</a></div>
                </div>
            </div>
            <div class="contact-item">
                <span class="contact-icon">📸</span>
                <div>
                    <div class="contact-label">Instagram</div>
                    <div class="contact-value"><a href="https://instagram.com/dailywealth_2026">@dailywealth_2026</a></div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>🕐 Support Hours</h2>
            <div class="hours-row"><span>Monday</span><span>10 AM – 6 PM</span></div>
            <div class="hours-row"><span>Tuesday</span><span>10 AM – 6 PM</span></div>
            <div class="hours-row"><span>Wednesday</span><span>10 AM – 6 PM</span></div>
            <div class="hours-row"><span>Thursday</span><span>10 AM – 6 PM</span></div>
            <div class="hours-row"><span>Friday</span><span>10 AM – 6 PM</span></div>
            <div class="hours-row"><span>Saturday</span><span>10 AM – 2 PM</span></div>
            <div class="hours-row"><span>Sunday</span><span>Closed</span></div>
            <div style="margin-top:1.2rem;padding:0.8rem;background:rgba(201,168,76,0.08);border-radius:8px;font-size:0.82rem;color:var(--muted)">
                ⚡ Emergency support ke liye WhatsApp karein
            </div>
        </div>
    </div>

    {{-- Contact Form --}}
    <div class="card" style="margin-top:1.5rem">
        <h2>📬 Message Bhejein</h2>

        @if(session('success'))
        <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);border-radius:10px;padding:1rem;margin-bottom:1.5rem;color:var(--green);font-size:0.9rem">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);border-radius:10px;padding:1rem;margin-bottom:1.5rem;color:var(--red);font-size:0.9rem">
            ❌ {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('contact.send') }}" method="POST">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
                <div>
                    <label class="form-label">Aapka Naam *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-input" placeholder="Full Name" required>
                </div>
                <div>
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-input" placeholder="aap@email.com" required>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="form-input" placeholder="10-digit number">
                </div>
                <div>
                    <label class="form-label">Transaction / Payment ID</label>
                    <input type="text" name="reference_id" value="{{ old('reference_id') }}"
                           class="form-input" placeholder="Optional — agar applicable ho">
                </div>
            </div>

            <div style="margin-bottom:1rem">
                <label class="form-label">Issue Type *</label>
                <select name="issue_type" class="form-input" required>
                    <option value="">-- Select Issue --</option>
                    <option value="withdrawal" {{ old('issue_type') == 'withdrawal' ? 'selected' : '' }}>💸 Withdrawal Issue</option>
                    <option value="kyc"        {{ old('issue_type') == 'kyc'        ? 'selected' : '' }}>🪪 KYC Issue</option>
                    <option value="payment"    {{ old('issue_type') == 'payment'    ? 'selected' : '' }}>💳 Payment Issue</option>
                    <option value="other"      {{ old('issue_type') == 'other'      ? 'selected' : '' }}>❓ Other</option>
                </select>
            </div>

            <div style="margin-bottom:1.5rem">
                <label class="form-label">Message *</label>
                <textarea name="message" rows="5" class="form-input"
                          style="resize:vertical;min-height:120px"
                          placeholder="Aapka issue detail mein likhein..." required>{{ old('message') }}</textarea>
            </div>

            <button type="submit"
                    style="background:var(--gold);color:#0A0C10;border:none;border-radius:8px;padding:0.85rem 2rem;font-size:0.95rem;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;width:100%;transition:all 0.2s"
                    onmouseover="this.style.background='#E8C97A'"
                    onmouseout="this.style.background='var(--gold)'">
                📨 Request Submit Karein →
            </button>
        </form>
    </div>
</div>

<footer>
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('about') }}">About</a>
    <a href="{{ route('privacy') }}">Privacy</a>
    <a href="{{ route('refund') }}">Refund</a>
    <a href="{{ route('grievance') }}">Grievance</a>
    <br><br>© {{ date('Y') }} InvestX. All rights reserved.
</footer>
</body>
</html>