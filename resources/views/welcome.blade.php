<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="Platform digital pembinaan 7 Kebiasaan Anak Indonesia Hebat — validasi berlapis, notifikasi real-time, AI assistive.">
    <title>G7KAIH</title>
    <link rel="icon" type="image/png" href="{{ asset('images/G7KAIH-Blue.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- FontAwesome CSS (lighter than JS version) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(224, 242, 254, 0.5);
        }

        .blob-shape {
            animation: morph 8s ease-in-out infinite;
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            transition: all 1s ease-in-out;
        }

        @keyframes morph {
            0% {
                border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            }

            50% {
                border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
            }

            100% {
                border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased selection:bg-sky-200 selection:text-sky-900">
    @include('components.global-loader')

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300" data-aos="fade-down" data-aos-duration="600">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">
            <div class="flex justify-between items-center h-20">
                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/G7KAIH-Blue.png') }}" alt="G7KAIH Logo"
                        class="h-10 w-auto group-hover:scale-110 transition-transform duration-300">
                    <span class="text-2xl font-bold text-sky-900 tracking-tight">G7<span
                            class="text-sky-500">KAIH</span></span>
                </a>
                <div class="hidden md:flex items-center gap-8 font-medium text-slate-600">
                    <a href="#tentang" class="hover:text-sky-600 transition-colors">Tentang Kami</a>
                    <a href="#fitur" class="hover:text-sky-600 transition-colors">7 Kebiasaan</a>
                    <a href="#alur" class="hover:text-sky-600 transition-colors">Cara Kerja</a>
                    <a href="#faq" class="hover:text-sky-600 transition-colors">FAQ</a>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-4 py-2 sm:px-6 sm:py-2.5 rounded-full bg-sky-600 text-white font-medium hover:bg-sky-700 transition-colors shadow-sm shadow-sky-200 text-xs sm:text-base">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 sm:px-5 sm:py-2 rounded-full border border-sky-200 text-sky-700 font-medium hover:bg-sky-50 hover:border-sky-400 transition-all text-xs sm:text-base">Masuk</a>
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-4 py-2 sm:px-6 sm:py-2.5 rounded-full bg-sky-600 text-white font-medium hover:bg-sky-700 hover:scale-105 transition-all shadow-sm shadow-sky-200 text-xs sm:text-base">Daftar<span class="hidden sm:inline"> Sekolah</span></a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden" style="background: linear-gradient(135deg, #e0f2f1 0%, #e3f2fd 100%);">
        <!-- Decorative blobs similar to login -->
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-[#64b5f6] rounded-full filter blur-[80px] opacity-30 -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[#ffb74d] rounded-full filter blur-[80px] opacity-30 translate-y-1/2 -translate-x-1/2"></div>

        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <div data-aos="fade-up" data-aos-delay="100"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-sky-50 border border-sky-100 text-sky-700 text-sm font-semibold mb-8 shadow-sm">
                    <span class="relative flex h-2.5 w-2.5">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-sky-500"></span>
                    </span>
                    Gerakan Nasional Pendidikan Karakter
                </div>
                <h1 data-aos="zoom-in-up" data-aos-delay="200"
                    class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-slate-900 tracking-tight mb-8 leading-tight">
                    Disiplin & Karakter <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-blue-700">Terukur &
                        Tervalidasi.</span>
                </h1>
                <p data-aos="fade-up" data-aos-delay="300"
                    class="text-lg md:text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Sistem pembinaan 7 kebiasaan harian siswa berbasis jadwal sholat, pantauan nyata, dan tiga lapis
                    validasi presisi (Orang Tua, Guru, hingga AI Assistive).
                </p>
                <div data-aos="fade-up" data-aos-delay="400"
                    class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="w-full sm:w-auto px-8 py-4 rounded-full bg-sky-600 text-white font-semibold hover:bg-sky-700 hover:scale-105 transition-all shadow-lg shadow-sky-200/50 flex items-center justify-center gap-2 text-lg">
                            Ke Dashboard <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    @else
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="w-full sm:w-auto px-8 py-4 rounded-full bg-sky-600 text-white font-semibold hover:bg-sky-700 hover:scale-105 transition-all shadow-lg shadow-sky-200/50 flex items-center justify-center gap-2 text-lg">
                                Daftarkan Sekolah Gratis
                            </a>
                        @endif
                        <a href="{{ route('login') }}"
                            class="w-full sm:w-auto px-8 py-4 rounded-full bg-white text-slate-700 font-semibold border border-slate-200 hover:border-sky-300 hover:text-sky-700 hover:-translate-y-1 transition-all flex items-center justify-center gap-2 text-lg shadow-sm">
                            Masuk ke Akun
                        </a>
                    @endauth
                </div>

                <!-- Stats -->
                <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-8 border-t border-slate-200/80 pt-10"
                    data-aos="fade-up" data-aos-delay="600">
                    <div>
                        <div class="text-4xl font-extrabold text-sky-600 mb-2">7</div>
                        <div class="text-slate-500 font-medium tracking-wide">Kebiasaan Inti</div>
                    </div>
                    <div>
                        <div class="text-4xl font-extrabold text-sky-600 mb-2">3×</div>
                        <div class="text-slate-500 font-medium tracking-wide">Validasi Lapis</div>
                    </div>
                    <div>
                        <div class="text-4xl font-extrabold text-sky-600 mb-2">5</div>
                        <div class="text-slate-500 font-medium tracking-wide">Peran Akses</div>
                    </div>
                    <div>
                        <div class="text-4xl font-extrabold text-sky-600 mb-2">24/7</div>
                        <div class="text-slate-500 font-medium tracking-wide">Notifikasi WA</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang G7KAIHSection -->
    <section id="tentang" class="py-24 relative overflow-hidden" style="background: linear-gradient(135deg, #e0f2f1 0%, #e3f2fd 100%);">
        <!-- Decorative blobs similar to login -->
        <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-[#64b5f6] rounded-full filter blur-[80px] opacity-30 -translate-y-1/2 -translate-x-1/2"></div>
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-[#ffb74d] rounded-full filter blur-[80px] opacity-30 translate-y-1/2 translate-x-1/2"></div>

        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <img src="{{ asset('images/G7KAIH-Blue.png') }}" alt="G7KAIH Logo Besar"
                        class="w-64 md:w-80 h-auto mx-auto drop-shadow-2xl hover:scale-105 transition-transform duration-700">
                </div>
                <div data-aos="fade-left">
                    <h2 class="text-sky-500 font-bold tracking-widest uppercase text-sm mb-3">Tentang G7KAIH </h2>
                    <h3 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">Mengenal G7KAIH Lebih Dekat</h3>
                    <p class="text-lg text-slate-600 mb-6 leading-relaxed text-justify">
                        <strong>Gerakan Tujuh Kebiasaan Anak Indonesia Hebat (G7KAIH)</strong> adalah inisiatif nasional
                        yang dicanangkan bersama melalui Surat Edaran Bersama Kementerian Pendidikan Dasar dan Menengah,
                        Kementerian Dalam Negeri, dan Kementerian Agama RI.
                    </p>
                    <p class="text-lg text-slate-600 mb-8 leading-relaxed text-justify">
                        Gerakan ini bertujuan melahirkan dampak ganda melalui <strong>Perubahan Intrinsik</strong>
                        (pembentukan kesadaran diri, <em>self-direction</em>, disiplin, mindset belajar) dan
                        <strong>Perubahan Ekstrinsik</strong> (motivasi konsisten dari ekosistem guru, orang tua, ruang
                        publik). Seluruh ikhtiar tersebut tidak dicapai dalam semalam, melainkan dipupuk melalui siklus
                        <strong>Pembiasaan Konsisten Berkelanjutan</strong> yang didukung secara penuh oleh teknologi
                        digital di platform kami.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-sky-100 text-sky-700 font-medium shadow-sm"><i
                                class="fa-solid fa-brain text-sky-500"></i> Intrinsik Mindset</span>
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-sky-100 text-sky-700 font-medium shadow-sm"><i
                                class="fa-solid fa-users text-sky-500"></i> Ekstrinsik Support</span>
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-sky-100 text-sky-700 font-medium shadow-sm"><i
                                class="fa-solid fa-arrows-spin text-sky-500"></i> Pembiasaan Kontinu</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7 Habits Section -->
    <section id="fitur" class="py-24 bg-white relative">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-sky-500 font-bold tracking-widest uppercase text-sm mb-3">7 Pilar Kebiasaan</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Membentuk Generasi Pemenang</h3>
                <p class="text-lg text-slate-600">Aktivitas harian yang terstruktur dan divalidasi sistem berbasis waktu
                    nyata untuk membentuk anak unggul Indonesia.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $habits = [
                        ['n' => '01', 'name' => 'Beribadah', 'desc' => 'Sholat 5 waktu terhitung otomatis dari integrasi API jadwal kemenag dengan toleransi zona waktu setempat.', 'icon' => 'fa-solid fa-hands-praying', 'delay' => '100'],
                        ['n' => '02', 'name' => 'Bangun Pagi', 'desc' => 'Pemantauan kedisiplinan dan vitalitas sedari awal hari, di mana submission tercatat sebagai trigger semangat pagi.', 'icon' => 'fa-solid fa-sun', 'delay' => '200'],
                        ['n' => '03', 'name' => 'Gemar Belajar', 'desc' => 'Rekam jejak aktivitas akademis atau ekstrakurikuler harian, membaca buku, dan pengayaan diri.', 'icon' => 'fa-solid fa-book-open', 'delay' => '300'],
                        ['n' => '04', 'name' => 'Berolahraga', 'desc' => 'Memastikan kesehatan raga lewat jejak olah fisik, baik lari pagi, senam, hingga kompetisi antar sekolah.', 'icon' => 'fa-solid fa-person-running', 'delay' => '400'],
                        ['n' => '05', 'name' => 'Makan Sehat', 'desc' => 'Jaga kualitas asupan gizi anak dari rumah. Laporan harian dengan persetujuan gizi awal oleh orang tua.', 'icon' => 'fa-solid fa-apple-whole', 'delay' => '500'],
                        ['n' => '06', 'name' => 'Bermasyarakat', 'desc' => 'Melatih empati dan tanggung jawab sosial lewat penugasan gotong royong maupun kegiatan masyarakat.', 'icon' => 'fa-solid fa-people-carry-box', 'delay' => '600'],
                        ['n' => '07', 'name' => 'Tidur Cepat', 'desc' => 'Memantau siklus rehat malam dan manajemen energi untuk persiapkan anak agar produktif di keesokan hari.', 'icon' => 'fa-solid fa-moon', 'delay' => '700'],
                    ];
                @endphp
                @foreach($habits as $h)
                    <div data-aos="zoom-in" data-aos-delay="{{ $h['delay'] }}"
                        class="bg-white rounded-2xl p-8 hover:shadow-2xl hover:shadow-sky-100 hover:-translate-y-2 transition-all duration-300 border border-slate-100 group">
                        <div
                            class="w-14 h-14 bg-sky-50 rounded-xl flex items-center justify-center text-sky-500 text-2xl mb-6 group-hover:bg-sky-500 group-hover:text-white group-hover:rotate-12 transition-all duration-300">
                            <i class="{{ $h['icon'] }}"></i>
                        </div>
                        <div class="text-sm font-bold text-slate-400 mb-2 tracking-wider">{{ $h['n'] }}</div>
                        <h4 class="text-xl font-bold text-slate-800 mb-3">{{ $h['name'] }}</h4>
                        <p class="text-slate-600 leading-relaxed">{{ $h['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How It Works & Roles -->
    <section id="alur" class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-right">
                    <h2 class="text-sky-500 font-bold tracking-widest uppercase text-sm mb-3">Cara Kerja G7KAIH</h2>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6">Siklus Sinkron,<br />Sepenuhnya
                        Terotomatisasi.</h3>
                    <p class="text-lg text-slate-600 mb-10">Kami mendigitalisasi lembar evaluasi tradisional ke dalam
                        satu ekosistem interaktif mutakhir bagi sekolah Anda.</p>

                    <div class="space-y-8">
                        <div class="flex gap-5 group" data-aos="fade-up" data-aos-delay="100">
                            <div
                                class="shrink-0 w-14 h-14 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-center text-sky-600 font-bold text-xl group-hover:border-sky-300 group-hover:bg-sky-50 transition-colors">
                                1</div>
                            <div>
                                <h4 class="text-xl font-bold text-slate-800 mb-2">Instansi Bergabung</h4>
                                <p class="text-slate-600 leading-relaxed">Admin Sekolah melakukan aktivasi dan
                                    mendaftarkan anggota (Wali Kelas, Siswa, Orang Tua) secara massal ke dalam sistem
                                    virtual.</p>
                            </div>
                        </div>
                        <div class="flex gap-5 group" data-aos="fade-up" data-aos-delay="200">
                            <div
                                class="shrink-0 w-14 h-14 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-center text-sky-600 font-bold text-xl group-hover:border-sky-300 group-hover:bg-sky-50 transition-colors">
                                2</div>
                            <div>
                                <h4 class="text-xl font-bold text-slate-800 mb-2">Aktivitas Siswa Mandiri</h4>
                                <p class="text-slate-600 leading-relaxed">Setiap harinya, siswa mendisiplinkan diri
                                    memenuhi pilar kebiasaan via unggah bukti (*foto/report*) saat berada di rumah atau
                                    luar jam sekolah.</p>
                            </div>
                        </div>
                        <div class="flex gap-5 group" data-aos="fade-up" data-aos-delay="300">
                            <div
                                class="shrink-0 w-14 h-14 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-center text-sky-600 font-bold text-xl group-hover:border-sky-300 group-hover:bg-sky-50 transition-colors">
                                3</div>
                            <div>
                                <h4 class="text-xl font-bold text-slate-800 mb-2">Validasi Berlapis Terintegrasi</h4>
                                <p class="text-slate-600 leading-relaxed">Orang tua melegitimasi awal, kemudian Robot AI
                                    memberi skor probabilitas keakuratan visual, ditutup validasi pamungkas oleh Wali
                                    Kelas. Semua terkirim lewat jalur <strong>WhatsApp Notifikasi</strong>.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Roles Hierarchy -->
                <div data-aos="zoom-in-left"
                    class="bg-white/90 backdrop-blur-md rounded-[32px] p-8 shadow-2xl shadow-slate-200/50 border border-white relative z-20">
                    <h4 class="text-2xl font-bold text-slate-800 mb-6 text-center">Hierarki 5 Peran Akses</h4>
                    <ul class="space-y-4">
                        <li
                            class="flex items-center gap-5 p-4 rounded-2xl border border-transparent hover:border-sky-100 hover:bg-sky-50/50 transition-all cursor-default">
                            <div
                                class="w-12 h-12 bg-sky-100 text-sky-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-server"></i></div>
                            <div>
                                <div class="font-bold text-slate-800">Master Admin</div>
                                <div class="text-sm text-slate-500">Pusat konfigurasi dan pengawasan sistem utama.</div>
                            </div>
                        </li>
                        <li
                            class="flex items-center gap-5 p-4 rounded-2xl border border-transparent hover:border-sky-100 hover:bg-sky-50/50 transition-all cursor-default">
                            <div
                                class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-building-columns"></i></div>
                            <div>
                                <div class="font-bold text-slate-800">Admin Sekolah</div>
                                <div class="text-sm text-slate-500">Pengelola operasional database instansi.</div>
                            </div>
                        </li>
                        <li
                            class="flex items-center gap-5 p-4 rounded-2xl border border-transparent hover:border-sky-100 hover:bg-sky-50/50 transition-all cursor-default">
                            <div
                                class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-chalkboard-user"></i></div>
                            <div>
                                <div class="font-bold text-slate-800">Guru/Wali Kelas</div>
                                <div class="text-sm text-slate-500">Ujung tombak penilai rekam jejak murid.</div>
                            </div>
                        </li>
                        <li
                            class="flex items-center gap-5 p-4 rounded-2xl border border-transparent hover:border-sky-100 hover:bg-sky-50/50 transition-all cursor-default">
                            <div
                                class="w-12 h-12 bg-teal-100 text-teal-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-users-viewfinder"></i></div>
                            <div>
                                <div class="font-bold text-slate-800">Orang Tua/Wali</div>
                                <div class="text-sm text-slate-500">Kolaborator utama validasi lingkungan rumah.</div>
                            </div>
                        </li>
                        <li
                            class="flex items-center gap-5 p-4 rounded-2xl border border-transparent hover:border-sky-100 hover:bg-sky-50/50 transition-all cursor-default">
                            <div
                                class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-user-graduate"></i></div>
                            <div>
                                <div class="font-bold text-slate-800">Siswa</div>
                                <div class="text-sm text-slate-500">Subjek utama pelaksana pembiasaan positif.</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-24 relative overflow-hidden" style="background: linear-gradient(135deg, #e0f2f1 0%, #e3f2fd 100%);">
        <!-- Decorative blobs similar to login -->
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-[#64b5f6] rounded-full filter blur-[80px] opacity-30 -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[#ffb74d] rounded-full filter blur-[80px] opacity-30 translate-y-1/2 -translate-x-1/2"></div>

        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-sky-600 font-bold tracking-widest uppercase text-sm mb-3 drop-shadow-sm">Pusat Bantuan</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 drop-shadow-sm">Pertanyaan yang Sering Diajukan</h3>
            </div>

            <div class="space-y-6">
                <!-- FAQ Item 1 -->
                <div data-aos="fade-up" data-aos-delay="100"
                    class="p-6 md:p-8 rounded-[28px] bg-white/85 backdrop-blur-[15px] border border-white/40 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.1)] hover:-translate-y-1 hover:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.15)] transition-all duration-300 group">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-sky-100 to-white border border-sky-200 flex items-center justify-center text-sky-600 group-hover:from-sky-500 group-hover:to-sky-600 group-hover:text-white group-hover:border-transparent transition-all shadow-sm">
                            <i class="fa-solid fa-circle-question text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-slate-800 mb-2 mt-1">Apakah sistem G7KAIH sepenuhnya berbayar?</h4>
                            <p class="text-slate-600 leading-relaxed">Saat ini pendaftaran untuk instansi maupun sekolah bersifat <strong>Gratis</strong> dan dapat langsung diaplikasikan. Calon Admin Sekolah cukup mendaftarkan diri, di-review sesaat oleh Master Admin pusat, dan sistem siap memfasilitasi ratusan hingga ribuan siswa Anda secara digital tanpa hambatan kertas (paperless).</p>
                        </div>
                    </div>
                </div>
                <!-- FAQ Item 2 -->
                <div data-aos="fade-up" data-aos-delay="200"
                    class="p-6 md:p-8 rounded-[28px] bg-white/85 backdrop-blur-[15px] border border-white/40 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.1)] hover:-translate-y-1 hover:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.15)] transition-all duration-300 group">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-sky-100 to-white border border-sky-200 flex items-center justify-center text-sky-600 group-hover:from-sky-500 group-hover:to-sky-600 group-hover:text-white group-hover:border-transparent transition-all shadow-sm">
                            <i class="fa-solid fa-robot text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-slate-800 mb-2 mt-1">Sehebat apa fitur AI (Kecerdasan Buatan) yang ditawarkan?</h4>
                            <p class="text-slate-600 leading-relaxed">AI kami tergolong ranah *Assistive Artificial Intelligence* berbasis Image Recognition. Misalnya, ketika anak mengklaim aktivitas "Berolahraga", model cerdas kami otomatis memindai rasio elemen gambar (apakah ia berada di lapangan, bergerak aktif dsb), lalu menyodorkan label kepercayaan tinggi kepada Guru sehingga beban evaluasi manual Guru bisa ditekan sisa 20% saja.</p>
                        </div>
                    </div>
                </div>
                <!-- FAQ Item 3 -->
                <div data-aos="fade-up" data-aos-delay="300"
                    class="p-6 md:p-8 rounded-[28px] bg-white/85 backdrop-blur-[15px] border border-white/40 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.1)] hover:-translate-y-1 hover:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.15)] transition-all duration-300 group">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-sky-100 to-white border border-sky-200 flex items-center justify-center text-sky-600 group-hover:from-sky-500 group-hover:to-sky-600 group-hover:text-white group-hover:border-transparent transition-all shadow-sm">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-slate-800 mb-2 mt-1">Apakah notifikasi WhatsApp berpotensi mengganggu (Spam)?</h4>
                            <p class="text-slate-600 leading-relaxed">Tidak. Sistem notifikasi Gateway WhatsApp telah didesain sedemikian rupa lewat antrian yang cermat agar laporan rutinitas tersampaikan pada jam-jam spesifik (seperti ringkasan di penghujung malam). Selain itu, instansi sekolah Anda punya kewenangan akses penuh mematikan tipe broadcast tertentu di panel pengaturan Admin.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-sky-600 relative overflow-hidden">
        <!-- Subtle Animated Pattern Overlay -->
        <div class="absolute inset-0 opacity-[0.1]"
            style="background-image: radial-gradient(#ffffff 2px, transparent 2px); background-size: 32px 32px;"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-sky-700/50 to-transparent"></div>

        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 relative z-10 text-center" data-aos="zoom-in">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">Misi Besar Membangun Generasi
                Tak Kasat Batas</h2>
            <p class="text-sky-100 text-xl md:text-2xl mb-12 max-w-2xl mx-auto font-light">Mari bergabung bersama
                G7KAIHmembentuk ekosistem pelajar yang tak sekadar pintar, namun diselimuti kedewasaan spiritual dan
                karakter.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-5">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-8 py-4 rounded-full bg-white text-sky-700 font-bold hover:bg-sky-50 hover:scale-105 transition-all shadow-xl shadow-sky-800/20 text-lg flex items-center justify-center gap-2">
                        Buka Dashboard Admin <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @else
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="px-8 py-4 rounded-full bg-white text-sky-700 font-bold hover:bg-sky-50 hover:scale-105 transition-all shadow-xl shadow-sky-800/20 text-lg flex items-center justify-center gap-2">
                            Daftarkan Sekolah Sekarang <i class="fa-solid fa-rocket text-sky-500"></i>
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800">
        <div
            class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 py-12 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/G7KAIH-Blue.png') }}" alt="G7KAIH Logo"
                    class="h-14 w-auto opacity-95 drop-shadow-md">
                <span class="text-xl font-bold text-white tracking-tight">G7KAIH</span>
            </div>
            <div class="text-slate-400 text-sm text-center md:text-right">
                &copy; {{ date('Y') }} Sistem Manajemen Kebiasaan Anak Hebat Indonesia.<br class="sm:hidden" /> Seluruh
                hak cipta dilindungi.
            </div>
        </div>
    </footer>

    @include('components.toast-notification')
    @include('components.ai-chatbot')

    <!-- AOS Animation Engine -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Detect mobile for lighter animations
        const isMobile = window.innerWidth < 768;
        AOS.init({
            duration: isMobile ? 400 : 800,
            once: true,
            offset: isMobile ? 20 : 50,
            easing: 'ease-out-cubic',
            disable: isMobile ? function () { return window.innerWidth < 480; } : false
        });

        // Force AOS refresh after full load to catch missed elements
        window.addEventListener('load', function () {
            setTimeout(function () { AOS.refresh(); }, 200);
        });
    </script>
</body>

</html>