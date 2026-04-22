<?php

namespace App\Http\Controllers\MasterAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Laravolt\Indonesia\Facade as Indonesia;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

use App\Services\Notification\NotificationService;

class SchoolController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService,
    ) {}

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Default 10 items per page
        $search = $request->get('search');
        $status = $request->get('status');

        $schools = School::query()
            ->withCount('users')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('npsn', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString(); // Keep filter parameters in pagination links

        return view('masteradmin.schools.list', compact('schools'));
    }

    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        
        return view('masteradmin.schools.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // School fields
            'name' => 'required|string|max:255',
            'npsn' => 'required|string|max:20|unique:schools,npsn',
            'timezone' => 'required|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:pending,active,in_active,rejected',

            // Address fields
            'province_id' => 'nullable|exists:indonesia_provinces,id',
            'city_id' => 'nullable|exists:indonesia_cities,id',
            'district_id' => 'nullable|exists:indonesia_districts,id',
            'village_id' => 'nullable|exists:indonesia_villages,id',
            'address_detail' => 'nullable|string',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

            // Admin fields
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_phone' => 'nullable|string|max:20',
            'admin_religion' => 'nullable|string|max:50',
            'admin_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&_]/',
                'confirmed'
            ],
        ]);

        DB::beginTransaction();

        try {
            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('schools/logos', 'public');
            }

            // 1. Create School
            $school = School::create([
                'name' => $validated['name'],
                'npsn' => $validated['npsn'],
                'timezone' => $validated['timezone'],
                'qr_logo1_path' => $logoPath,
                'status' => $validated['status'],
            ]);

            // 2. Create Address
            if (
                $request->filled('province_id') ||
                $request->filled('address_detail')
            ) {
                $address = new Address([
                    'province_id'   => $validated['province_id'] ?? null,
                    'city_id'       => $validated['city_id'] ?? null,
                    'district_id'   => $validated['district_id'] ?? null,
                    'village_id'    => $validated['village_id'] ?? null,
                    'address_detail'=> $validated['address_detail'] ?? null,
                    'postal_code'   => $validated['postal_code'] ?? null,
                    'latitude'      => $validated['latitude'] ?? null,
                    'longitude'     => $validated['longitude'] ?? null,
                ]);

                $school->addresses()->save($address);
            }

            // 3. Create Admin User
            User::create([
                'school_id' => $school->id,
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'phone_number' => $validated['admin_phone'] ?? null,
                'religion' => $validated['admin_religion'] ?? null,
                'role' => 'admin',
                'password' => Hash::make($validated['admin_password']),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('masteradmin.schools.list')
                ->with('success', 'Sekolah dan admin berhasil ditambahkan.');

        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Store school failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menyimpan data sekolah.')
                ->withInput();
        }
    }

    public function show(School $school)
    {
        return view('masteradmin.schools.show', [
            'school' => $school->load('users'),
            'admin'  => User::where('school_id', $school->id)
                            ->where('role', 'admin')
                            ->first(),
        ]);
    }

    public function edit(School $school)
    {
        // SAMA PERSIS DENGAN CREATE
        $provinces = Province::orderBy('name')->get();
        $school->load('addresses');
        
        // Get admin user
        $admin = User::where('school_id', $school->id)
                    ->where('role', 'admin')
                    ->first();
        
        return view('masteradmin.schools.edit', compact('school', 'admin', 'provinces'));
    }

    public function update(Request $request, School $school)
    {
        $admin = User::where('school_id', $school->id)
                    ->where('role', 'admin')
                    ->first();
        
        $validated = $request->validate([
            // School fields
            'name' => 'required|string|max:255',
            'npsn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('schools', 'npsn')->ignore($school->id)
            ],
            'timezone' => 'required|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:pending,active,in_active,rejected',
            'is_wa_enabled' => 'boolean',
            'is_email_enabled' => 'boolean',
            
            // Address fields - SAMA PERSIS DENGAN STORE
            'province_id' => 'nullable|exists:indonesia_provinces,id',
            'city_id' => 'nullable|exists:indonesia_cities,id',
            'district_id' => 'nullable|exists:indonesia_districts,id',
            'village_id' => 'nullable|exists:indonesia_villages,id',
            'address_detail' => 'nullable|string',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            
            // Admin fields
            'admin_name' => 'required|string|max:255',
            'admin_email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($admin?->id)
            ],
            'admin_phone' => 'nullable|string|max:20',
            'admin_religion' => 'nullable|string|max:50',
            'admin_password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&_]/',
                'confirmed'
            ],
        ]);

        DB::beginTransaction();

        try {
            // Handle logo upload - SAMA DENGAN STORE
            if ($request->hasFile('logo')) {
                if ($school->qr_logo1_path) {
                    Storage::disk('public')->delete($school->qr_logo1_path);
                }
                
                $logoPath = $request->file('logo')->store('schools/logos', 'public');
                $school->qr_logo1_path = $logoPath;
            }

            // Update school
            $school->update([
                'name' => $validated['name'],
                'npsn' => $validated['npsn'],
                'timezone' => $validated['timezone'],
                'status' => $validated['status'],
                'is_wa_enabled' => $validated['is_wa_enabled'] ?? false,
                'is_email_enabled' => $validated['is_email_enabled'] ?? false,
            ]);

            // Update or create address - SAMA LOGIKA DENGAN STORE
            if ($request->filled('province_id') || $request->filled('address_detail')) {
                $school->addresses()->updateOrCreate(
                    ['addressable_id' => $school->id, 'addressable_type' => School::class],
                    [
                        'province_id' => $validated['province_id'] ?? null,
                        'city_id' => $validated['city_id'] ?? null,
                        'district_id' => $validated['district_id'] ?? null,
                        'village_id' => $validated['village_id'] ?? null,
                        'address_detail' => $validated['address_detail'] ?? null,
                        'postal_code' => $validated['postal_code'] ?? null,
                        'latitude' => $validated['latitude'] ?? null,
                        'longitude' => $validated['longitude'] ?? null,
                    ]
                );
            } else {
                // If no address data, delete existing address
                $school->addresses()->delete();
            }

            // Prepare admin data - SAMA DENGAN STORE
            $adminData = [
                'school_id' => $school->id,
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'phone_number' => $validated['admin_phone'] ?? null,
                'religion' => $validated['admin_religion'] ?? null,
                'role' => 'admin',
                'is_active' => true,
            ];

            // Only update password if provided
            if ($request->filled('admin_password')) {
                $adminData['password'] = Hash::make($validated['admin_password']);
            }

            if ($admin) {
                $admin->update($adminData);
            } else {
                $adminData['password'] = Hash::make($validated['admin_password'] ?? 'password123');
                $adminData['email_verified_at'] = now();
                User::create($adminData);
            }

            DB::commit();

            return redirect()
                ->route('masteradmin.schools.list')
                ->with('success', 'Sekolah dan admin berhasil diperbarui.');

        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Update school failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal memperbarui data sekolah.')
                ->withInput();
        }
    }

    public function destroy(School $school)
    {
        if ($school->users()->count() > 0) {
            return redirect()
                ->route('masteradmin.schools.list')
                ->with('error', 'Tidak dapat menghapus sekolah yang masih memiliki pengguna.');
        }

        DB::transaction(function () use ($school) {
            if ($school->qr_logo1_path) {
                Storage::disk('public')->delete($school->qr_logo1_path);
            }

            $school->addresses()->delete();
            $school->delete();
        });

        return redirect()
            ->route('masteradmin.schools.list')
            ->with('success', 'Sekolah berhasil dihapus.');
    }

    public function toggleStatus(School $school)
    {
        $previousStatus = $school->status;
        $newStatus      = $previousStatus === 'active' ? 'in_active' : 'active';

        $school->update(['status' => $newStatus]);

        $label = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Sekolah berhasil {$label}.");
    }
}