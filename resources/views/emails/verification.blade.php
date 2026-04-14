<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email - G7KAIH</title>
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800&display=swap" rel="stylesheet"/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', Arial, sans-serif;
            background: linear-gradient(145deg, #bfdbfe 0%, #dbeafe 20%, #e0f2fe 45%, #bae6fd 70%, #c7e2fb 100%);
            min-height: 100vh;
            padding: 40px 16px;
            -webkit-font-smoothing: antialiased;
        }

        .outer-border {
            max-width: 640px;
            margin: 0 auto;
            border: 2px solid rgba(14,165,233,0.25);
            border-radius: 32px;
            padding: 6px;
            background: linear-gradient(180deg, rgba(255,255,255,0.4) 0%, rgba(186,230,253,0.2) 100%);
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.6) inset,
                0 20px 60px rgba(14,165,233,0.15),
                0 4px 16px rgba(0,0,0,0.06);
        }

        .wrapper {
            max-width: 628px;
            margin: 0 auto;
        }

        .brand-bar {
            text-align: center;
            margin-bottom: 20px;
            padding: 16px 0 4px;
        }
        .brand-bar .logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }
        .brand-bar .logo-img {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            object-fit: contain;
            background: white;
            padding: 3px;
            box-shadow: 0 3px 10px rgba(14,165,233,0.25);
        }
        .brand-bar .logo-text {
            font-size: 22px;
            font-weight: 800;
            color: #0c4a6e;
            letter-spacing: -0.5px;
        }

        .card {
            background: rgba(255,255,255,0.88);
            border: 1px solid rgba(255,255,255,0.95);
            border-radius: 26px;
            overflow: hidden;
            box-shadow:
                0 8px 32px rgba(14,165,233,0.10),
                0 2px 8px rgba(0,0,0,0.04);
            position: relative;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 80px;
            bottom: 80px;
            left: 0;
            width: 3px;
            background: linear-gradient(180deg, transparent, #0ea5e9 20%, #38bdf8 50%, #0ea5e9 80%, transparent);
        }
        .card::after {
            content: '';
            position: absolute;
            top: 80px;
            bottom: 80px;
            right: 0;
            width: 3px;
            background: linear-gradient(180deg, transparent, #0ea5e9 20%, #38bdf8 50%, #0ea5e9 80%, transparent);
        }

        .card-header {
            background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 50%, #38bdf8 100%);
            padding: 32px 40px 28px;
            position: relative;
            overflow: hidden;
            text-align: left;
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.10);
        }
        .card-header::after {
            content: '';
            position: absolute;
            bottom: -40px; left: -30px;
            width: 150px; height: 150px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }
        .card-header .badge {
            display: inline-flex;
            align-items: center;
            background: rgba(255,255,255,0.20);
            border: 1px solid rgba(255,255,255,0.35);
            border-radius: 999px;
            padding: 5px 16px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            letter-spacing: 0.4px;
            margin-bottom: 14px;
        }
        .card-header h1 {
            font-size: 26px;
            font-weight: 800;
            color: white;
            line-height: 1.2;
            letter-spacing: -0.4px;
            position: relative;
            z-index: 1;
        }

        .card-body {
            padding: 28px 40px 32px;
        }

        .message-content {
            font-size: 15px;
            color: #1e3a5f;
            line-height: 1.80;
            background: rgba(240,249,255,0.75);
            border: 1px solid rgba(186,230,253,0.65);
            border-radius: 14px;
            padding: 24px 22px;
            margin: 16px 0;
            text-align: center;
        }

        p.text-muted {
            font-size: 14px;
            color: #4b5563;
            margin-bottom: 20px;
        }

        .otp-box {
            display: inline-block;
            background: white;
            padding: 20px 40px;
            border-radius: 14px;
            border: 2px dashed #0ea5e9;
            box-shadow: 0 8px 24px rgba(14,165,233,0.15);
            margin: 20px 0;
        }

        .otp-code {
            font-size: 38px;
            font-weight: 800;
            letter-spacing: 12px;
            color: #0369a1;
            margin: 0;
        }

        .disclaimer-box {
            background: rgba(255,241,242,0.85);
            border: 1px solid rgba(254,205,211,0.75);
            border-radius: 12px;
            padding: 14px 18px;
            margin: 20px 0;
            text-align: left;
            font-size: 13px;
        }
        .disclaimer-box strong { color: #f43f5e; }

        .divider {
            border: none;
            border-top: 1px solid rgba(186,230,253,0.55);
            margin: 24px 0;
        }

        .footer-note {
            font-size: 12.5px;
            color: #64748b;
            line-height: 1.70;
            text-align: center;
        }

        .card-footer {
            background: rgba(240,249,255,0.65);
            border-top: 1px solid rgba(186,230,253,0.45);
            padding: 18px 40px;
            text-align: center;
        }
        .card-footer p {
            font-size: 12px;
            color: #94a3b8;
        }
        .card-footer strong { color: #0ea5e9; }
    </style>
</head>
<body>

<div class="outer-border">
<div class="wrapper">

    <div class="brand-bar">
        <div class="logo">
            <img src="https://i.ibb.co/NdvrqqT7/G7-KAIH-Blue.png"
                 alt="G7KAIH Logo"
                 class="logo-img">
            <span class="logo-text">G7KAIH</span>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="badge">Keamanan Akun</div>
            <h1>Verifikasi Email Anda</h1>
        </div>

        <div class="card-body">
            <div class="message-content">
                <p class="text-muted">Halo,<br>Terima kasih telah mendaftar di G7KAIH. Gunakan kode OTP berikut untuk melanjutkan proses verifikasi email Anda:</p>
                
                <div class="otp-box">
                    <p class="otp-code">{{ $otp }}</p>
                </div>
                
                <div class="disclaimer-box">
                    <strong>Penting:</strong> Kode OTP ini hanya berlaku selama 5 menit. Jangan bagikan kode ini kepada siapapun demi keamanan pendaftaran Anda.
                </div>
            </div>

            <hr class="divider">

            <p class="footer-note">
                Jika Anda tidak merasa mendaftar di G7KAIH, abaikan email ini.<br>
                Email ini dikirim secara otomatis oleh sistem.
            </p>
        </div>

        <div class="card-footer">
            <p>© {{ date('Y') }} <strong>G7KAIH</strong> · Sistem Manajemen Kebiasaan Baik · All rights reserved.</p>
        </div>
    </div>

</div>
</div>

</body>
</html>