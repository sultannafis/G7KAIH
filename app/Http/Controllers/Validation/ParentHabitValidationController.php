<?php

namespace App\Http\Controllers\Validation;

use App\Http\Controllers\Controller;
use App\Models\HabitSubmission;
use App\Models\MediaFile;
use App\Models\Parents;
use App\Services\G7KAIH\HabitValidationService;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ParentHabitValidationController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected HabitValidationService $validationService,
    ) {}

    /**
     * Daftar submission yang menunggu validasi dari orang tua yang sedang login.
     *
     * URL: GET /parent/validations
     */
    public function index(Request $request)
    {
        $user   = Auth::user();
        $parent = $user->parent;

        abort_unless($parent, 403, 'Profil orang tua tidak ditemukan.');

        $studentId = $parent->student_id;

        $query = HabitSubmission::where('student_id', $studentId)
            ->with(['habit', 'habitItem', 'rule', 'validations', 'mediaFiles', 'selectedActivities']);

        // ── Filter status (default: all) ───────────────────────────────
        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // ── Filter habit item ──────────────────────────────────────────
        if ($request->filled('habit_item_id')) {
            $val = $request->get('habit_item_id');
            if (str_starts_with((string) $val, 'habit_')) {
                $query->where('habit_id', (int) str_replace('habit_', '', $val))
                      ->whereNull('habit_item_id');
            } else {
                $query->where('habit_item_id', (int) $val);
            }
        }

        // ── Filter tanggal ─────────────────────────────────────────────
        if ($request->filled('date_from')) {
            $query->whereDate('submission_date', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('submission_date', '<=', $request->get('date_to'));
        }

        // ── Sorting: pending_parent paling atas, lalu terbaru ──────────
        $query->orderByRaw("FIELD(status, 'pending_parent') DESC")
              ->orderBy('submitted_at', 'desc');

        // ── Per-page ───────────────────────────────────────────────────
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;

        $submissions = $query->paginate($perPage)->withQueryString();

        return view('parent.validations.index', compact('submissions', 'status'));
    }

    /**
     * Detail satu submission untuk diperiksa orang tua.
     *
     * URL: GET /parent/validations/{submission}
     */
    public function show(HabitSubmission $submission)
    {
        $user   = Auth::user();
        $parent = $user->parent;

        abort_unless($parent, 403);
        $this->abortUnlessIsMyChild($parent, $submission);

        $submission->load(['habit', 'habitItem', 'rule', 'validations.validator', 'mediaFiles', 'student.user']);

        $roleLabel      = 'Orang Tua';
        $backRoute      = route('parent.validations.index');
        $canValidate    = $submission->status === 'pending_parent';
        $expectedStatus = 'pending_parent';
        $approveRoute   = route('parent.validations.approve', $submission);
        $rejectRoute    = route('parent.validations.reject', $submission);

        // from = 'validation' karena berasal dari halaman validasi
        $from = 'validation';

        return view('shared.submission_show', compact(
            'submission',
            'roleLabel',
            'backRoute',
            'canValidate',
            'expectedStatus',
            'approveRoute',
            'rejectRoute',
            'from',
        ));
    }

    /**
     * Orang tua menyetujui submission anak.
     * Jika request membawa signature_data (base64 canvas), simpan ke profil terlebih dahulu.
     *
     * URL: POST /parent/validations/{submission}/approve
     */
    public function approve(Request $request, HabitSubmission $submission)
    {
        $user   = Auth::user();
        $parent = $user->parent;

        abort_unless($parent, 403);
        $this->abortUnlessIsMyChild($parent, $submission);
        $this->abortUnlessStatus($submission, 'pending_parent');

        // ── Simpan tanda tangan jika dikirim dari popup ──────────────
        if ($request->filled('signature_data')
            && str_starts_with($request->signature_data, 'data:image')
        ) {
            $this->saveSignatureFromCanvas($user, $request->signature_data);
        }

        // ── Tentukan redirect berdasarkan `from` ─────────────────────
        $from = $request->input('from', 'validation');

        try {
            $this->validationService->parentApprove($submission, $user);

            $redirectRoute = $from === 'dashboard'
                ? route('dashboard.parent')
                : route('parent.validations.index');

            return redirect()
                ->to($redirectRoute)
                ->with('success', 'Kamu telah menyetujui kegiatan ' . $submission->habit->name . '!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memvalidasi: ' . $e->getMessage());
        }
    }

    /**
     * Orang tua menolak submission anak (wajib memberi alasan).
     *
     * URL: POST /parent/validations/{submission}/reject
     */
    public function reject(Request $request, HabitSubmission $submission)
    {
        $user   = Auth::user();
        $parent = $user->parent;

        abort_unless($parent, 403);
        $this->abortUnlessIsMyChild($parent, $submission);
        $this->abortUnlessStatus($submission, 'pending_parent');

        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi.',
            'reason.min'      => 'Alasan penolakan minimal 10 karakter.',
        ]);

        // ── Tentukan redirect berdasarkan `from` ─────────────────────
        $from = $request->input('from', 'validation');

        try {
            $this->validationService->parentReject($submission, $user, $validated['reason']);

            $redirectRoute = $from === 'dashboard'
                ? route('dashboard.parent')
                : route('parent.validations.index');

            return redirect()
                ->to($redirectRoute)
                ->with('success', 'Submission telah ditolak. Siswa akan diberitahu.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak submission: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // Helper / Guard Methods
    // -------------------------------------------------------------------------

    protected function abortUnlessIsMyChild(Parents $parent, HabitSubmission $submission): void
    {
        abort_unless(
            $submission->student_id === $parent->student_id,
            403,
            'Kamu tidak memiliki akses ke submission ini.'
        );
    }

    protected function abortUnlessStatus(HabitSubmission $submission, string $expectedStatus): void
    {
        abort_unless(
            $submission->status === $expectedStatus,
            422,
            'Submission ini sudah tidak bisa divalidasi (status: ' . $submission->status . ').'
        );
    }

    /**
     * Simpan tanda tangan dari canvas base64 ke Cloudinary dan update profil user.
     */
    protected function saveSignatureFromCanvas(\App\Models\User $user, string $base64Data): void
    {
        try {
            // Hapus signature lama jika ada
            if ($user->signature_media_id) {
                $oldMedia = MediaFile::find($user->signature_media_id);
                if ($oldMedia) {
                    try {
                        $cloudinary = new Cloudinary(config('cloudinary.cloud_url'));
                        $cloudinary->uploadApi()->destroy(
                            $oldMedia->cloudinary_public_id,
                            ['resource_type' => 'image']
                        );
                    } catch (\Throwable) {
                        // Abaikan jika gagal hapus dari Cloudinary
                    }
                    $oldMedia->delete();
                }
            }

            // Upload signature baru
            $cloudinary = new Cloudinary(config('cloudinary.cloud_url'));
            $result = $cloudinary->uploadApi()->upload($base64Data, [
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

            $user->update([
                'signature_media_id' => $sigMedia->id,
                'signature_url'      => $sigMedia->url,
            ]);
        } catch (\Throwable $e) {
            logger()->warning('Gagal simpan tanda tangan dari canvas: ' . $e->getMessage());
        }
    }
}