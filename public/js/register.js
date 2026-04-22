/**
 * Register Page Script
 * Handles registration form, OTP verification, and map functionality
 */

class RegistrationForm {
    constructor() {
        this.countdownInterval = null;
        this.timeLeft = 300;
        this.emailVerified = false;
        this.currentEmail = '';
        this.map = null;
        this.marker = null;

        this.init();
    }

    init() {
        this.initMap();
        this.bindEvents();
        this.initFormState();
    }

    /**
     * Initialize Leaflet Map
     */
    initMap() {
        // Default to Indonesia center
        this.map = L.map('map').setView([-2.5489, 118.0149], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(this.map);

        // Add click event to map
        this.map.on('click', (e) => {
            this.handleMapClick(e);
        });
    }

    /**
     * Handle map click event
     */
    handleMapClick(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        $('#latitude').val(lat.toFixed(6));
        $('#longitude').val(lng.toFixed(6));

        // Update or add marker
        if (this.marker) {
            this.marker.setLatLng([lat, lng]);
        } else {
            this.marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(this.map);

            // Add dragend event
            this.marker.on('dragend', (event) => {
                const position = this.marker.getLatLng();
                $('#latitude').val(position.lat.toFixed(6));
                $('#longitude').val(position.lng.toFixed(6));
            });
        }

        // Reverse geocode to get address
        this.reverseGeocode(lat, lng);
    }

    /**
     * Get current location
     */
    getCurrentLocation() {
        if (navigator.geolocation) {
            Swal.fire({
                title: 'Mendapatkan lokasi...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    $('#latitude').val(lat.toFixed(6));
                    $('#longitude').val(lng.toFixed(6));

                    // Center map on location
                    this.map.setView([lat, lng], 15);

                    // Update or add marker
                    if (this.marker) {
                        this.marker.setLatLng([lat, lng]);
                    } else {
                        this.marker = L.marker([lat, lng], {
                            draggable: true
                        }).addTo(this.map);

                        this.marker.on('dragend', (event) => {
                            const position = this.marker.getLatLng();
                            $('#latitude').val(position.lat.toFixed(6));
                            $('#longitude').val(position.lng.toFixed(6));
                        });
                    }

                    this.reverseGeocode(lat, lng);
                    Swal.close();
                },
                (error) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Tidak dapat mendapatkan lokasi: ' + error.message
                    });
                }
            );
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Tidak didukung',
                text: 'Browser Anda tidak mendukung geolocation'
            });
        }
    }

    /**
     * Reverse geocode function
     */
    reverseGeocode(lat, lng) {
        $.get(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`, (data) => {
            if (data.address) {
                // Update address fields if empty
                if (!$('#address_detail').val()) {
                    let addressParts = [];
                    if (data.address.road) addressParts.push(data.address.road);
                    if (data.address.house_number) addressParts.push(data.address.house_number);

                    if (addressParts.length > 0) {
                        $('#address_detail').val(addressParts.join(' '));
                    }
                }
            }
        });
    }

    /**
     * Load cities based on province
     */
    loadCities(provinceId) {
        if (provinceId) {
            $('#city_id').prop('disabled', false);
            $.get(`/api/cities/${provinceId}`, (cities) => {
                $('#city_id').empty().append('<option value="">Pilih Kota/Kabupaten</option>');
                cities.forEach(city => {
                    $('#city_id').append(`<option value="${city.id}">${city.name}</option>`);
                });
            }).fail(() => {
                Swal.fire('Error', 'Gagal memuat data kota', 'error');
            });
        } else {
            $('#city_id').prop('disabled', true).empty().append('<option value="">Pilih Kota/Kabupaten</option>');
            $('#district_id').prop('disabled', true).empty().append('<option value="">Pilih Kecamatan</option>');
            $('#village_id').prop('disabled', true).empty().append('<option value="">Pilih Kelurahan</option>');
        }
    }

    /**
     * Load districts based on city
     */
    loadDistricts(cityId) {
        if (cityId) {
            $('#district_id').prop('disabled', false);
            $.get(`/api/districts/${cityId}`, (districts) => {
                $('#district_id').empty().append('<option value="">Pilih Kecamatan</option>');
                districts.forEach(district => {
                    $('#district_id').append(`<option value="${district.id}">${district.name}</option>`);
                });
            }).fail(() => {
                Swal.fire('Error', 'Gagal memuat data kecamatan', 'error');
            });
        } else {
            $('#district_id').prop('disabled', true).empty().append('<option value="">Pilih Kecamatan</option>');
            $('#village_id').prop('disabled', true).empty().append('<option value="">Pilih Kelurahan</option>');
        }
    }

    /**
     * Load villages based on district
     */
    loadVillages(districtId) {
        if (districtId) {
            $('#village_id').prop('disabled', false);
            $.get(`/api/villages/${districtId}`, (villages) => {
                $('#village_id').empty().append('<option value="">Pilih Kelurahan</option>');
                villages.forEach(village => {
                    $('#village_id').append(`<option value="${village.id}">${village.name}</option>`);
                });
            }).fail(() => {
                Swal.fire('Error', 'Gagal memuat data kelurahan', 'error');
            });
        } else {
            $('#village_id').prop('disabled', true).empty().append('<option value="">Pilih Kelurahan</option>');
        }
    }

    /**
     * Send OTP to email
     */
    sendOTP() {
        const email = $('#email').val().trim();

        if (!email) {
            Swal.fire('Error', 'Silakan masukkan email terlebih dahulu', 'error');
            return;
        }

        if (!this.validateEmail(email)) {
            Swal.fire('Error', 'Format email tidak valid', 'error');
            return;
        }

        this.currentEmail = email;

        const $btn = $('#btnSendOtp');
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...');

        $.ajax({
            url: window.routes.verificationSend,
            method: 'POST',
            data: {
                email: email,
                _token: window.csrfToken
            },
            success: (response) => {
                $('#verificationSection').show();
                $('#emailDisplay').text(email);
                $('#otp').val('');
                this.timeLeft = 300;
                this.startCountdown();

                $('#otp').prop('disabled', false);
                $('#btnVerifyOtp').prop('disabled', false);
                $('#btnResendOtp').prop('disabled', false);

                $('#verificationStatus').html('');
                this.emailVerified = false;
                $('#emailVerified').val('0');
                $('#btnRegister').prop('disabled', true);

                Swal.fire('Berhasil', 'Kode OTP telah dikirim ke email Anda', 'success');
                $btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Kirim Kode');
            },
            error: (xhr) => {
                let message = 'Gagal mengirim kode OTP';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Error', message, 'error');
                $btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Kirim Kode');
            }
        });
    }

    /**
     * Verify OTP
     */
    verifyOTP() {
        const email = $('#email').val().trim();
        const otp = $('#otp').val().trim();

        if (!email) {
            Swal.fire('Error', 'Email tidak ditemukan', 'error');
            return;
        }

        if (!otp || otp.length !== 6 || !/^\d+$/.test(otp)) {
            Swal.fire('Error', 'Masukkan kode OTP 6 digit yang valid', 'error');
            return;
        }

        const $btn = $('#btnVerifyOtp');
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memverifikasi...');

        $.ajax({
            url: window.routes.verificationVerify,
            method: 'POST',
            data: {
                email: email,
                otp: otp,
                _token: window.csrfToken
            },
            success: (response) => {
                if (response.verified || response.message === 'Email anda berhasil diverifikasi.') {
                    this.emailVerified = true;
                    $('#emailVerified').val('1');
                    $('#btnRegister').prop('disabled', false);

                    clearInterval(this.countdownInterval);

                    $('#verificationStatus').html(
                        '<div class="alert alert-success d-flex align-items-center mb-0 p-3">' +
                        '<i class="fas fa-check-circle fa-2x me-3 text-success"></i>' +
                        '<div>' +
                        '<h6 class="mb-1">Email Terverifikasi</h6>' +
                        '<p class="mb-0">Email Anda telah berhasil diverifikasi.</p>' +
                        '</div>' +
                        '</div>'
                    );

                    $('#otp').prop('disabled', true);
                    $btn.prop('disabled', true).html('<i class="fas fa-check-circle me-1"></i> Terverifikasi');
                    $('#btnResendOtp').prop('disabled', true);

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Email berhasil diverifikasi!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', 'Verifikasi gagal', 'error');
                    $btn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> Verifikasi OTP');
                }
            },
            error: (xhr) => {
                let message = 'Gagal memverifikasi OTP';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Error', message, 'error');
                $btn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> Verifikasi OTP');
            }
        });
    }

    /**
     * Resend OTP
     */
    resendOTP() {
        const email = $('#email').val().trim();

        if (!email) {
            Swal.fire('Error', 'Email tidak ditemukan', 'error');
            return;
        }

        const $btn = $('#btnResendOtp');
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...');

        $.ajax({
            url: window.routes.verificationSend,
            method: 'POST',
            data: {
                email: email,
                _token: window.csrfToken
            },
            success: (response) => {
                this.timeLeft = 300;
                this.startCountdown();
                $('#otp').val('');
                $('#otp').prop('disabled', false);
                $('#btnVerifyOtp').prop('disabled', false);
                $('#verificationStatus').html('');
                this.emailVerified = false;
                $('#emailVerified').val('0');
                $('#btnRegister').prop('disabled', true);

                Swal.fire('Berhasil', 'Kode OTP baru telah dikirim', 'success');
                $btn.prop('disabled', false).html('<i class="fas fa-redo me-1"></i> Kirim Ulang');
            },
            error: (xhr) => {
                let message = 'Gagal mengirim ulang kode OTP';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Error', message, 'error');
                $btn.prop('disabled', false).html('<i class="fas fa-redo me-1"></i> Kirim Ulang');
            }
        });
    }

    /**
     * Check if email is already verified
     */
    checkEmailVerification(email) {
        if (email && this.validateEmail(email)) {
            $.ajax({
                url: window.routes.verificationCheck,
                method: 'POST',
                data: {
                    email: email,
                    _token: window.csrfToken
                },
                success: (response) => {
                    if (response.verified) {
                        this.emailVerified = true;
                        $('#emailVerified').val('1');
                        $('#btnRegister').prop('disabled', false);
                        $('#verificationSection').show();
                        $('#emailDisplay').text(email);
                        $('#verificationStatus').html(
                            '<div class="alert alert-success d-flex align-items-center mb-0 p-3">' +
                            '<i class="fas fa-check-circle fa-2x me-3 text-success"></i>' +
                            '<div>' +
                            '<h6 class="mb-1">Email Terverifikasi</h6>' +
                            '<p class="mb-0">Email ini sudah terverifikasi sebelumnya.</p>' +
                            '</div>' +
                            '</div>'
                        );
                        $('#otp').prop('disabled', true);
                        $('#btnVerifyOtp').prop('disabled', true);
                        $('#btnResendOtp').prop('disabled', true);
                    } else {
                        this.emailVerified = false;
                        $('#emailVerified').val('0');
                        $('#btnRegister').prop('disabled', true);
                    }
                },
                error: () => {
                    this.emailVerified = false;
                    $('#emailVerified').val('0');
                    $('#btnRegister').prop('disabled', true);
                }
            });
        }
    }

    /**
     * Submit registration form
     */
    submitForm(e) {
        e.preventDefault();

        if (!this.validateForm()) {
            return false;
        }

        if (!this.emailVerified) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan verifikasi email terlebih dahulu',
                confirmButtonText: 'OK'
            });
            return false;
        }

        Swal.fire({
            title: 'Konfirmasi Pendaftaran',
            html: 'Apakah data yang Anda masukkan sudah benar?<br><small>Pastikan semua informasi telah sesuai</small>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1e88e5',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-paper-plane me-1"></i> Ya, Daftarkan',
            cancelButtonText: '<i class="fas fa-edit me-1"></i> Periksa Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                const $submitBtn = $('#btnRegister');
                $submitBtn.prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm me-2"></span>Mendaftarkan...');

                const formData = new FormData(document.getElementById('registerForm'));

                $.ajax({
                    url: $('#registerForm').attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: (response) => {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            window.location.href = window.routes.login;
                        }
                    },
                    error: (xhr) => {
                        $submitBtn.prop('disabled', false)
                            .html('<i class="fas fa-paper-plane me-2"></i> Daftarkan Sekolah');

                        let message = 'Terjadi kesalahan saat mendaftar';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            try {
                                const errors = JSON.parse(xhr.responseText);
                                if (errors.errors) {
                                    let errorMessages = [];
                                    for (const field in errors.errors) {
                                        errorMessages.push(errors.errors[field][0]);
                                    }
                                    message = errorMessages.join('<br>');
                                }
                            } catch (e) {
                                message = xhr.responseText.substring(0, 200);
                            }
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: message,
                            confirmButtonColor: '#1e88e5'
                        });
                    }
                });
            }
        });

        return false;
    }

    /**
     * Validate entire form
     */
    validateForm() {
        let isValid = true;
        let errorMessage = '';

        // Validasi NPSN
        const npsn = $('#npsn').val();
        if (npsn.length !== 8 || !/^\d+$/.test(npsn)) {
            errorMessage += '• NPSN harus terdiri dari 8 digit angka<br>';
            isValid = false;
        }

        // Validasi phone number
        const phone = $('#phone_number').val();
        if (!phone || !/^\d{10,13}$/.test(phone)) {
            errorMessage += '• Nomor telepon harus 10-13 digit angka<br>';
            isValid = false;
        }

        // Validasi password
        const password = $('#password').val();
        const confirmPassword = $('#password_confirmation').val();
        if (password.length < 8) {
            errorMessage += '• Password minimal 8 karakter<br>';
            isValid = false;
        } else if (!/(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&_])/.test(password)) {
            errorMessage += '• Password harus memiliki kombinasi huruf besar, angka, dan simbol<br>';
            isValid = false;
        }
        if (password !== confirmPassword) {
            errorMessage += '• Password dan konfirmasi password tidak cocok<br>';
            isValid = false;
        }

        // Validasi email
        const email = $('#email').val();
        if (!this.validateEmail(email)) {
            errorMessage += '• Format email tidak valid<br>';
            isValid = false;
        }

        // Validasi required fields
        const requiredFields = [
            'school_name', 'province_id', 'city_id',
            'district_id', 'village_id', 'address_detail',
            'admin_name', 'timezone'
        ];

        requiredFields.forEach(field => {
            if (!$(`#${field}`).val()) {
                const fieldName = $(`#${field}`).attr('placeholder') || field.replace('_', ' ');
                errorMessage += `• ${fieldName} harus diisi<br>`;
                isValid = false;
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: errorMessage,
                confirmButtonColor: '#1e88e5'
            });
        }

        return isValid;
    }

    /**
     * Validate email format
     */
    validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    /**
     * Start countdown timer
     */
    startCountdown() {
        clearInterval(this.countdownInterval);
        this.updateCountdownDisplay();

        this.countdownInterval = setInterval(() => {
            this.timeLeft--;
            this.updateCountdownDisplay();

            if (this.timeLeft <= 0) {
                clearInterval(this.countdownInterval);
                $('#countdown').text('Kadaluarsa');
                $('#btnVerifyOtp').prop('disabled', true);
                $('#otp').prop('disabled', true);
                $('#verificationStatus').html(
                    '<div class="alert alert-danger d-flex align-items-center mb-0 p-3">' +
                    '<i class="fas fa-exclamation-triangle fa-2x me-3 text-danger"></i>' +
                    '<div>' +
                    '<h6 class="mb-1">Kode Kadaluarsa</h6>' +
                    '<p class="mb-0">Kode OTP telah kadaluarsa. Silakan minta kode baru.</p>' +
                    '</div>' +
                    '</div>'
                );
            }
        }, 1000);
    }

    /**
     * Update countdown display
     */
    updateCountdownDisplay() {
        const minutes = Math.floor(this.timeLeft / 60);
        const seconds = this.timeLeft % 60;
        $('#countdown').text(`${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);
    }

    /**
     * Real-time field validation
     */
    setupFieldValidation() {
        // NPSN validation
        $('#npsn').on('input', function () {
            const npsn = $(this).val();
            if (npsn.length === 8 && /^\d+$/.test(npsn)) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else if (npsn.length > 0) {
                $(this).removeClass('is-valid').addClass('is-invalid');
            } else {
                $(this).removeClass('is-valid is-invalid');
            }
        });

        // Phone validation
        $('#phone_number').on('input', function () {
            const phone = $(this).val();
            if (phone && /^\d{10,13}$/.test(phone)) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else if (phone.length > 0) {
                $(this).removeClass('is-valid').addClass('is-invalid');
            } else {
                $(this).removeClass('is-valid is-invalid');
            }
        });

        // Password validation
        $('#password, #password_confirmation').on('input', () => {
            const password = $('#password').val();
            const confirmPassword = $('#password_confirmation').val();
            
            const isValidPassword = password.length >= 8 && /(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&_])/.test(password);

            if (isValidPassword) {
                $('#password').removeClass('is-invalid').addClass('is-valid');
            } else if (password.length > 0) {
                $('#password').removeClass('is-valid').addClass('is-invalid');
            } else {
                $('#password').removeClass('is-valid is-invalid');
            }

            if (confirmPassword === password && isValidPassword) {
                $('#password_confirmation').removeClass('is-invalid').addClass('is-valid');
            } else if (confirmPassword.length > 0) {
                $('#password_confirmation').removeClass('is-valid').addClass('is-invalid');
            } else {
                $('#password_confirmation').removeClass('is-valid is-invalid');
            }
        });

        // Email blur validation
        $('#email').on('blur', () => {
            const email = $('#email').val().trim();
            this.checkEmailVerification(email);
        });
    }

    /**
     * Bind all events
     */
    bindEvents() {
        // Location button
        $('#btnGetLocation').on('click', () => this.getCurrentLocation());

        // Address cascade
        $('#province_id').on('change', (e) => this.loadCities($(e.target).val()));
        $('#city_id').on('change', (e) => this.loadDistricts($(e.target).val()));
        $('#district_id').on('change', (e) => this.loadVillages($(e.target).val()));

        // OTP actions
        $('#btnSendOtp').on('click', () => this.sendOTP());
        $('#btnVerifyOtp').on('click', () => this.verifyOTP());
        $('#btnResendOtp').on('click', () => this.resendOTP());

        // Form submission
        $('#registerForm').on('submit', (e) => this.submitForm(e));

        // Field validation
        this.setupFieldValidation();
    }

    /**
     * Initialize form state
     */
    initFormState() {
        $('#btnRegister').prop('disabled', true);
    }
}

// Initialize when document is ready
$(document).ready(function () {
    new RegistrationForm();
});