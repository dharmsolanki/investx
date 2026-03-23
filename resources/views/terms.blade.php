<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Terms & Conditions — InvestX</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --gold: #C9A84C; --gold-light: #E8C97A;
    --dark: #0A0C10; --dark2: #111318; --dark3: #181C24; --dark4: #1E2330;
    --text: #E8EAF0; --muted: #7A8099;
    --green: #22C55E; --red: #EF4444;
    --border: rgba(201,168,76,0.18);
  }
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'DM Sans',sans-serif; background:var(--dark); color:var(--text); }

  nav {
    display:flex; align-items:center; justify-content:space-between;
    padding:1.2rem 3rem;
    background:rgba(10,12,16,0.95);
    border-bottom:1px solid var(--border);
    position:sticky; top:0; z-index:10;
  }
  .logo { font-family:'Playfair Display',serif; font-size:1.5rem; font-weight:900; color:var(--gold); text-decoration:none; }
  .logo span { color:var(--text); }
  .btn-back {
    padding:0.5rem 1.2rem; border:1px solid var(--border);
    background:transparent; color:var(--text); border-radius:6px;
    font-size:0.85rem; cursor:pointer; font-family:'DM Sans',sans-serif;
    text-decoration:none; transition:all 0.2s;
  }
  .btn-back:hover { border-color:var(--gold); color:var(--gold); }

  .container { max-width:780px; margin:0 auto; padding:3rem 2rem 5rem; }

  .page-title {
    font-family:'Playfair Display',serif;
    font-size:2.5rem; font-weight:900;
    color:var(--gold); margin-bottom:0.5rem;
  }
  .last-updated {
    font-size:0.8rem; color:var(--muted); margin-bottom:2.5rem;
  }

  /* Risk disclaimer box */
  .risk-box {
    background:rgba(239,68,68,0.08);
    border:1px solid rgba(239,68,68,0.25);
    border-radius:12px; padding:1.5rem;
    margin-bottom:2.5rem;
  }
  .risk-box h3 { color:var(--red); font-size:1rem; margin-bottom:0.8rem; }
  .risk-box p { font-size:0.88rem; color:var(--muted); line-height:1.8; }

  /* Sections */
  .section { margin-bottom:2.5rem; }
  .section h2 {
    font-size:1.1rem; font-weight:700;
    color:var(--gold); margin-bottom:1rem;
    padding-bottom:0.5rem;
    border-bottom:1px solid var(--border);
  }
  .section p { font-size:0.88rem; color:var(--muted); line-height:1.9; margin-bottom:0.8rem; }
  .section ul {
    padding-left:1.4rem; display:flex;
    flex-direction:column; gap:0.5rem;
    font-size:0.88rem; color:var(--muted); line-height:1.7;
  }
  .section ul li strong { color:var(--text); }

  /* Footer note */
  .footer-note {
    background:var(--dark3); border:1px solid var(--border);
    border-radius:12px; padding:1.5rem; margin-top:3rem;
    font-size:0.82rem; color:var(--muted); line-height:1.8;
    text-align:center;
  }
  .footer-note strong { color:var(--text); }
</style>
</head>
<body>

<nav>
  <a href="{{ url('/') }}" class="logo">Invest<span>X</span></a>
  <a href="{{ url('/') }}" class="btn-back">← Wapas Jaayein</a>
</nav>

<div class="container">

    <div class="page-title">Terms & Conditions</div>
    <div class="last-updated">Last Updated: {{ date('d F Y') }} &nbsp;|&nbsp; Please carefully read before participating.</div>

    {{-- Risk Disclaimer --}}
    <div class="risk-box">
        <h3>⚠️ Important Risk Disclaimer</h3>
        <p>
            Forex trading mein <strong style="color:var(--red)">substantial financial risk</strong> hota hai.
            Aap apni poori capital kho sakte hain. Yahan dikhaye gaye returns
            <strong style="color:var(--red)">past performance par based hain</strong> aur future returns ki
            koi guarantee nahi hai. Sirf woh funds use karein jo aap khone ke liye
            afford kar sakein. Agar aap forex trading ke risks se familiar nahi hain
            to participate karne se pehle financial advisor se salah lein.
        </p>
    </div>

    {{-- 1. Agreement --}}
    <div class="section">
        <h2>1. Agreement to Terms</h2>
        <p>
            InvestX platform par register karke aur participate karke aap inn Terms & Conditions ko
            poori tarah padhne, samajhne aur agree karne ki confirmation dete hain.
            Agar aap kisi bhi term se asahmati rakhte hain to platform use nahi karein.
        </p>
    </div>

    {{-- 2. Nature of Service --}}
    <div class="section">
        <h2>2. Service Ki Nature</h2>
        <p>InvestX ek <strong style="color:var(--text)">Forex Trading Profit Sharing Platform</strong> hai.</p>
        <ul>
            <li>Aap hamare experienced traders ke saath apna fund <strong>contribute</strong> karte hain.</li>
            <li>Hamari team aapka fund forex markets mein professionally trade karti hai.</li>
            <li>Trading performance ke basis par returns share kiye jaate hain.</li>
            <li><strong>Yeh koi guaranteed return scheme nahi hai.</strong></li>
            <li>Returns market conditions par depend karte hain aur variable hain.</li>
        </ul>
    </div>

    {{-- 3. Returns & Performance --}}
    <div class="section">
        <h2>3. Returns Aur Performance</h2>
        <ul>
            <li>Platform par dikhaye gaye returns <strong>expected/historical hain, guaranteed nahi.</strong></li>
            <li>Past performance future results ki guarantee nahi deta.</li>
            <li>Forex market volatile hoti hai — returns positive ya negative dono ho sakte hain.</li>
            <li>Hamara management fee sirf net returns par lagta hai, principal par nahi.</li>
            <li>Exceptional market conditions mein returns expectations se kam ya zyada ho sakte hain.</li>
        </ul>
    </div>

    {{-- 4. Management Fee --}}
    <div class="section">
        <h2>4. Management Fee Structure</h2>
        <ul>
            <li><strong>Plan 1:</strong> 20% of net returns (principal pe nahi)</li>
            <li><strong>Plan 2:</strong> 17% of net returns</li>
            <li><strong>Plan 3:</strong> 16% of net returns</li>
            <li><strong>Plan 4:</strong> 15% of net returns</li>
            <li>Fee sirf tab deduct hoti hai jab positive returns aate hain.</li>
            <li>Loss ki situation mein koi fee charge nahi ki jaayegi.</li>
        </ul>
    </div>

    {{-- 5. Lock-in Period --}}
    <div class="section">
        <h2>5. Lock-in Period</h2>
        <ul>
            <li>Koi lock-in period nahi hai — aap kab bhi withdrawal request kar sakte hain.</li>
            <li>Lock-in period se pehle early exit request par <strong>5% early exit fee</strong> lagegi.</li>
            <li>Early exit sirf admin approval ke baad process hogi.</li>
            <li>Withdrawal request 24 ghante mein process hogi.</li>
        </ul>
    </div>

    {{-- 6. KYC --}}
    <div class="section">
        <h2>6. KYC Verification</h2>
        <ul>
            <li>Participate karne ke liye KYC verification zaroori hai.</li>
            <li>Aapko valid PAN card, Aadhaar card aur bank details submit karni hongi.</li>
            <li>Galat documents submit karna account suspension ka karan ban sakta hai.</li>
            <li>KYC documents sirf verification ke liye use kiye jaate hain — kisi third party ke saath share nahi kiye jaate.</li>
        </ul>
    </div>

    {{-- 7. Withdrawals --}}
    <div class="section">
        <h2>7. Withdrawal Policy</h2>
        <ul>
            <li>Withdrawal kab bhi request ki ja sakti hai — pro-rated profit milega.</li>
            <li>Withdrawal aapke KYC-verified bank account mein ki jaayegi.</li>
            <li>Processing time: <strong>24 ghante</strong> (banking days).</li>
            <li>Minimum withdrawal amount ₹500 hai.</li>
            <li>Suspicious activity ke case mein withdrawal hold ki ja sakti hai.</li>
        </ul>
    </div>

    {{-- 8. Prohibited Activities --}}
    <div class="section">
        <h2>8. Prohibited Activities</h2>
        <ul>
            <li>Multiple accounts banana.</li>
            <li>Fake ya forged documents submit karna.</li>
            <li>Platform ko money laundering ke liye use karna.</li>
            <li>Doosre users ko mislead karna ya false claims karna.</li>
            <li>Platform ke systems ko hack ya manipulate karne ki koshish karna.</li>
        </ul>
        <p style="margin-top:0.8rem">Koi bhi prohibited activity par turant account suspend ho sakta hai aur legal action liya ja sakta hai.</p>
    </div>

    {{-- 9. Liability --}}
    <div class="section">
        <h2>9. Limitation of Liability</h2>
        <ul>
            <li>InvestX <strong>forex market losses ke liye liable nahi hai.</strong></li>
            <li>Force majeure events (war, natural disaster, regulatory changes) ke karan losses ke liye company liable nahi hai.</li>
            <li>Aap apne funds participate karne ka full risk khud lete hain.</li>
            <li>Maximum liability, agar koi ho, sirf aapke contributed amount tak seimit hogi.</li>
        </ul>
    </div>

    {{-- 10. Privacy --}}
    <div class="section">
        <h2>10. Privacy & Data Protection</h2>
        <ul>
            <li>Aapki personal information encrypted aur secure rakhi jaati hai.</li>
            <li>KYC documents kisi third party ke saath share nahi kiye jaate.</li>
            <li>Payment details Razorpay ke secure servers par process hote hain.</li>
            <li>Aap kabhi bhi apna account delete karne ka request kar sakte hain.</li>
        </ul>
    </div>

    {{-- 11. Changes --}}
    <div class="section">
        <h2>11. Terms Mein Changes</h2>
        <p>
            InvestX in terms ko kabhi bhi update karne ka adhikar rakhta hai.
            Material changes ke case mein registered email par notification bheji jaayegi.
            Platform ka continued use updated terms ki acceptance maana jaayega.
        </p>
    </div>

    {{-- 12. Contact --}}
    <div class="section">
        <h2>12. Contact Us</h2>
        <p>Koi bhi sawal ya concern ke liye:</p>
        <ul>
            <li><strong>Email:</strong> jayrajmchauhan6271@gmail.com</li>
            <li><strong>Support Hours:</strong> Monday–Saturday, 10 AM – 6 PM IST</li>
        </ul>
    </div>

    <div class="footer-note">
        <strong>⚠️ Final Reminder:</strong> Forex trading mein significant risk hota hai.
        Yeh platform sabke liye suitable nahi hai. Participate karne se pehle
        apni financial situation aur risk tolerance ko samjhein.
        <br><br>
        Platform par participate karke aap confirm karte hain ki aapne yeh
        Terms & Conditions poori tarah padhe aur samjhe hain.
    </div>

</div>

</body>
</html>