<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pendaftaran Sekolah - G7KAIH</title>
    <link rel="icon" type="image/png" href="{{ asset('images/G7KAIH-Blue.png') }}">

    <!-- External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>
@include('components.global-loader')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="register-card">
                    <!-- Header -->
                    <div class="register-header text-center">
                        <img src="{{ asset('images/G7KAIH-Blue.png') }}" alt="G7KAIH Logo" style="height: 80px; width: auto; margin-bottom: 20px;">
                        <h2 class="fw-bold"><i class="fas fa-school me-2 text-primary"></i> Pendaftaran Sekolah</h2>
                        <p class="text-muted">Bergabung dengan G7KAIH untuk mengelola kebiasaan baik siswa</p>
                    </div>

                    <!-- Body -->
                    <div class="register-body">
                        <form id="registerForm" action="{{ route('register.store') }}" method="POST">
                            @csrf

                            <!-- School Information -->
                            <div class="mb-5">
                                <div class="section-title">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Informasi Sekolah</span>
                                </div>

                                <div class="row">
                                    <!-- Nama Sekolah -->
                                    <div class="col-md-6 mb-4">
                                        <label for="school_name" class="form-label">
                                            <i class="fas fa-school me-1"></i> Nama Sekolah <span
                                                class="required-star">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="school_name" name="school_name"
                                            required placeholder="Masukkan nama sekolah lengkap">
                                        <div class="form-text">Contoh: SD Negeri 1 Jakarta</div>
                                    </div>

                                    <!-- NPSN -->
                                    <div class="col-md-6 mb-4">
                                        <label for="npsn" class="form-label">
                                            <i class="fas fa-id-card me-1"></i> NPSN <span
                                                class="required-star">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="npsn" name="npsn" required
                                            maxlength="8" pattern="\d{8}" placeholder="8 digit NPSN">
                                        <div class="form-text">Nomor Pokok Sekolah Nasional (8 digit angka)</div>
                                    </div>
                                </div>

                                <!-- Address Fields -->
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="province_id" class="form-label">
                                            <i class="fas fa-map me-1"></i> Provinsi <span
                                                class="required-star">*</span>
                                        </label>
                                        <select class="form-select" id="province_id" name="province_id" required>
                                            <option value="">Pilih Provinsi</option>
                                            @foreach($provinces ?? [] as $province)
                                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="city_id" class="form-label">
                                            <i class="fas fa-city me-1"></i> Kota/Kabupaten <span
                                                class="required-star">*</span>
                                        </label>
                                        <select class="form-select" id="city_id" name="city_id" required disabled>
                                            <option value="">Pilih Kota/Kabupaten</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="district_id" class="form-label">
                                            <i class="fas fa-map-marked me-1"></i> Kecamatan <span
                                                class="required-star">*</span>
                                        </label>
                                        <select class="form-select" id="district_id" name="district_id" required
                                            disabled>
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="village_id" class="form-label">
                                            <i class="fas fa-map-pin me-1"></i> Kelurahan <span
                                                class="required-star">*</span>
                                        </label>
                                        <select class="form-select" id="village_id" name="village_id" required disabled>
                                            <option value="">Pilih Kelurahan</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Detail Alamat -->
                                <div class="mb-4">
                                    <label for="address_detail" class="form-label">
                                        <i class="fas fa-location-dot me-1"></i> Detail Alamat <span
                                            class="required-star">*</span>
                                    </label>
                                    <textarea class="form-control" id="address_detail" name="address_detail" rows="3"
                                        required placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02"></textarea>
                                    <div class="form-text">Isi dengan alamat lengkap sekolah</div>
                                </div>

                                <!-- Map Section -->
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="fas fa-map-location-dot me-1"></i> Peta Lokasi
                                    </label>
                                    <div class="map-container">
                                        <div id="map"></div>
                                        <div class="map-controls">
                                            <button type="button" class="location-btn" id="btnGetLocation">
                                                <i class="fas fa-crosshairs"></i> Lokasi Saya
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-text">Klik pada peta untuk menentukan koordinat sekolah</div>
                                    <input type="hidden" id="latitude" name="latitude">
                                    <input type="hidden" id="longitude" name="longitude">
                                </div>

                                <div class="row">
                                    <!-- Kode Pos -->
                                    <div class="col-md-6 mb-4">
                                        <label for="postal_code" class="form-label">
                                            <i class="fas fa-mail-bulk me-1"></i> Kode Pos
                                        </label>
                                        <input type="text" class="form-control" id="postal_code" name="postal_code"
                                            placeholder="Contoh: 12345">
                                    </div>

                                    <!-- Timezone -->
                                    <div class="col-md-6 mb-4">
                                        <label for="timezone" class="form-label">
                                            <i class="fas fa-clock me-1"></i> Zona Waktu <span
                                                class="required-star">*</span>
                                        </label>
                                        <select class="form-select" id="timezone" name="timezone" required>
                                            <option value="Asia/Jakarta">WIB (Asia/Jakarta)</option>
                                            <option value="Asia/Makassar">WITA (Asia/Makassar)</option>
                                            <option value="Asia/Jayapura">WIT (Asia/Jayapura)</option>
                                        </select>
                                        <div class="form-text">Pilih zona waktu sesuai lokasi sekolah</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Admin Information -->
                            <div class="mb-5">
                                <div class="section-title">
                                    <i class="fas fa-user-tie"></i>
                                    <span>Informasi Admin Sekolah</span>
                                </div>

                                <!-- Nama Admin -->
                                <div class="mb-4">
                                    <label for="admin_name" class="form-label">
                                        <i class="fas fa-user me-1"></i> Nama Lengkap Admin <span
                                            class="required-star">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="admin_name" name="admin_name" required
                                        placeholder="Nama lengkap admin sekolah">
                                </div>

                                <!-- Phone Number -->
                                <div class="mb-4">
                                    <label for="phone_number" class="form-label">
                                        <i class="fas fa-phone me-1"></i> Nomor Telepon <span
                                            class="required-star">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">+62</span>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number"
                                            required placeholder="81234567890" pattern="[0-9]{10,13}">
                                    </div>
                                    <div class="form-text">Contoh: 81234567890 (tanpa +62)</div>
                                </div>

                                <!-- Email -->
                                <div class="mb-4">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i> Email <span class="required-star">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="email" class="form-control" id="email" name="email" required
                                            placeholder="admin@sekolah.sch.id">
                                        <button type="button" class="btn btn-outline-primary" id="btnSendOtp">
                                            <i class="fas fa-paper-plane me-1"></i> Kirim Kode
                                        </button>
                                    </div>
                                    <div class="form-text">Email ini akan digunakan untuk login</div>
                                </div>

                                <!-- Email Verification Section -->
                                <div class="verification-section" id="verificationSection" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">
                                            <i class="fas fa-shield-alt me-1"></i> Verifikasi Email
                                        </h6>
                                        <span id="emailDisplay" class="text-primary fw-bold"></span>
                                    </div>

                                    <!-- OTP Input -->
                                    <div class="mb-3">
                                        <label for="otp" class="form-label">Kode OTP (6 digit)</label>
                                        <input type="text" class="form-control otp-input" id="otp" name="otp"
                                            maxlength="6" pattern="\d{6}" placeholder="000000">
                                        <div class="d-flex justify-content-between mt-2">
                                            <small class="text-muted" id="countdownText">Kode berlaku selama 5
                                                menit</small>
                                            <span class="countdown" id="countdown">05:00</span>
                                        </div>
                                    </div>

                                    <!-- Verification Buttons -->
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary flex-grow-1" id="btnVerifyOtp">
                                            <i class="fas fa-check-circle me-1"></i> Verifikasi OTP
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" id="btnResendOtp">
                                            <i class="fas fa-redo me-1"></i> Kirim Ulang
                                        </button>
                                    </div>

                                    <!-- Verification Status -->
                                    <div class="mt-3" id="verificationStatus"></div>
                                </div>

                                <!-- Password -->
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="password" class="form-label">
                                            <i class="fas fa-lock me-1"></i> Password <span
                                                class="required-star">*</span>
                                        </label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            required minlength="8" placeholder="Minimal 8 karakter">
                                        <div class="form-text">Gunakan kombinasi huruf, angka, dan simbol</div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label for="password_confirmation" class="form-label">
                                            <i class="fas fa-lock me-1"></i> Konfirmasi Password <span
                                                class="required-star">*</span>
                                        </label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" required placeholder="Ulangi password">
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden field for email verification status -->
                            <input type="hidden" name="email_verified" id="emailVerified" value="0">

                            <!-- reCAPTCHA -->
                            <div class="mb-4 d-flex justify-content-center flex-column align-items-center">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                @error('g-recaptcha-response')
                                    <div class="text-danger mt-2 small"><i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-3">
                                <button type="submit" class="btn btn-primary btn-lg py-3" id="btnRegister" disabled>
                                    <i class="fas fa-paper-plane me-2"></i> Daftarkan Sekolah
                                </button>
                                <div class="text-center">
                                    <span>Sudah punya akun?</span>
                                    <a href="{{ route('login') }}" class="login-link ms-2">
                                        <i class="fas fa-sign-in-alt me-1"></i> Masuk di sini
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- External Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Pass data from Laravel to JavaScript -->
    <script>
        // Global configuration untuk JavaScript
        window.csrfToken = '{{ csrf_token() }}';
        window.routes = {
            verificationSend: '{{ route("verification.send") }}',
            verificationVerify: '{{ route("verification.verify") }}',
            verificationCheck: '{{ route("verification.check") }}',
            login: '{{ route("login") }}'
        };
    </script>

    <!-- Custom JavaScript - Pisahkan ke file terpisah -->
    <script src="{{ asset('js/register.js') }}"></script>
    @include('components.toast-notification')
    @include('components.ai-chatbot')
</body>

</html>