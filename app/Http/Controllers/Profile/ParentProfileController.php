<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use App\Services\Media\MediaUploadService;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ParentProfileController extends Controller
{
    public function __construct(protected MediaUploadService $mediaService) {}

    public function show()
    {
        $user = auth()->user()->load(['parent.student.user', 'school']);
        return view('profile.parent.show', compact('user'));
    }

    public function edit()
    {
        return redirect()->route('profile.parent.show');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone_number'   => ['nullable', 'string', 'max:20'],
            'religion'       => ['nullable', 'string', 'max:50'],
            'avatar'         => ['nullable', 'image', 'max:2048'],
            'signature_file' => ['nullable', 'image', 'max:2048'],
            'signature_data' => ['nullable', 'string'],
        ]);

        // ── Avatar upload ─────────────────────────────────────────────
        if ($request->hasFile('avatar')) {
            $media = $this->mediaService->upload($request->file('avatar'));
            $user->avatar_url = $media->url;
        }

        // ── Signature: tentukan apakah ada input baru ─────────────────
        $hasNewSignatureFile   = $request->hasFile('signature_file');
        $hasNewSignatureCanvas = $request->filled('signature_data')
            && str_starts_with($request->signature_data, 'data:image');

        if ($hasNewSignatureFile || $hasNewSignatureCanvas) {
            // Hapus signature LAMA dari Cloudinary + DB ─────────────────
            if ($user->signature_media_id) {
                $oldMedia = MediaFile::find($user->signature_media_id);
                if ($oldMedia) {
                    try {
                        $cloudinary = new Cloudinary(config('cloudinary.cloud_url'));
                        $cloudinary->uploadApi()->destroy(
                            $oldMedia->cloudinary_public_id,
                            ['resource_type' => 'image']
                        );
                    } catch (\Throwable $e) {
                        // Log tapi jangan crash jika gagal hapus dari Cloudinary
                        logger()->warning('Gagal hapus Cloudinary lama: ' . $e->getMessage());
                    }
                    $oldMedia->delete();
                }
                $user->signature_media_id = null;
                $user->signature_url      = null;
            }

            // Upload signature BARU ────────────────────────────────────
            if ($hasNewSignatureFile) {
                $sigMedia = $this->mediaService->upload($request->file('signature_file'));
                $sigMedia->update(['type' => 'signature']);
            } else {
                // Canvas base64 → upload langsung ke Cloudinary
                $cloudinary = new Cloudinary(config('cloudinary.cloud_url'));
                $result = $cloudinary->uploadApi()->upload($request->signature_data, [
                    'resource_type' => 'image',
                    'folder'        => 'signatures',
                    'quality'       => 'auto',
                    'fetch_format'  => 'auto',
                ]);
                $sigMedia = MediaFile::create([
                    'cloudinary_public_id' => $result['public_id'],
                    'url'                  => $result['secure_url'],
                    'type'                 => 'signature',
                ]);
            }

            $user->signature_media_id = $sigMedia->id;
            $user->signature_url      = $sigMedia->url;
        }

        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone_number = $request->phone_number;
        $user->religion     = $request->religion;
        $user->save();

        return redirect()->route('profile.parent.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    // ── Fix #1: password redirect ke show, bukan back() ──────────────
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', 'min:8'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.parent.show')
            ->with('success', 'Password berhasil diperbarui.');
    }
}