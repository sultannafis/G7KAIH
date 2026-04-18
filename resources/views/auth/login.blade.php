<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - G7KAIH</title>
    <link rel="icon" type="image/png" href="{{ asset('images/G7KAIH-Blue.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
            margin-bottom: 35px;
        }

        .brand-logo {
            width: 120px;
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
        }

        .nav-pills {
            background: rgba(255, 255, 255, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 6px;
            border-radius: 16px;
            margin-bottom: 30px;
            display: flex;
            gap: 4px;
        }

        .nav-pills .nav-link {
            flex: 1;
            border-radius: 12px;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 14px;
            padding: 10px 4px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: transparent;
        }

        .nav-pills .nav-link:hover:not(.active) {
            background: rgba(255, 255, 255, 0.3);
            color: var(--primary);
        }

        .nav-pills .nav-link.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
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

        .btn-scan {
            background: white;
            border: 1.5px solid var(--primary);
            color: var(--primary);
            border-radius: 14px;
            padding: 12px;
            font-weight: 600;
            font-size: 14px;
            width: 100%;
            margin-top: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .btn-scan:hover {
            background: rgba(30, 136, 229, 0.05);
            transform: translateY(-1px);
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

        .info-box {
            background: rgba(30, 136, 229, 0.05);
            border-radius: 14px;
            padding: 12px 16px;
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .info-box i { color: var(--primary); margin-top: 2px; }

        /* QR Scanner Modern UI */
        .scanner-container {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            background: #000;
            box-shadow: 0 15px 35px -5px rgba(30, 136, 229, 0.2);
            margin: 0 auto 15px auto;
            width: 100%;
            max-width: 350px;
            aspect-ratio: 1 / 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #qr-reader {
            width: 100% !important;
            height: 100% !important;
            border: none !important;
            margin: 0 !important;
        }
        #qr-reader video {
            object-fit: cover !important;
            width: 100% !important;
            height: 100% !important;
        }
        /* Sembunyikan styling bawaan yang mengganggu */
        #qr-reader img { display: none !important; }
        
        .scanner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .scanner-box {
            position: relative;
            width: 250px;
            height: 250px;
        }
        .scanner-corners {
            position: absolute;
            top: -4px; left: -4px; width: calc(100% + 8px); height: calc(100% + 8px);
        }
        .scanner-corners::before, .scanner-corners::after, 
        .scanner-corners span::before, .scanner-corners span::after {
            content: '';
            position: absolute;
            width: 35px;
            height: 35px;
            border-color: var(--primary);
            border-style: solid;
        }
        .scanner-corners::before { top: 0; left: 0; border-width: 4px 0 0 4px; border-top-left-radius: 16px; }
        .scanner-corners::after { top: 0; right: 0; border-width: 4px 4px 0 0; border-top-right-radius: 16px; }
        .scanner-corners span::before { bottom: 0; left: 0; border-width: 0 0 4px 4px; border-bottom-left-radius: 16px; }
        .scanner-corners span::after { bottom: 0; right: 0; border-width: 0 4px 4px 0; border-bottom-right-radius: 16px; }

        .scan-line {
            position: absolute;
            top: 0;
            left: 5%;
            width: 90%;
            height: 4px;
            background: var(--primary);
            box-shadow: 0 0 10px var(--primary), 0 0 20px var(--primary);
            animation: scan 2.5s infinite ease-in-out alternate;
            border-radius: 10px;
        }
        @keyframes scan {
            0% { top: 5%; opacity: 0.8; }
            50% { opacity: 1; }
            100% { top: 95%; opacity: 0.8; }
        }
        #qr-reader-results { font-size: 14px; font-weight: 600; text-align: center; }

        .footer-links {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .footer-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .footer-links a:hover { text-decoration: underline; }

        .hidden { display: none !important; }

        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
                border-radius: 24px;
            }
            .brand-logo { width: 100px; }
            .nav-pills .nav-link { 
                flex-direction: column;
                font-size: 12px;
                padding: 8px 4px;
                gap: 5px;
            }
            .nav-pills .nav-link i {
                margin: 0 !important;
                font-size: 18px;
            }
        }

        /* ═══ DARK MODE (OS PREFERENCE) ═════════════════════ */
        @media (prefers-color-scheme: dark) {
            :root {
                --glass-bg: rgba(16, 24, 39, 0.85);
                --glass-border: rgba(255, 255, 255, 0.1);
                --primary: #3b82f6;
                --primary-dark: #2563eb;
                --secondary: #1e3a8a;
                --accent: #b45309;
                --text-main: #f3f4f6;
                --text-muted: #9ca3af;
            }
            body {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            }
            .form-control, .btn-scan {
                background: rgba(30, 41, 59, 0.6);
                border-color: rgba(255, 255, 255, 0.1);
                color: #f3f4f6;
            }
            .form-control:focus {
                background: rgba(30, 41, 59, 0.9);
                color: #f3f4f6;
            }
            .btn-scan {
                color: var(--primary);
                border-color: var(--primary);
            }
            .nav-pills {
                background: rgba(15, 23, 42, 0.4);
                border-color: rgba(255, 255, 255, 0.05);
            }
            .nav-pills .nav-link:hover:not(.active) {
                background: rgba(255, 255, 255, 0.05);
            }
            .nav-pills .nav-link.active {
                background: #1e293b;
                color: var(--primary);
            }
            .info-box {
                background: rgba(59, 130, 246, 0.1);
            }
        }
    </style>
</head>
<body>
@include('components.global-loader')
    <div class="login-card">
        <div class="brand-section">
            <img src="{{ asset('images/G7KAIH-Blue.png') }}" alt="G7KAIH Logo" style="height: 80px; width: auto; margin-bottom: 20px;">
            <h1 class="brand-name">Selamat Datang</h1>
            <p class="brand-tagline">Sistem Manajemen Kebiasaan Siswa</p>
        </div>




        <!-- Custom Tabs -->
        <div class="nav-pills">
            <button class="nav-link active" data-tab="email">
                <i class="fas fa-envelope me-1"></i> Email
            </button>
            <button class="nav-link" data-tab="siswa">
                <i class="fas fa-user-graduate me-1"></i> Siswa
            </button>
            <button class="nav-link" data-tab="orangtua">
                <i class="fas fa-users me-1"></i> Orang Tua
            </button>
        </div>

        <!-- Email Tab -->
        <div id="emailTab" class="login-content">
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                Login menggunakan email.
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-at"></i> Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@sekolah.sch.id" required>
                </div>
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0"><i class="fas fa-key"></i> Password</label>
                        <a href="{{ route('password.request') }}" class="text-decoration-none" style="font-size: 13px; font-weight: 600; color: var(--primary);">Lupa Password?</a>
                    </div>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="mb-4 d-flex justify-content-center">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                </div>
                <button type="submit" class="btn-login">Masuk Sekarang</button>
            </form>
        </div>

        <!-- Student Tab -->
        <div id="siswaTab" class="login-content hidden">
            <div id="siswaMainView">
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    Masukkan NIS/NISN yang diberikan sekolah untuk login cepat.
                </div>
                <form method="POST" action="{{ route('login.siswa') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label"><i class="fas fa-id-card"></i> NIS / NISN</label>
                        <input type="text" name="student_code" class="form-control" placeholder="Contoh: 21221001" required>
                    </div>
                    <div class="mb-4 d-flex justify-content-center">
                        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    </div>
                    <button type="submit" class="btn-login">Login Siswa</button>
                </form>
                <button class="btn-scan" id="startScannerBtn">
                    <i class="fas fa-qrcode"></i> Scan Kartu Siswa
                </button>
            </div>

            <div id="scannerView" class="hidden text-center">
                <h5 class="mb-3 font-bold text-slate-800" style="color: var(--text-main);">Scan Barcode Anda</h5>
                <div class="scanner-container">
                    <div id="qr-reader"></div>
                    <div class="scanner-overlay">
                        <div class="scanner-box">
                            <div class="scan-line"></div>
                            <div class="scanner-corners">
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="qr-reader-results" class="mt-3"></div>
                <button class="btn btn-link text-muted mt-3 underline text-sm" id="stopScannerBtn" style="font-weight:600; text-decoration:none;">
                    <i class="fas fa-times-circle"></i> Batal Kembali
                </button>
            </div>
        </div>

        <!-- Parent Tab -->
        <div id="orangtuaTab" class="login-content hidden">
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                Masukkan kode login yang diberikan oleh admin sekolah.
            </div>
            <form method="POST" action="{{ route('login.orangtua') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-key"></i> Kode Login</label>
                    <input type="text" name="login_code" class="form-control" placeholder="Masukkan kode login orang tua" required>
                </div>
                <div class="mb-4 d-flex justify-content-center">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                </div>
                <button type="submit" class="btn-login">Login Orang Tua</button>
            </form>
        </div>

        <div class="footer-links">
            <p>Belum terdaftar? <a href="{{ route('register') }}">Daftarkan Sekolah</a></p>
            <p class="mt-2"><a href="{{ url('/') }}"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a></p>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let html5QrCode = null;

            // Tab handling
            $('.nav-link').click(function() {
                const tab = $(this).data('tab');
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
                $('.login-content').addClass('hidden');
                $(`#${tab}Tab`).removeClass('hidden');
                
                // Stop scanner if switching tabs
                if (html5QrCode) stopScanner();
            });

            // Scanner actions
            $('#startScannerBtn').click(function() {
                $('#siswaMainView').addClass('hidden');
                $('#scannerView').removeClass('hidden');
                startScanner();
            });

            $('#stopScannerBtn').click(function() {
                stopScanner();
                $('#scannerView').addClass('hidden');
                $('#siswaMainView').removeClass('hidden');
            });

            function startScanner() {
                html5QrCode = new Html5Qrcode("qr-reader", {
                    experimentalFeatures: {
                        useBarCodeDetectorIfSupported: true
                    },
                    verbose: false
                });

                const config = {
                    fps: 30,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0,
                    formatsToSupport: [
                        Html5QrcodeSupportedFormats.QR_CODE,
                        Html5QrcodeSupportedFormats.CODE_128,
                        Html5QrcodeSupportedFormats.CODE_39,
                        Html5QrcodeSupportedFormats.EAN_13,
                        Html5QrcodeSupportedFormats.EAN_8,
                        Html5QrcodeSupportedFormats.ITF,
                        Html5QrcodeSupportedFormats.DATA_MATRIX
                    ]
                };

                html5QrCode.start(
                    { facingMode: "environment" }, 
                    config, 
                    (decodedText) => {
                        stopScanner();
                        processBarcode(decodedText);
                    }
                ).catch(err => {
                    $('#qr-reader-results').html(`<div class="text-danger mt-2">Kamera tidak aktif: ${err}</div>`);
                });
            }

            function stopScanner() {
                if (html5QrCode) {
                    html5QrCode.stop().then(() => {
                        html5QrCode.clear();
                        html5QrCode = null;
                    }).catch(err => console.log(err));
                }
            }

            function processBarcode(data) {
                $('#qr-reader-results').html('<div class="text-primary mt-2"><i class="fas fa-spinner fa-spin"></i> Memproses...</div>');
                
                $.ajax({
                    url: '{{ route("login.barcode") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        barcode: data
                    },
                    success: function(res) {
                        if (res.success) {
                            window.location.href = res.redirect;
                        } else {
                            $('#qr-reader-results').html(`<div class="text-danger mt-2">${res.message}</div>`);
                            setTimeout(() => startScanner(), 2000);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 419) {
                            $('#qr-reader-results').html('<div class="text-danger mt-2">Sesi telah kedaluwarsa. Memuat ulang halaman...</div>');
                            setTimeout(() => window.location.reload(), 1500);
                            return;
                        }
                        const msg = xhr.responseJSON?.message || 'Gagal mengenali barcode.';
                        $('#qr-reader-results').html(`<div class="text-danger mt-2">${msg}</div>`);
                        setTimeout(() => startScanner(), 2000);
                    }
                });
            }
        });
    </script>
    @include('components.toast-notification')
    @include('components.ai-chatbot')
</body>
</html>