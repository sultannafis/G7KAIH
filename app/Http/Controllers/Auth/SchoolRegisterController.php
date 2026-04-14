<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Address;
use App\Models\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravolt\Indonesia\Facade as Indonesia;

class SchoolRegisterController extends Controller
{
    public function showRegister()
    {
        $provinces = Indonesia::allProvinces();
        return view('auth.register', compact('provinces'));
    }

    public function register(Request $request)
    {
        // Tambah debug logging
        \Log::info('Registration attempt:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'g-recaptcha-response' => 'required',
            'school_name' => 'required|string|max:255',
            'npsn' => 'required|string|size:8|unique:schools,npsn',
            'province_id' => 'required|exists:indonesia_provinces,id',
            'city_id' => 'required|exists:indonesia_cities,id',
            'district_id' => 'required|exists:indonesia_districts,id',
            'village_id' => 'required|exists:indonesia_villages,id',
            'address_detail' => 'required|string|max:500',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'timezone' => 'required|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
            'admin_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'g-recaptcha-response.required' => 'Harap centang kotak "I\'m not a robot".',
            'npsn.unique' => 'NPSN sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone_number.required' => 'Nomor telepon harus diisi.',
        ]);

        $validator->after(function ($validator) use ($request) {
            // Verify Recaptcha
            if ($request->filled('g-recaptcha-response')) {
                $response = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => env('RECAPTCHA_SECRET_KEY'),
                    'response' => $request->input('g-recaptcha-response'),
                    'remoteip' => $request->ip()
                ]);
                if (!$response->successful() || !$response->json('success')) {
                    $validator->errors()->add('g-recaptcha-response', 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.');
                }
            }

            $verification = EmailVerification::where('email', $request->email)
                ->whereNotNull('verified_at')
                ->where('expired_at', '>', now())
                ->first();

            if (!$verification) {
                $validator->errors()->add('email', 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu.');
            }
        });

        if ($validator->fails()) {
            \Log::error('Validation failed:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // 1. Create School
            $school = School::create([
                'name' => $request->school_name,
                'npsn' => $request->npsn,
                'timezone' => $request->timezone,
                'status' => 'pending',
            ]);

            \Log::info('School created:', $school->toArray());

            // 2. Create Address for School
            $address = new Address([
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'district_id' => $request->district_id,
                'village_id' => $request->village_id,
                'address_detail' => $request->address_detail, // PERBAIKI DISINI
                'postal_code' => $request->postal_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            $school->addresses()->save($address);
            \Log::info('Address created:', $address->toArray());

            // 3. Create Admin User
            $admin = User::create([
                'school_id' => $school->id,
                'name' => $request->admin_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'is_active' => false,
                'email_verified_at' => now(),
            ]);

            \Log::info('User created:', $admin->toArray());

            // 4. Send notifications
            $this->notifyMasterAdmin($school);
            $this->notifyAdmin($admin, $school);

            // 5. Clear email verification data
            EmailVerification::where('email', $request->email)->delete();

            DB::commit();
            
            \Log::info('Registration successful for email:', [$request->email]);

            $message = "Pendaftaran sekolah {$school->name} berhasil! Akun sedang menunggu persetujuan Master Admin.";
            session()->flash('success', $message);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'redirect' => route('login')
                ]);
            }

            return redirect()->route('login');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Registration error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat Pendaftaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    protected function notifyMasterAdmin(School $school)
    {
        $masterAdmins = User::where('role', 'masteradmin')->get();
        
        foreach ($masterAdmins as $masterAdmin) {
            \App\Models\Notification::create([
                'user_id' => $masterAdmin->id,
                'title' => 'Pendaftaran Sekolah Baru',
                'message' => "Sekolah {$school->name} (NPSN: {$school->npsn}) mendaftar dan menunggu persetujuan.",
            ]);
        }
    }

    protected function notifyAdmin(User $admin, School $school)
    {
        \App\Models\Notification::create([
            'user_id' => $admin->id,
            'title' => 'Pendaftaran Berhasil',
            'message' => "Pendaftaran sekolah {$school->name} berhasil. Akun sedang menunggu persetujuan Master Admin.",
        ]);
    }
}