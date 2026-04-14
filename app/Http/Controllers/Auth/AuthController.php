<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Parents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $this->validateRecaptcha($request);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $user = Auth::user();
        return $this->validateAndRedirect($user);
    }

    public function loginSiswa(Request $request)
    {
        $request->validate([
            'student_code' => 'required|string',
        ]);

        $this->validateRecaptcha($request);

        $student = Student::where('nis', $request->student_code)
            ->orWhere('nisn', $request->student_code)
            ->first();

        if (!$student || !$student->user) {
            throw ValidationException::withMessages([
                'student_code' => 'Siswa tidak ditemukan.',
            ]);
        }

        Auth::login($student->user);
        return $this->validateAndRedirect($student->user);
    }

    public function loginOrangTua(Request $request)
    {
        $request->validate([
            'login_code' => 'required|string',
        ]);

        $this->validateRecaptcha($request);

        $parent = Parents::where('login_code', $request->login_code)->first();

        if (!$parent || !$parent->user) {
            throw ValidationException::withMessages([
                'login_code' => 'Kode login tidak ditemukan. Hubungi admin sekolah untuk mendapatkan kode login.',
            ]);
        }

        Auth::login($parent->user);
        return $this->validateAndRedirect($parent->user);
    }

    public function loginWithBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $barcodeData = $request->barcode;
        $nis = $barcodeData;
        $nisn = $barcodeData;

        // Try to handle "STUDENT-nis-nisn" format first
        if (strpos($barcodeData, 'STUDENT-') === 0) {
            $parts = explode('-', $barcodeData);
            if (count($parts) === 3) {
                $nis = $parts[1];
                $nisn = $parts[2];
            }
        }

        $student = Student::where('nis', $nis)
            ->orWhere('nisn', $nisn)
            ->first();

        if ($student && $student->user) {
            $user = $student->user;

            $validationResult = $this->validateUser($user);
            if ($validationResult !== true) {
                return response()->json([
                    'success' => false,
                    'message' => $validationResult
                ], 401);
            }

            Auth::login($user);
            $request->session()->regenerate();

            $message = 'Selamat datang ' . $user->name . '!';
            session()->flash('success', $message);

            return response()->json([
                'success' => true,
                'redirect' => route('dashboard.student'),
                'message' => $message
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'NIS/NISN tidak ditemukan.'
        ], 404);
    }

    public function generateBarcode(Request $request)
    {
        $request->validate([
            'student_code' => 'required|string',
        ]);

        $student = Student::where('nis', $request->student_code)
            ->orWhere('nisn', $request->student_code)
            ->first();

        if (!$student || !$student->user) {
            return response()->json([
                'barcode_url' => null,
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }

        $barcodeData = "STUDENT-{$student->nis}-{$student->nisn}";
        $qrCode = QrCode::size(300)->generate($barcodeData);
        $barcodeUrl = 'data:image/svg+xml;base64,' . base64_encode($qrCode);

        return response()->json([
            'barcode_url' => $barcodeUrl,
            'barcode_data' => $barcodeData,
            'student' => [
                'name' => $student->user->name,
                'nis' => $student->nis,
                'nisn' => $student->nisn
            ]
        ]);
    }

    protected function validateUser($user)
    {
        if (!$user->email_verified_at) {
            return 'Email belum diverifikasi.';
        }

        if (!$user->is_active) {
            return 'Akun tidak aktif.';
        }

        if ($user->role !== 'masteradmin' && (!$user->school || $user->school->status !== 'active')) {
            return 'Sekolah belum disetujui oleh sistem.';
        }

        return true;
    }

    protected function validateAndRedirect($user)
    {
        $validationResult = $this->validateUser($user);
        if ($validationResult !== true) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => $validationResult,
            ]);
        }

        request()->session()->regenerate();
        return redirect()->route($this->redirectRouteByRole($user->role))->with('success', 'Selamat datang ' . $user->name . '!');
    }

    protected function redirectRouteByRole(string $role): string
    {
        return match ($role) {
            'masteradmin' => 'dashboard.masteradmin',
            'admin'       => 'dashboard.admin',
            'guru'        => 'dashboard.teacher',
            'siswa'       => 'dashboard.student',
            'orangtua'    => 'dashboard.parent',
            default       => 'login',
        };
    }

    protected function validateRecaptcha(Request $request)
    {
        $recaptcha_response = $request->input('g-recaptcha-response');
        if (empty($recaptcha_response)) {
             throw ValidationException::withMessages([
                 'g-recaptcha-response' => 'Harap centang kotak "I\'m not a robot".',
             ]);
        }
        $response = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $recaptcha_response,
            'remoteip' => $request->ip()
        ]);
        if (!$response->successful() || !$response->json('success')) {
             throw ValidationException::withMessages([
                 'g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
             ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar (logout).');
    }
}