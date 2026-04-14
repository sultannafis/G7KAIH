<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - G7KAIH</title>
    <link rel="icon" type="image/png" href="{{ asset('images/G7KAIH-Blue.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.4);
            --primary: #1e88e5;
            --primary-dark: #1565c0;
            --secondary: #64b5f6;
            --accent: #ffb74d;
            --text-main: #1a237e;
            --text-muted: #546e7a;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #e0f2f1 0%, #e3f2fd 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative blobs */
        body::before, body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            z-index: -1;
            filter: blur(80px);
            opacity: 0.5;
        }
        body::before {
            background: var(--secondary);
            top: -100px;
            right: -100px;
        }
        body::after {
            background: var(--accent);
            bottom: -100px;
            left: -100px;
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px;
            padding: 40px;
            transition: transform 0.3s ease;
        }

        .brand-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo {
            width: 100px;
            height: auto;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 8px rgba(30, 136, 229, 0.2));
        }

        .brand-name {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
            letter-spacing: -0.5px;
        }

        .brand-tagline {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 5px;
            line-height: 1.5;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control {
            border: 1.5px solid rgba(0, 0, 0, 0.08);
            border-radius: 14px;
            padding: 12px 16px;
            font-size: 15px;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.2s;
        }

        .form-control:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(30, 136, 229, 0.1);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-weight: 700;
            font-size: 16px;
            color: white;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 10px 20px -5px rgba(30, 136, 229, 0.4);
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(30, 136, 229, 0.5);
            filter: brightness(1.1);
        }

        .alert {
            border-radius: 16px;
            border: none;
            font-size: 14px;
            padding: 14px 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger { background: #fee2e2; color: #991b1b; }
        .alert-success { background: #dcfce7; color: #166534; }

        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
                border-radius: 24px;
            }
            .brand-logo { width: 90px; }
        }
    </style>
</head>
<body>
    @include('components.global-loader')
    <div class="login-card">
        <div class="brand-section">
            <img src="{{ asset('images/G7KAIH-Blue.png') }}" alt="G7KAIH Logo" class="brand-logo">
            <h1 class="brand-name">Buat Password Baru</h1>
            <p class="brand-tagline">Silakan masukkan password baru Anda untuk melanjutkan masuk ke sistem.</p>
        </div>


        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-at"></i> Alamat Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $request->email) }}" required readonly>
            </div>
            
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-key"></i> Password Baru</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required autofocus>
            </div>

            <div class="mb-4">
                <label class="form-label"><i class="fas fa-check-double"></i> Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn-login">Reset Password</button>
        </form>
    </div>
    @include('components.toast-notification')
    @include('components.ai-chatbot')
</body>
</html>
