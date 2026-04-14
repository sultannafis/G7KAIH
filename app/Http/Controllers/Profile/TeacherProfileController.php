<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Services\Media\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherProfileController extends Controller
{
    public function __construct(protected MediaUploadService $mediaService) {}

    public function show()
    {
        $user = auth()->user()->load(['teacher.g7kaihClass', 'school']);
        return view('profile.teacher.show', compact('user'));
    }

    public function edit()
    {
        return redirect()->route('profile.teacher.show');
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

        return redirect()->route('profile.teacher.show')
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

        return redirect()->route('profile.teacher.show')
            ->with('success', 'Password berhasil diperbarui.');
    }
}