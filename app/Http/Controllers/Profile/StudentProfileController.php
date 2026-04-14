<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Services\Media\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentProfileController extends Controller
{
    public function __construct(protected MediaUploadService $mediaService) {}

    public function show()
    {
        $user = auth()->user()->load(['student.g7kaihClass', 'school']);
        // Ensure QR settings defaults are applied if null
        if ($user->school) {
            $user->school->qr_show_logo1 = $user->school->qr_show_logo1 ?? true;
            $user->school->qr_show_logo2 = $user->school->qr_show_logo2 ?? true;
            $user->school->qr_logo1_x    = $user->school->qr_logo1_x    ?? 25;
            $user->school->qr_logo1_y    = $user->school->qr_logo1_y    ?? 40;
            $user->school->qr_logo2_x    = $user->school->qr_logo2_x    ?? 75;
            $user->school->qr_logo2_y    = $user->school->qr_logo2_y    ?? 40;
        }
        return view('profile.student.show', compact('user'));
    }

    public function edit()
    {
        return redirect()->route('profile.student.show');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'religion'     => ['nullable', 'string', 'max:50'],
            'avatar'       => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $media = $this->mediaService->upload($request->file('avatar'));
            $validated['avatar_url'] = $media->url;
        }

        $user->update(collect($validated)->except('avatar')->toArray());

        return redirect()->route('profile.student.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    // Fix #1: redirect ke show, bukan back()
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', 'min:8'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.student.show')
            ->with('success', 'Password berhasil diperbarui.');
    }
}