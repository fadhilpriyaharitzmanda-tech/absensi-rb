<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - RPLB Website Absensi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
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
            margin-bottom: 2rem;
        }

        .field {
            margin-bottom: 1.15rem;
        }

        .field label {
            display: block;
            font-size: 12.5px;
            font-weight: 500;
            color: #555;
            margin-bottom: 6px;
        }

        .field-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .field-row label {
            margin: 0;
        }

        .inp-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .inp-wrap .ico {
            position: absolute;
            left: 13px;
            font-size: 17px;
            color: #6d28d9;
            pointer-events: none;
        }

        .inp-wrap input {
            width: 100%;
            border: 1.5px solid #c5d9f0;
            border-radius: 12px;
            padding: 12px 12px 12px 42px;
            font-size: 14px;
            color: #222;
            background: #f7faff;
            outline: none;
            transition: border 0.15s, background 0.15s;
        }

        .inp-wrap input:focus {
            border-color: #6d28d9;
            background: #fff;
        }

        .eye-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            color: #aaa;
            font-size: 17px;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .eye-btn:hover {
            color: #6d28d9;
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
            margin-top: 0.5rem;
            letter-spacing: 0.02em;
            transition: background 0.15s, transform 0.1s;
        }

        .btn-main:hover {
            background: #4c1d95;
        }

        .btn-main:active {
            transform: scale(0.98);
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
        }

        .alert-success {
            background: #f0fdf4;
            color: #15803d;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 1rem;
            border: 1px solid #bbf7d0;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 1.4rem 0 1.2rem;
            color: #bbb;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5eef9;
        }

        .social-row {
            display: flex;
            justify-content: center;
            gap: 14px;
        }

        .soc-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 1.5px solid #dde8f5;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
            transition: border 0.15s, box-shadow 0.15s, transform 0.1s;
        }

        .soc-btn:hover {
            border-color: #6d28d9;
            box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.10);
            transform: translateY(-1px);
        }

        .soc-btn svg {
            width: 22px;
            height: 22px;
            display: block;
        }

        .signup {
            text-align: center;
            margin-top: 1.4rem;
            font-size: 13px;
            color: #888;
        }

        .signup a {
            color: #6d28d9;
            font-weight: 600;
            text-decoration: none;
        }

        .signup a:hover {
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

            <h1>Masuk ke Akun Anda</h1>
            <p class="subtitle">Selamat datang kembali! Silakan masukkan data Anda.</p>

            @if($errors->any())
            <div class="alert-error">
                {{ $errors->first('username') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="username">Username</label>
                    <div class="inp-wrap">
                        <i class="ti ti-user ico"></i>
                        <input type="text" id="username" name="username" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
                    </div>
                </div>

                <div class="field">
                    <div class="field-row">
                        <label for="password">Password</label>
                    </div>
                    <div class="inp-wrap">
                        <i class="ti ti-lock ico"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <button class="eye-btn" onclick="togglePw()" type="button" aria-label="Tampilkan password">
                            <i class="ti ti-eye" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-main">Masuk</button>
            </form>

            <div class="signup">
                Masuk sebagai siswa? <a href="{{ route('pin.form') }}">Gunakan PIN kelas</a>
            </div>

        </div>
    </div>

    <script>
        function togglePw() {
            const pw = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (pw.type === 'password') {
                pw.type = 'text';
                icon.className = 'ti ti-eye-off';
            } else {
                pw.type = 'password';
                icon.className = 'ti ti-eye';
            }
        }
    </script>

</body>

</html>
