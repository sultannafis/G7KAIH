<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'G7KAIH') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/G7KAIH-Blue.png') }}">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

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

            .guest-card {
                background: var(--glass-bg);
                backdrop-filter: blur(15px);
                -webkit-backdrop-filter: blur(15px);
                border: 1px solid var(--glass-border);
                border-radius: 28px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 440px;
                padding: 40px;
            }

            .brand-section {
                text-align: center;
                margin-bottom: 30px;
            }

            .brand-logo {
                width: 100px;
                height: auto;
                margin-bottom: 12px;
                filter: drop-shadow(0 4px 8px rgba(30, 136, 229, 0.2));
            }

            .brand-name {
                font-size: 22px;
                font-weight: 700;
                color: var(--text-main);
                margin: 0 0 4px 0;
            }

            .brand-tagline {
                color: var(--text-muted);
                font-size: 13px;
                margin: 0;
            }
        </style>
    </head>
    <body>
        @include('components.global-loader')
        <div class="guest-card">
            <div class="brand-section">
                <a href="/">
                    <img src="{{ asset('images/G7KAIH-Blue.png') }}" alt="G7KAIH Logo" class="brand-logo">
                </a>
                <h1 class="brand-name">G7KAIH</h1>
                <p class="brand-tagline">Sistem Manajemen Kebiasaan Siswa</p>
            </div>

            {{ $slot }}
        </div>
        @include('components.toast-notification')
        @include('components.ai-chatbot')
    </body>
</html>
