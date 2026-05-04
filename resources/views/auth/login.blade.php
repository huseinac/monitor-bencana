<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login — {{ env('APP_NAME') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>

    <style>
        :root {
            --primary:    #1a4b8c;
            --primary-dk: #12336a;
            --primary-lt: #e8f0fb;
            --accent:     #f0a500;
            --text:       #1e293b;
            --muted:      #64748b;
            --border:     #d1dce8;
            --card:       #ffffff;
            --danger:     #dc3545;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: var(--primary-dk);
            overflow: hidden;
        }

        /* ── LEFT PANEL ──────────────────────────────── */
        .left-panel {
            flex: 1.1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 52px;
            overflow: hidden;
            min-height: 100vh;
        }

        /* deep navy-to-blue gradient base */
        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 70% at 20% 110%, rgba(240,165,0,.18) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% -10%, rgba(26,75,140,.9) 0%, transparent 70%),
                linear-gradient(155deg, #0d2547 0%, #1a4b8c 55%, #0f3060 100%);
            z-index: 0;
        }

        /* animated geo rings */
        .geo-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.07);
            animation: ringPulse 8s ease-in-out infinite;
        }
        .geo-ring:nth-child(1) { width: 520px; height: 520px; top: -160px; right: -160px; animation-delay: 0s; }
        .geo-ring:nth-child(2) { width: 360px; height: 360px; top: -80px;  right: -80px;  animation-delay: 1.5s; border-color: rgba(240,165,0,.1); }
        .geo-ring:nth-child(3) { width: 700px; height: 700px; bottom: -300px; left: -200px; animation-delay: 3s; }
        .geo-ring:nth-child(4) { width: 200px; height: 200px; bottom: 120px; right: 60px; animation-delay: 2s; border-color: rgba(240,165,0,.08); }

        @keyframes ringPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50%       { transform: scale(1.04); opacity: .6; }
        }

        /* map dot grid */
        .dot-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,.12) 1px, transparent 1px);
            background-size: 32px 32px;
            z-index: 0;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%);
        }

        /* floating disaster icons */
        .float-icons { position: absolute; inset: 0; z-index: 1; pointer-events: none; }
        .fi {
            position: absolute;
            font-size: 1.6rem;
            opacity: .12;
            animation: floatUp 12s ease-in-out infinite;
        }
        .fi:nth-child(1)  { left: 12%; top: 18%; font-size: 1.2rem; animation-delay: 0s;   animation-duration: 10s; }
        .fi:nth-child(2)  { left: 72%; top: 12%; font-size: 2rem;   animation-delay: 2s;   animation-duration: 14s; }
        .fi:nth-child(3)  { left: 35%; top: 65%; font-size: 1rem;   animation-delay: 4s;   animation-duration: 11s; }
        .fi:nth-child(4)  { left: 80%; top: 58%; font-size: 1.4rem; animation-delay: 1s;   animation-duration: 13s; }
        .fi:nth-child(5)  { left: 55%; top: 82%; font-size: .9rem;  animation-delay: 6s;   animation-duration: 9s;  }
        .fi:nth-child(6)  { left: 8%;  top: 78%; font-size: 1.6rem; animation-delay: 3s;   animation-duration: 15s; }

        @keyframes floatUp {
            0%,100% { transform: translateY(0) rotate(0deg); }
            33%      { transform: translateY(-14px) rotate(4deg); }
            66%      { transform: translateY(8px) rotate(-3deg); }
        }

        /* left content */
        .left-content { position: relative; z-index: 2; }

        .brand-logo {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 64px;
            animation: fadeDown .7s ease both;
        }
        .brand-logo img {
            width: 42px; height: 42px; border-radius: 10px;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,.3));
        }
        .brand-logo-text strong {
            display: block;
            font-size: .9rem; font-weight: 700; color: #fff;
            letter-spacing: .4px;
        }
        .brand-logo-text span {
            font-size: .65rem; color: rgba(255,255,255,.55);
            font-weight: 500; letter-spacing: .5px;
        }

        .left-headline {
            animation: fadeDown .7s .15s ease both;
        }
        .left-headline h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 2.8rem;
            color: #fff;
            line-height: 1.18;
            margin-bottom: 18px;
            letter-spacing: -.5px;
        }
        .left-headline h1 em {
            font-style: normal;
            color: var(--accent);
        }
        .left-headline p {
            font-size: .87rem;
            color: rgba(255,255,255,.62);
            line-height: 1.7;
            max-width: 380px;
        }

        /* region pills */
        .region-pills {
            display: flex; gap: 8px; flex-wrap: wrap;
            margin-top: 40px;
            animation: fadeDown .7s .3s ease both;
        }
        .region-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 20px;
            padding: 5px 14px;
            font-size: .75rem;
            color: rgba(255,255,255,.75);
            font-weight: 500;
            backdrop-filter: blur(6px);
        }
        .region-pill i { color: var(--accent); font-size: .8rem; }

        /* bottom stat strip */
        .left-stats {
            position: relative; z-index: 2;
            display: flex; gap: 32px;
            animation: fadeUp .7s .45s ease both;
        }
        .lst-item {}
        .lst-val {
            font-family: 'DM Serif Display', serif;
            font-size: 1.9rem; color: #fff; line-height: 1;
        }
        .lst-val span { color: var(--accent); }
        .lst-label {
            font-size: .7rem; color: rgba(255,255,255,.5);
            margin-top: 3px; font-weight: 500; letter-spacing: .4px;
            text-transform: uppercase;
        }
        .lst-divider {
            width: 1px; background: rgba(255,255,255,.12); align-self: stretch;
        }

        /* ── RIGHT PANEL ─────────────────────────────── */
        .right-panel {
            width: 460px;
            flex-shrink: 0;
            background: var(--card);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 52px 48px;
            position: relative;
            overflow-y: auto;
            min-height: 100vh;
            box-shadow: -20px 0 60px rgba(0,0,0,.25);
        }

        /* subtle top accent line */
        .right-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
        }

        /* ── FORM ────────────────────────────────────── */
        .login-header { margin-bottom: 36px; animation: fadeDown .6s .1s ease both; }
        .login-header .welcome {
            font-size: .75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.5px;
            color: var(--accent);
            margin-bottom: 8px;
        }
        .login-header h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.85rem;
            color: var(--text);
            line-height: 1.2;
            margin-bottom: 8px;
            letter-spacing: -.3px;
        }
        .login-header p {
            font-size: .82rem; color: var(--muted); line-height: 1.6;
        }

        .form-group { margin-bottom: 18px; animation: fadeUp .5s ease both; }
        .form-group:nth-child(1) { animation-delay: .2s; }
        .form-group:nth-child(2) { animation-delay: .3s; }
        .form-group:nth-child(3) { animation-delay: .4s; }

        .form-label {
            font-size: .78rem; font-weight: 600;
            color: var(--text); margin-bottom: 6px;
            display: block;
        }

        /* floating label input */
        .input-wrap {
            position: relative;
        }
        .input-wrap .input-icon {
            position: absolute; left: 13px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: .9rem;
            pointer-events: none;
            transition: color .2s;
        }
        .input-wrap input {
            width: 100%;
            padding: 10px 42px 10px 38px;
            font-size: .85rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            background: #f8fafd;
            transition: border-color .2s, background .2s, box-shadow .2s;
            height: 44px;
        }
        .input-wrap input:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26,75,140,.1);
        }
        .input-wrap input:focus + .input-icon,
        .input-wrap input:focus ~ .input-icon {
            color: var(--primary);
        }
        .input-wrap input.is-invalid {
            border-color: var(--danger);
            background: #fff8f8;
        }
        .input-wrap input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220,53,69,.1);
        }

        /* pw toggle */
        .pw-btn {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--muted); font-size: .9rem;
            cursor: pointer; padding: 2px;
            transition: color .15s;
        }
        .pw-btn:hover { color: var(--primary); }
        .pw-btn:focus { outline: none; }

        /* error msg */
        .field-error {
            font-size: .72rem; color: var(--danger);
            margin-top: 5px;
            align-items: center; gap: 4px;
        }
        .field-error.show { display: flex; }

        /* remember + forgot row */
        .form-meta {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            animation: fadeUp .5s .45s ease both;
        }
        .form-check-label { font-size: .8rem; color: var(--muted); cursor: pointer; }
        .form-check-input:checked { background-color: var(--primary); border-color: var(--primary); }
        .form-check-input:focus { box-shadow: 0 0 0 2px rgba(26,75,140,.15); }
        .link-forgot {
            font-size: .8rem; font-weight: 600;
            color: var(--primary); text-decoration: none;
            transition: color .15s;
        }
        .link-forgot:hover { color: var(--accent); }

        /* submit button */
        .btn-login {
            width: 100%;
            height: 46px;
            background: var(--primary);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .88rem;
            font-weight: 700;
            letter-spacing: .3px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background .2s, transform .15s, box-shadow .2s;
            animation: fadeUp .5s .5s ease both;
            box-shadow: 0 4px 16px rgba(26,75,140,.25);
        }
        .btn-login:hover {
            background: var(--primary-dk);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26,75,140,.35);
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login.loading { pointer-events: none; opacity: .75; }

        /* spinner */
        .spinner {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .6s linear infinite;
            display: none;
        }
        .btn-login.loading .spinner { display: block; }
        .btn-login.loading .btn-label { display: none; }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* alert error */
        .login-alert {
            display: none;
            background: #fff1f2;
            border: 1px solid #fecdd3;
            border-radius: 10px;
            padding: 11px 14px;
            margin-bottom: 20px;
            font-size: .81rem;
            color: #9f1239;
            align-items: center; gap: 9px;
            animation: shake .35s ease;
        }
        .login-alert.show { display: flex; }
        .login-alert i { font-size: 1rem; flex-shrink: 0; }

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%      { transform: translateX(-6px); }
            40%      { transform: translateX(6px); }
            60%      { transform: translateX(-4px); }
            80%      { transform: translateX(4px); }
        }

        /* divider */
        .or-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 22px 0 18px;
            font-size: .73rem; color: var(--muted); font-weight: 600;
            animation: fadeUp .5s .55s ease both;
        }
        .or-divider::before, .or-divider::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }

        /* SSO button */
        .btn-sso {
            width: 100%;
            height: 42px;
            background: #f1f5fb;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .83rem;
            font-weight: 600;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background .15s, border-color .15s;
            animation: fadeUp .5s .6s ease both;
            text-decoration: none;
        }
        .btn-sso:hover { background: var(--primary-lt); border-color: var(--primary); color: var(--primary); }
        .btn-sso img { width: 18px; }

        /* footer note */
        .login-footer {
            margin-top: 32px;
            text-align: center;
            font-size: .72rem;
            color: var(--muted);
            line-height: 1.7;
            animation: fadeUp .5s .65s ease both;
        }
        .login-footer strong { color: var(--text); }

        /* ── KEYFRAMES ───────────────────────────────── */
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ──────────────────────────────── */
        @media (max-width: 768px) {
            body { overflow: auto; }
            .left-panel { display: none; }
            .right-panel {
                width: 100%;
                padding: 40px 28px;
                box-shadow: none;
                min-height: 100vh;
            }
        }
    </style>
</head>
<body>

<div class="left-panel">
    <div class="geo-ring"></div>
    <div class="geo-ring"></div>
    <div class="geo-ring"></div>
    <div class="geo-ring"></div>
    <div class="dot-grid"></div>

    <div class="float-icons">
        <i class="bi bi-tsunami fi"></i>
        <i class="bi bi-fire fi"></i>
        <i class="bi bi-cloud-lightning-rain fi"></i>
        <i class="bi bi-wind fi"></i>
        <i class="bi bi-geo-alt-fill fi"></i>
        <i class="bi bi-exclamation-triangle-fill fi"></i>
    </div>

    <!-- top: brand -->
    <div class="left-content">
        <div class="brand-logo">
            <img src="{{ asset('favicon.png') }}" alt=""/>
            <div class="brand-logo-text">
                <strong>Monitoring Bencana</strong>
                <span>Satgas PRR</span>
            </div>
        </div>

        <div class="left-headline">
            <h1>Pantau & Kendalikan <em>Bencana</em><br/> Aceh, Sumut dan Sumbar<br/>Secara Real-Time</h1>
            <p>Platform monitoring terpadu untuk penanganan bencana di wilayah Aceh, Sumatera Utara, dan Sumatera Barat. Data akurat, respons cepat.</p>
        </div>
    </div>

    <div class="left-stats">
        <div class="lst-item">
            <div class="lst-val">3<span>+</span></div>
            <div class="lst-label">Provinsi Terpantau</div>
        </div>
        <div class="lst-divider"></div>
        <div class="lst-item">
            <div class="lst-val">24<span>/7</span></div>
            <div class="lst-label">Monitoring Aktif</div>
        </div>
        <div class="lst-divider"></div>
        <div class="lst-item">
            <div class="lst-val">11<span>+</span></div>
            <div class="lst-label">Indikator Bencana</div>
        </div>
    </div>
</div>

<form class="right-panel" action="{{ route('login.process') }}" method="post">
    @csrf

    <div class="login-header">
        <div class="welcome">Selamat Datang</div>
        <h2>Masuk ke Sistem</h2>
        <p>Gunakan akun yang telah diberikan oleh administrator untuk mengakses dashboard.</p>
    </div>

    <!-- Alert Error -->
    <div class="login-alert" id="loginAlert">
        <i class="bi bi-shield-exclamation"></i>
        <span id="alertMsg">Email atau password salah. Silakan coba lagi.</span>
    </div>

    <!-- Email -->
    <div class="form-group">
        <label class="form-label" for="email">Alamat Email</label>
        <div class="input-wrap">
            <input name="email" placeholder="nama@domain" autocomplete="email"/>
            <i class="bi bi-envelope-fill input-icon"></i>
        </div>
        @error('email')
        <div class="field-error">
            <i class="bi bi-exclamation-circle-fill"></i> <span>{{ $message }}</span>
        </div>
        @enderror
    </div>

    <!-- Password -->
    <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <div class="input-wrap">
            <input type="password" name="password" placeholder="Masukkan password" autocomplete="current-password"/>
            <i class="bi bi-lock-fill input-icon"></i>
            <button class="pw-btn" type="button" id="pwToggle" onclick="togglePassword()">
                <i class="bi bi-eye" id="pwIcon"></i>
            </button>
        </div>
        @error('password')
            <div class="field-error">
                <i class="bi bi-exclamation-circle-fill"></i> <span>{{ $message }}</span>
            </div>
        @enderror
    </div>

    <div class="form-meta">
        <div class="form-check d-flex align-items-center ps-0 gap-2 m-0">
            <input class="form-check-input m-0" type="checkbox" id="remember"/>
            <label class="form-check-label" for="remember">Ingat saya</label>
        </div>
    </div>

    <button class="btn-login" type="submit">
        <span class="btn-label"><i class="bi bi-box-arrow-in-right"></i> &nbsp;Masuk</span>
    </button>
    <hr>
    <a href="{{ route('/') }}" class="btn-login" style="text-decoration: none;background-color: #f0a500;border-color: #f0a500">
        <i class="bi bi-map"></i> &nbsp;Dashboard
    </a>

    <div class="login-footer">
        Sistem ini hanya untuk pengguna yang berwenang.<br/>
        &copy; 2026 Satgas PRR
    </div>

</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // ── Demo credentials ──────────────────────────────────────
    const DEMO = { email: 'admin@bnpb.go.id', password: 'admin123' };

    function togglePassword() {
        const inp  = document.getElementById('password');
        const icon = document.getElementById('pwIcon');
        const show = inp.type === 'password';
        inp.type   = show ? 'text' : 'password';
        icon.className = `bi bi-eye${show ? '-slash' : ''}`;
    }

    function clearErrors() {
        document.getElementById('loginAlert').classList.remove('show');
        ['email','password'].forEach(id => {
            document.getElementById(id).classList.remove('is-invalid');
        });
        document.getElementById('emailError').classList.remove('show');
        document.getElementById('passError').classList.remove('show');
    }

    function showFieldError(fieldId, errorId, msg) {
        const inp = document.getElementById(fieldId);
        const err = document.getElementById(errorId);
        inp.classList.add('is-invalid');
        err.querySelector('span').textContent = msg;
        err.classList.add('show');
    }

    function doLogin() {
        clearErrors();
        const email = document.getElementById('email').value.trim();
        const pass  = document.getElementById('password').value;
        let valid   = true;

        if (!email) {
            showFieldError('email', 'emailError', 'Alamat email wajib diisi.');
            valid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showFieldError('email', 'emailError', 'Format email tidak valid.');
            valid = false;
        }

        if (!pass) {
            showFieldError('password', 'passError', 'Password wajib diisi.');
            valid = false;
        }

        if (!valid) return;

        // simulate loading
        const btn = document.getElementById('btnLogin');
        btn.classList.add('loading');

        setTimeout(() => {
            btn.classList.remove('loading');

            if (email === DEMO.email && pass === DEMO.password) {
                // success — in real app, redirect here
                document.getElementById('alertMsg').textContent = 'Login berhasil! Mengarahkan ke dashboard…';
                const alertEl = document.getElementById('loginAlert');
                alertEl.style.background   = '#f0fdf4';
                alertEl.style.borderColor  = '#bbf7d0';
                alertEl.style.color        = '#166534';
                alertEl.querySelector('i').className = 'bi bi-check-circle-fill';
                alertEl.classList.add('show');
            } else {
                const alertEl = document.getElementById('loginAlert');
                alertEl.style = '';
                document.getElementById('alertMsg').textContent = 'Email atau password salah. Silakan coba lagi.';
                alertEl.classList.add('show');
                // re-trigger shake
                alertEl.style.animation = 'none';
                alertEl.offsetHeight; // reflow
                alertEl.style.animation = '';
            }
        }, 1400);
    }

    // Enter key support
    document.addEventListener('keydown', e => {
        if (e.key === 'Enter') doLogin();
    });
</script>
</body>
</html>
