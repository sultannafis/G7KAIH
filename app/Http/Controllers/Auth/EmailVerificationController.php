<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    /**
     * Kirim OTP ke email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Cek apakah email sudah terdaftar di users
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Email sudah terdaftar.'
            ], 422);
        }

        $otp = rand(100000, 999999);

        EmailVerification::updateOrCreate(
            ['email' => $request->email],
            [
                'code' => $otp,
                'expired_at' => Carbon::now()->addMinutes(5),
                'verified_at' => null
            ]
        );

        // Kirim email OTP
        Mail::send('emails.verification', ['otp' => $otp], function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Kode Verifikasi Email - G7KAIH');
        });

        return response()->json([
            'message' => 'Kode verifikasi telah dikirim ke email.'
        ]);
    }

    /**
     * Verifikasi OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6'
        ]);

        $verification = EmailVerification::where('email', $request->email)
            ->where('code', $request->otp)
            ->first();

        if (!$verification) {
            return response()->json([
                'message' => 'OTP yang anda masukkan salah.'
            ], 422);
        }

        if (Carbon::now()->gt($verification->expired_at)) {
            return response()->json([
                'message' => 'Kode OTP sudah kadaluarsa.'
            ], 422);
        }

        // Update verification timestamp
        $verification->update([
            'verified_at' => Carbon::now()
        ]);

        return response()->json([
            'message' => 'Email anda berhasil diverifikasi.',
            'verified' => true
        ]);
    }

    /**
     * Cek status verifikasi email
     */
    public function checkVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $verification = EmailVerification::where('email', $request->email)
            ->whereNotNull('verified_at')
            ->where('expired_at', '>', Carbon::now())
            ->first();

        return response()->json([
            'verified' => $verification !== null
        ]);
    }
}