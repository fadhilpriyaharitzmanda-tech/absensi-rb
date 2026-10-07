<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'RPLB Absensi') }}</title>
    <meta http-equiv="refresh" content="2;url={{ route('login') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: linear-gradient(135deg, #6d28d9, #4c1d95);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .splash {
            text-align: center;
        }

        .splash img {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 28px;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="splash">
        <img src="{{ asset('storage/logo/loading-logo.png') }}" alt="RPLB Logo">
    </div>
</body>
</html>
