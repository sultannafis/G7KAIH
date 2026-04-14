<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'G7KAIH') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/G7KAIH-Blue.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Outfit', sans-serif;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            margin: 0;
        }

        .g7-bg {
            position: fixed; inset: 0; z-index: -1;
            background:
                radial-gradient(ellipse 90% 70% at -5% -15%,  rgba(186,230,253,0.95) 0%, transparent 55%),
                radial-gradient(ellipse 70% 60% at 105% 5%,   rgba(125,211,252,0.6)  0%, transparent 50%),
                radial-gradient(ellipse 60% 50% at 100% 100%, rgba(147,197,253,0.5)  0%, transparent 55%),
                radial-gradient(ellipse 80% 60% at 10%  90%,  rgba(186,230,253,0.4)  0%, transparent 50%),
                radial-gradient(ellipse 100% 100% at 50% 50%, rgba(219,234,254,0.3)  0%, transparent 80%),
                linear-gradient(145deg, #bfdbfe 0%, #dbeafe 20%, #e0f2fe 45%, #bae6fd 70%, #c7e2fb 100%);
        }

        .g7-blob-1 {
            position: fixed; top: -120px; left: -80px;
            width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, rgba(125,211,252,0.35) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
            animation: blobFloat 12s ease-in-out infinite;
        }
        .g7-blob-2 {
            position: fixed; bottom: -100px; right: -60px;
            width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(56,189,248,0.2) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
            animation: blobFloat 16s ease-in-out infinite reverse;
        }
        .g7-blob-3 {
            position: fixed; top: 40%; right: 5%;
            width: 300px; height: 300px; border-radius: 50%;
            background: radial-gradient(circle, rgba(186,230,253,0.25) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
            animation: blobFloat 20s ease-in-out infinite 4s;
        }
        @keyframes blobFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%  { transform: translate(20px, -30px) scale(1.03); }
            66%  { transform: translate(-15px, 15px) scale(0.97); }
        }

        .glass-card {
            background: rgba(255,255,255,0.72);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.85);
            box-shadow: 0 4px 24px rgba(14,165,233,0.06), 0 1px 4px rgba(0,0,0,0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .glass-card:hover { box-shadow: 0 8px 32px rgba(14,165,233,0.1), 0 2px 8px rgba(0,0,0,0.05); }

        .nav-glass {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(255,255,255,0.9);
            box-shadow: 0 2px 20px rgba(14,165,233,0.08);
        }

        .gc {
            background: rgba(255,255,255,0.68);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.85);
            box-shadow: 0 4px 28px rgba(14,165,233,.07), 0 1px 3px rgba(0,0,0,.04);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .gc:hover { transform:translateY(-2px); box-shadow:0 12px 40px rgba(14,165,233,.13),0 2px 8px rgba(0,0,0,.06) }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: rgba(56,189,248,0.8) !important;
            box-shadow: 0 0 0 3px rgba(56,189,248,0.15) !important;
        }

        /* ── Glass Header (static, tidak sticky) ── */
        .floating-header {
            width: 100%;
            margin: 0 0 28px 0;
            border-radius: 20px;
            background: rgba(255,255,255,0.78);
            backdrop-filter: blur(28px); -webkit-backdrop-filter: blur(28px);
            border: 1px solid rgba(255,255,255,0.92);
            box-shadow:
                0 8px 32px rgba(14,165,233,0.12),
                0 2px 8px rgba(0,0,0,0.05),
                inset 0 1px 0 rgba(255,255,255,0.9);
            animation: headerFloat .5s cubic-bezier(.22,1,.36,1) both .1s;
        }
        @keyframes headerFloat {
            from { opacity:0; transform:translateY(-14px); }
            to   { opacity:1; transform:translateY(0); }
        }
        @media (max-width: 640px) {
            .floating-header { border-radius: 14px; }
        }

        @media (prefers-color-scheme: dark) {
            .g7-bg {
                background:
                    radial-gradient(ellipse 90% 70% at -5% -15%,  rgba(2,132,199,0.25)  0%, transparent 55%),
                    radial-gradient(ellipse 70% 60% at 105% 5%,   rgba(3,105,161,0.2)   0%, transparent 50%),
                    radial-gradient(ellipse 60% 50% at 100% 100%, rgba(7,89,133,0.2)    0%, transparent 55%),
                    linear-gradient(145deg, #0c1929 0%, #0d2035 40%, #0a1a2e 70%, #0c1929 100%);
            }
            .glass-card, .gc {
                background: rgba(12,25,42,0.75);
                border: 1px solid rgba(56,189,248,0.12);
                box-shadow: 0 4px 24px rgba(0,0,0,0.2);
            }
            .nav-glass {
                background: rgba(10,22,38,0.88);
                border-bottom: 1px solid rgba(56,189,248,0.1);
            }
            .floating-header {
                background: rgba(10,22,38,0.82);
                border: 1px solid rgba(56,189,248,0.15);
                box-shadow: 0 8px 32px rgba(0,0,0,0.3), 0 2px 8px rgba(0,0,0,0.2);
            }
        }

        @keyframes pageIn {
            from { opacity:0; transform:translateY(12px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .page-in { animation: pageIn .4s cubic-bezier(.22,1,.36,1) both; }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(14,165,233,0.25); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(14,165,233,0.45); }
    </style>
    @stack('head')
</head>
<body>
    @include('components.global-loader')
    <div class="g7-bg"></div>
    <div class="g7-blob-1"></div>
    <div class="g7-blob-2"></div>
    <div class="g7-blob-3"></div>

    <div class="min-h-screen relative" style="z-index:1;">
        @include('layouts.navigation')

        @isset($header)
            <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 pt-6">
                <header class="floating-header">
                    <div class="px-6 sm:px-8 py-5 sm:py-6">
                        {{ $header }}
                    </div>
                </header>
            </div>
        @endisset

        <main class="page-in">
            {{ $slot }}
        </main>
    </div>

    {{-- Global Toast Notifications --}}
    @include('components.toast-notification')

    {{-- AI Chatbot Widget --}}
    @include('components.ai-chatbot')

    {{-- Global Confirm/Alert Modal --}}
    @include('components.confirm-modal')

    @stack('scripts')
</body>
</html>