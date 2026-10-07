<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk Siswa — Presensi 11 RPL B</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;1,400&family=JetBrains+Mono:wght@500;600&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            min-height: 100vh;
        }

        .panel-left {
            flex: 2;
            background: linear-gradient(135deg, #6d28d9, #4c1d95);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .panel-left::before {
            content: '';
            position: absolute;
            width: 420px;
            height: 420px;
            background: rgba(255, 255, 255, 0.07);
            border-radius: 50%;
            top: -130px;
            left: -130px;
        }

        .panel-left::after {
            content: '';
            position: absolute;
            width: 280px;
            height: 280px;
            background: rgba(255, 255, 255, 0.07);
            border-radius: 50%;
            bottom: -90px;
            right: -80px;
        }

        .left-inner {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .left-inner img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .mobile-logo {
            display: none;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.75rem;
        }

        .mobile-logo img {
            width: 110px;
            height: 110px;
            object-fit: cover;
            display: block;
        }

        .panel-right {
            flex: 0 0 33.333%;
            background: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2.5rem;
        }

        .form-inner {
            width: 100%;
            max-width: 320px;
        }

        .form-inner h1 {
            font-size: 22px;
            font-weight: 600;
            color: #6d28d9;
            margin-bottom: 0.3rem;
        }

        .form-inner .subtitle {
            font-size: 13.5px;
            color: #999;
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 1rem;
            border: 1px solid #fecaca;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .pin-dots {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 1.25rem;
        }

        .pin-dot {
            width: 42px;
            height: 50px;
            border: 1.5px solid #c5d9f0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: 20px;
            font-weight: 600;
            color: #6d28d9;
            background: #f7faff;
            transition: border-color 0.15s, background 0.15s, transform 0.1s;
        }

        .pin-dot.filled {
            border-color: #6d28d9;
            background: #ede9fe;
        }

        .pin-dot.filled::after {
            content: '\2022';
            color: #6d28d9;
            font-size: 26px;
            line-height: 1;
        }

        .pin-dot.active {
            border-color: #6d28d9;
            box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.12);
            transform: scale(1.04);
        }

        .pin-dot.error-shake {
            border-color: #b91c1c;
            background: #fef2f2;
            animation: shake 0.35s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-5px); }
            60% { transform: translateX(5px); }
            80% { transform: translateX(-3px); }
        }

        .numpad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 4px;
        }

        .key {
            aspect-ratio: 1.6 / 1;
            border: 1.5px solid #dde8f5;
            border-radius: 12px;
            background: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 18px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            transition: background 0.15s, border-color 0.15s, transform 0.08s;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        .key:hover {
            background: #f5f0ff;
            border-color: #6d28d9;
            color: #6d28d9;
        }

        .key:active {
            transform: scale(0.94);
        }

        .key.key-del {
            color: #b91c1c;
            border-color: #fecaca;
            background: #fef2f2;
        }

        .key.key-del:hover {
            background: #fde8e8;
            border-color: #b91c1c;
        }

        .key.key-del i {
            font-size: 22px;
        }

        .key.key-empty {
            opacity: 0;
            pointer-events: none;
            cursor: default;
        }

        .btn-main {
            width: 100%;
            background: #6d28d9;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 13.5px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1rem;
            letter-spacing: 0.02em;
            transition: background 0.15s, transform 0.1s, opacity 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .btn-main:hover {
            background: #4c1d95;
        }

        .btn-main:active {
            transform: scale(0.98);
        }

        .btn-main:disabled {
            opacity: 0.45;
            cursor: default;
            pointer-events: none;
        }

        .btn-main.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .switch-row {
            text-align: center;
            margin-top: 1.4rem;
            font-size: 13px;
            color: #888;
        }

        .switch-row a {
            color: #6d28d9;
            font-weight: 600;
            text-decoration: none;
        }

        .switch-row a:hover {
            text-decoration: underline;
        }

        @media (max-width: 620px) {
            body {
                flex-direction: column;
            }

            .panel-left {
                display: none;
            }

            .panel-right {
                flex: 1;
                padding: 2.5rem 1.75rem;
                justify-content: flex-start;
                padding-top: 3rem;
            }

            .mobile-logo {
                display: flex;
            }
        }
    </style>
</head>
<body>

    <div class="panel-left">
        <div class="left-inner">
            <img src="{{ asset('storage/logo/loading-logo.png') }}" alt="RPLB Logo" />
        </div>
    </div>

    <div class="panel-right">
        <div class="form-inner">

            <div class="mobile-logo">
                <img src="{{ asset('storage/logo/logo.png') }}" alt="RPLB Logo">
            </div>

            <h1>Masuk sebagai Siswa</h1>
            <p class="subtitle">Masukkan PIN 6 digit dari wali kelas</p>

            @if(session('info'))
            <script>document.addEventListener('DOMContentLoaded',function(){ showToast(@json(session('info')),'info'); });</script>
            @endif
            @if($errors->any())
            <div class="alert-error">
                <i class="ti ti-alert-circle"></i>
                {{ $errors->first('pin') }}
            </div>
            @endif

            <form id="form-siswa" method="POST" action="{{ route('pin.verify') }}">
                @csrf
                <input type="hidden" id="pin-value" name="pin">

                <div class="pin-dots" id="pin-dots">
                    <div class="pin-dot active" id="dot-0"></div>
                    <div class="pin-dot" id="dot-1"></div>
                    <div class="pin-dot" id="dot-2"></div>
                    <div class="pin-dot" id="dot-3"></div>
                    <div class="pin-dot" id="dot-4"></div>
                    <div class="pin-dot" id="dot-5"></div>
                </div>

                <div class="numpad">
                    <button type="button" class="key" onclick="pressKey('1')">1</button>
                    <button type="button" class="key" onclick="pressKey('2')">2</button>
                    <button type="button" class="key" onclick="pressKey('3')">3</button>
                    <button type="button" class="key" onclick="pressKey('4')">4</button>
                    <button type="button" class="key" onclick="pressKey('5')">5</button>
                    <button type="button" class="key" onclick="pressKey('6')">6</button>
                    <button type="button" class="key" onclick="pressKey('7')">7</button>
                    <button type="button" class="key" onclick="pressKey('8')">8</button>
                    <button type="button" class="key" onclick="pressKey('9')">9</button>
                    <button type="button" class="key key-empty" aria-hidden="true"></button>
                    <button type="button" class="key" onclick="pressKey('0')">0</button>
                    <button type="button" class="key key-del" onclick="pressDelete()" aria-label="Hapus digit terakhir">
                        <i class="ti ti-backspace"></i>

                    </button>
                </div>

                <button type="submit" class="btn-main" id="btn-siswa" disabled>
                    <i class="ti ti-login"></i>
                    Masuk
                </button>
            </form>

            <div class="switch-row">
                Guru atau admin? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>

        </div>
    </div>

    <style>
        @keyframes toastIn { from { opacity:0; transform:translateX(30px); } to { opacity:1; transform:translateX(0); } }
    </style>
    <script>
    function showToast(msg, type) {
        var c = document.getElementById('toast-area');
        if (!c) { c = document.createElement('div'); c.id = 'toast-area';
            c.style.cssText = 'position:fixed;top:24px;right:24px;z-index:99999;display:flex;flex-direction:column;gap:12px;max-width:420px';
            document.body.appendChild(c); }
        var t = document.createElement('div');
        var isS = type === 'success';
        var ac = isS ? '#059669' : '#6d28d9';
        var bg = isS ? '#ecfdf5' : '#f5f0ff';
        var ic = isS ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="' + ac + '" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
            : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="' + ac + '" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>';
        t.style.cssText = 'display:flex;align-items:flex-start;gap:12px;background:#fff;border:1px solid #e2e8f0;border-left:4px solid ' + ac + ';border-radius:12px;padding:14px 16px;box-shadow:0 8px 24px rgba(0,0,0,0.08);font-family:\'Inter\',\'Segoe UI\',sans-serif;animation:toastIn 0.35s ease';
        t.innerHTML = '<div style="flex-shrink:0;width:36px;height:36px;border-radius:10px;background:' + bg + ';display:flex;align-items:center;justify-content:center">' + ic + '</div>'
            + '<div style="flex:1;min-width:0"><div style="font-size:14px;font-weight:600;color:#1e293b">' + (isS ? 'Berhasil' : 'Informasi') + '</div>'
            + '<div style="font-size:13px;color:#64748b;margin-top:2px;line-height:1.4">' + msg + '</div></div>'
            + '<button onclick="this.parentElement.remove()" style="flex-shrink:0;width:24px;height:24px;border:none;background:transparent;cursor:pointer;color:#94a3b8;font-size:20px;line-height:1;padding:0;display:flex;align-items:center;justify-content:center">&times;</button>';
        c.appendChild(t);
        setTimeout(function(){ t.style.transition = 'opacity 0.3s,transform 0.3s'; t.style.opacity = '0'; t.style.transform = 'translateX(30px)';
            setTimeout(function(){ if(t.parentNode) t.parentNode.removeChild(t); },300); }, isS ? 3000 : 5000);
    }
    </script>
    <script>
        let pinBuffer = [];
        const PIN_LEN = 6;

        function updateDots() {
            for (let i = 0; i < PIN_LEN; i++) {
                const dot = document.getElementById('dot-' + i);
                dot.classList.toggle('filled', i < pinBuffer.length);
                dot.classList.toggle('active', i === pinBuffer.length);
                dot.classList.remove('error-shake');
            }
            const btn = document.getElementById('btn-siswa');
            const full = pinBuffer.length === PIN_LEN;
            btn.disabled = !full;
            document.getElementById('pin-value').value = pinBuffer.join('');
        }

        function pressKey(digit) {
            if (pinBuffer.length >= PIN_LEN) return;
            pinBuffer.push(digit);
            updateDots();
            if (pinBuffer.length === PIN_LEN) {
                document.getElementById('form-siswa').requestSubmit();
            }
        }

        function pressDelete() {
            if (pinBuffer.length === 0) return;
            pinBuffer.pop();
            updateDots();
        }

        document.addEventListener('keydown', function (e) {
            if (e.key >= '0' && e.key <= '9') pressKey(e.key);
            if (e.key === 'Backspace') pressDelete();
        });
    </script>
</body>
</html>
