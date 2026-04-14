<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QrCardController extends Controller
{
    public function index(Request $request)
    {
        $school = Auth::user()->school;

        if (!$school) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di sekolah manapun.');
        }

        $search     = $request->input('search');
        $gradeLevel = $request->input('grade_level');
        $classId    = $request->input('class_id');
        $perPage    = in_array($request->input('per_page'), [10, 25, 50, 100])
                        ? (int) $request->input('per_page')
                        : 15;

        $query = User::where('school_id', $school->id)
            ->where('role', 'siswa')
            ->with(['student.g7kaihClass'])
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('student', fn($q) =>
                      $q->where('nis',  'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%")
                  );
            });
        }

        if ($gradeLevel) {
            $query->whereHas('student', fn($q) => $q->where('grade_level', $gradeLevel));
        }

        if ($classId) {
            $query->whereHas('student', fn($q) => $q->where('g7_kaih_class_id', $classId));
        }

        $students = $query->paginate($perPage)->withQueryString();

        $classes = \App\Models\G7KaihClass::where('school_id', $school->id)
            ->orderBy('name')->get();

        return view('school-admin.qr-cards.index', compact(
            'school', 'students', 'classes', 'search', 'gradeLevel', 'classId'
        ));
    }

    public function updateSettings(Request $request)
    {
        $school = Auth::user()->school;

        if (!$school) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di sekolah manapun.');
        }

        $request->validate([
            'qr_bg'         => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'qr_logo1'      => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'qr_logo2'      => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'qr_show_logo1' => 'nullable|boolean',
            'qr_show_logo2' => 'nullable|boolean',
            'qr_logo1_x'    => 'nullable|numeric|min:0|max:100',
            'qr_logo1_y'    => 'nullable|numeric|min:0|max:100',
            'qr_logo2_x'    => 'nullable|numeric|min:0|max:100',
            'qr_logo2_y'    => 'nullable|numeric|min:0|max:100',
            'qr_logo1_size' => 'nullable|numeric|min:5|max:40',
            'qr_logo2_size' => 'nullable|numeric|min:5|max:40',
        ]);

        $updates = [
            'qr_show_logo1' => $request->boolean('qr_show_logo1'),
            'qr_show_logo2' => $request->boolean('qr_show_logo2'),
            'qr_logo1_x'    => $request->input('qr_logo1_x',    $school->qr_logo1_x    ?? 25),
            'qr_logo1_y'    => $request->input('qr_logo1_y',    $school->qr_logo1_y    ?? 50),
            'qr_logo2_x'    => $request->input('qr_logo2_x',    $school->qr_logo2_x    ?? 65),
            'qr_logo2_y'    => $request->input('qr_logo2_y',    $school->qr_logo2_y    ?? 50),
            'qr_logo1_size' => $request->input('qr_logo1_size', $school->qr_logo1_size ?? 15),
            'qr_logo2_size' => $request->input('qr_logo2_size', $school->qr_logo2_size ?? 15),
        ];

        if ($request->hasFile('qr_bg')) {
            if ($school->qr_bg_path) Storage::disk('public')->delete($school->qr_bg_path);
            $updates['qr_bg_path'] = $request->file('qr_bg')->store('schools/qr-backgrounds', 'public');
        }

        if ($request->hasFile('qr_logo1')) {
            if ($school->qr_logo1_path) Storage::disk('public')->delete($school->qr_logo1_path);
            $updates['qr_logo1_path'] = $request->file('qr_logo1')->store('schools/qr-logos', 'public');
        }

        if ($request->hasFile('qr_logo2')) {
            if ($school->qr_logo2_path) Storage::disk('public')->delete($school->qr_logo2_path);
            $updates['qr_logo2_path'] = $request->file('qr_logo2')->store('schools/qr-logos', 'public');
        }

        $school->update($updates);

        return redirect()->route('school-admin.qr-cards.index')
            ->with('success', 'Pengaturan kartu QR berhasil disimpan.');
    }

    /**
     * FIX #2: Reset ke default
     * - Hapus file dari storage (bg, logo1, logo2)
     * - Set path ke null → accessor akan fallback ke gambar default di public/images/
     * - Reset posisi & ukuran ke default
     */
    public function resetSettings()
    {
        $school = Auth::user()->school;

        if (!$school) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di sekolah manapun.');
        }

        // Hapus file yang tersimpan di storage
        if ($school->qr_bg_path)    Storage::disk('public')->delete($school->qr_bg_path);
        if ($school->qr_logo1_path) Storage::disk('public')->delete($school->qr_logo1_path);
        if ($school->qr_logo2_path) Storage::disk('public')->delete($school->qr_logo2_path);

        // Reset DB ke default
        // Path di-null-kan → accessor School model akan fallback ke:
        //   qr_bg_url    → asset('images/background smk.png')
        //   qr_logo1_url → asset('images/logo smk.png')      (via logo_url)
        //   qr_logo2_url → asset('images/logo ybm pln.png')
        $school->update([
            'qr_bg_path'    => null,
            'qr_logo1_path' => null,
            'qr_logo2_path' => null,
            'qr_show_logo1' => true,
            'qr_show_logo2' => false,
            'qr_logo1_x'    => 25,
            'qr_logo1_y'    => 50,
            'qr_logo2_x'    => 65,
            'qr_logo2_y'    => 50,
            'qr_logo1_size' => 15,
            'qr_logo2_size' => 15,
        ]);

        return redirect()->route('school-admin.qr-cards.index')
            ->with('success', 'Pengaturan kartu QR berhasil direset ke setelan awal.');
    }

    /**
     * Simpan posisi & ukuran logo via AJAX
     */
    public function saveLogoPosition(Request $request)
    {
        $school = Auth::user()->school;

        $validated = $request->validate([
            'qr_logo1_x'    => 'nullable|numeric|min:0|max:100',
            'qr_logo1_y'    => 'nullable|numeric|min:0|max:100',
            'qr_logo2_x'    => 'nullable|numeric|min:0|max:100',
            'qr_logo2_y'    => 'nullable|numeric|min:0|max:100',
            'qr_logo1_size' => 'nullable|numeric|min:5|max:40',
            'qr_logo2_size' => 'nullable|numeric|min:5|max:40',
        ]);

        $school->update($validated);

        return response()->json(['success' => true]);
    }
}