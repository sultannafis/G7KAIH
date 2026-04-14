<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\SchoolRegisterController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;
use Laravolt\Indonesia\Facade as Indonesia;

// ========================
// LOGIN
// ========================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/login/siswa', [AuthController::class, 'loginSiswa'])->name('login.siswa');
Route::post('/login/orangtua', [AuthController::class, 'loginOrangTua'])->name('login.orangtua');
Route::post('/login/barcode', [AuthController::class, 'loginWithBarcode'])->name('login.barcode');
Route::post('/student/barcode', [AuthController::class, 'generateBarcode'])->name('student.barcode');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========================
// REGISTER SEKOLAH
// ========================
Route::get('/register', [SchoolRegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [SchoolRegisterController::class, 'register'])->name('register.store');

// ========================
// PASSWORD RESET
// ========================
Route::middleware('guest')->group(function () {
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// ========================
// EMAIL VERIFICATION
// ========================
Route::prefix('verify-email')->group(function () {
    Route::post('/send-otp', [EmailVerificationController::class, 'sendOtp'])
        ->name('verification.send');
    
    Route::post('/verify-otp', [EmailVerificationController::class, 'verifyOtp'])
        ->name('verification.verify');
    
    Route::post('/check', [EmailVerificationController::class, 'checkVerification'])
        ->name('verification.check');
});

// ========================
// API FOR ADDRESS (Indonesia Region)
// ========================
Route::prefix('api')->group(function () {
    Route::get('/cities/{provinceId}', function ($provinceId) {
        $cities = Indonesia::findProvince($provinceId, ['cities'])->cities;
        return response()->json($cities);
    });
    
    Route::get('/districts/{cityId}', function ($cityId) {
        $districts = Indonesia::findCity($cityId, ['districts'])->districts;
        return response()->json($districts);
    });
    
    Route::get('/villages/{districtId}', function ($districtId) {
        $villages = Indonesia::findDistrict($districtId, ['villages'])->villages;
        return response()->json($villages);
    });
});