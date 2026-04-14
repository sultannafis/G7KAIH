<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SchoolSettingController extends Controller
{
    public function edit()
    {
        return redirect()->route('profile.edit', ['#school-settings']);
    }

    public function update(Request $request)
    {
        $school = School::findOrFail(auth()->user()->school_id);

        $validated = $request->validate([
            // School fields
            'name' => 'required|string|max:255',
            'npsn' => ['required', 'string', 'max:20', Rule::unique('schools', 'npsn')->ignore($school->id)],
            'timezone' => 'required|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Address fields
            'province_id' => 'nullable|exists:indonesia_provinces,id',
            'city_id' => 'nullable|exists:indonesia_cities,id',
            'district_id' => 'nullable|exists:indonesia_districts,id',
            'village_id' => 'nullable|exists:indonesia_villages,id',
            'address_detail' => 'nullable|string',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        DB::beginTransaction();

        try {
            // Handle logo upload
            if ($request->hasFile('logo')) {
                if ($school->logo_path) {
                    Storage::disk('public')->delete($school->logo_path);
                }
                $logoPath = $request->file('logo')->store('schools/logos', 'public');
                $school->logo_path = $logoPath;
            }

            // Update school basic details
            $school->update([
                'name' => $validated['name'],
                'npsn' => $validated['npsn'],
                'timezone' => $validated['timezone'],
            ]);

            // Update or create address
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
                $school->addresses()->delete();
            }

            DB::commit();

            return redirect()->route('profile.edit', ['#school-settings'])->with('success', 'Detail sekolah berhasil diperbarui.');

        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Update school setting failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal memperbarui data sekolah.')
                ->withInput();
        }
    }
}
