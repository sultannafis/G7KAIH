<?php

namespace App\Http\Controllers\Validation;

use App\Http\Controllers\Controller;
use App\Models\G7KaihClass;
use App\Models\HabitSubmission;
use App\Models\Teacher;
use App\Services\G7KAIH\HabitValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TeacherHabitValidationController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected HabitValidationService $validationService,
    ) {}

    public function index(Request $request)
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        abort_unless($teacher, 403, 'Profil guru tidak ditemukan.');

        $classIds = \DB::table('g7_kaih_classes')
            ->where('teacher_id', $user->id)
            ->where('is_active', true)
            ->pluck('id');

        if ($request->get('debug') == 1) {
            dd([
                'user->id'               => $user->id,
                'teacher->id'            => $teacher->id,
                'classIds'               => $classIds,
                'all_g7_kaih_classes'    => \DB::table('g7_kaih_classes')->get(['id','name','teacher_id','is_active']),
                'all_habit_submissions'  => \DB::table('habit_submissions')
                    ->get(['id','student_id','g7_kaih_class_id','status']),
            ]);
        }

        $query = HabitSubmission::whereIn('g7_kaih_class_id', $classIds)
            ->with(['habit', 'habitItem', 'rule', 'student.user', 'validations', 'mediaFiles', 'selectedActivities']);

        $status = $request->get('status', 'all');
        if ($status === 'pending_teacher') {
            $query->where('status', 'pending_teacher');
        } if ($status === 'all') {
            $query->where('status', '!=', 'pending_parent');
        }

        if ($request->filled('class_id')) {
            $query->where('g7_kaih_class_id', $request->class_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('submission_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('submission_date', '<=', $request->date_to);
        }

        $query->orderBy('submitted_at', 'desc');

        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;

        $submissions = $query->paginate($perPage)->withQueryString();

        $myClasses = \DB::table('g7_kaih_classes')
            ->where('teacher_id', $user->id)
            ->get();

        return view('teacher.validations.index', compact('submissions', 'status', 'myClasses'));
    }

    public function show(HabitSubmission $submission)
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        abort_unless($teacher, 403);

        $school = $user->school;
        abort_unless(
            $submission->student->user->school_id === $school->id,
            403,
            'Kamu tidak memiliki akses ke submission ini.'
        );

        $submission->load(['habit', 'habitItem', 'rule', 'validations.validator', 'mediaFiles', 'student.user']);

        $validatableStatuses = ['pending_teacher', 'ai_valid'];
        $canValidate         = in_array($submission->status, $validatableStatuses);
        $expectedStatus      = $submission->status;

        $roleLabel    = 'Guru';
        $backRoute    = route('teacher.validations.index');
        $approveRoute = route('teacher.validations.approve', $submission);
        $rejectRoute  = route('teacher.validations.reject', $submission);

        return view('shared.submission_show', compact(
            'submission',
            'roleLabel',
            'backRoute',
            'canValidate',
            'expectedStatus',
            'approveRoute',
            'rejectRoute',
        ));
    }

    public function approve(Request $request, HabitSubmission $submission)
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        abort_unless($teacher, 403);
        $this->abortUnlessMyClass($user, $submission);
        $this->abortUnlessValidatableByTeacher($submission);

        try {
            $overridePoint = $request->filled('override_point') ? (int)$request->input('override_point') : null;
            $this->validationService->teacherApprove($submission, $user, $overridePoint);

            // ── Smart redirect: balik ke asal (dashboard atau halaman validasi) ──
            return $this->redirectAfterValidation($request, 'Submission berhasil disetujui! Poin siswa telah dikunci.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, HabitSubmission $submission)
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        abort_unless($teacher, 403);
        $this->abortUnlessMyClass($user, $submission);
        $this->abortUnlessValidatableByTeacher($submission);

        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi sebagai bukti audit.',
            'reason.min'      => 'Alasan harus minimal 10 karakter.',
        ]);

        try {
            $this->validationService->teacherReject($submission, $user, $validated['reason']);

            // ── Smart redirect: balik ke asal (dashboard atau halaman validasi) ──
            return $this->redirectAfterValidation($request, 'Submission ditolak dan audit trail berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak submission: ' . $e->getMessage());
        }
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Smart redirect setelah validasi:
     * - Jika dari dashboard → redirect ke dashboard
     * - Jika dari halaman validasi → redirect ke halaman validasi
     *
     * Deteksi via hidden input 'from' di form (nilai: 'dashboard' | 'validation')
     */
    protected function redirectAfterValidation(Request $request, string $successMessage): \Illuminate\Http\RedirectResponse
    {
        $from = $request->input('from', 'validation');

        if ($from === 'dashboard') {
            return redirect()
                ->route('dashboard.teacher')
                ->with('success', $successMessage);
        }

        return redirect()
            ->route('teacher.validations.index')
            ->with('success', $successMessage);
    }

    /**
     * FIX: Gunakan $user->id karena teacher_id di g7_kaih_classes → constrained('users')
     */
    protected function abortUnlessMyClass(\App\Models\User $user, HabitSubmission $submission): void
    {
        $isMyClass = \DB::table('g7_kaih_classes')
            ->where('teacher_id', $user->id)
            ->where('id', $submission->g7_kaih_class_id)
            ->exists();

        abort_unless($isMyClass, 403, 'Kamu tidak memiliki akses ke submission ini.');
    }

    protected function abortUnlessValidatableByTeacher(HabitSubmission $submission): void
    {
        $validStatuses = ['pending_teacher', 'ai_valid'];

        abort_unless(
            in_array($submission->status, $validStatuses),
            422,
            'Submission ini tidak bisa divalidasi oleh guru saat ini (status: ' . $submission->status . ').'
        );
    }
}