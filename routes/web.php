<?php

use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\MasterAdminDashboardController;
use App\Http\Controllers\Dashboard\ParentDashboardController;
use App\Http\Controllers\Dashboard\StudentDashboardController;
use App\Http\Controllers\Dashboard\TeacherDashboardController;
use App\Http\Controllers\MasterAdmin\SchoolApprovalController;
use App\Http\Controllers\MasterAdmin\SchoolController;
use App\Http\Controllers\SchoolAdmin\UserManagement\TeacherController;
use App\Http\Controllers\SchoolAdmin\UserManagement\StudentController;
use App\Http\Controllers\SchoolAdmin\UserManagement\ParentController;
use App\Http\Controllers\SchoolAdmin\Habit\HabitController;
use App\Http\Controllers\SchoolAdmin\Habit\HabitItemController;
use App\Http\Controllers\SchoolAdmin\Habit\HabitRuleController;
use App\Http\Controllers\MasterAdmin\NotificationTemplateController;
use App\Http\Controllers\SchoolAdmin\QrCardController;
use App\Http\Controllers\SchoolAdmin\G7KaihClassController;
use App\Http\Controllers\SchoolAdmin\SchoolSettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Profile\AdminProfileController;
use App\Http\Controllers\Profile\ParentProfileController;
use App\Http\Controllers\Profile\StudentProfileController;
use App\Http\Controllers\Profile\TeacherProfileController;
use App\Http\Controllers\Profile\MasterAdminProfileController;
use App\Http\Controllers\Student\HabitSubmissionController;
use App\Http\Controllers\Validation\ParentHabitValidationController;
use App\Http\Controllers\Validation\TeacherHabitValidationController;
use App\Http\Controllers\Teacher\TeacherPrayerAttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    // ---------------------------------------------------------------
    // Dashboard
    // ---------------------------------------------------------------
    Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])
        ->name('dashboard.admin')->middleware('role:admin');

    Route::get('/dashboard/masteradmin', [MasterAdminDashboardController::class, 'index'])
        ->name('dashboard.masteradmin')->middleware('role:masteradmin');

    Route::get('/dashboard/parent', [ParentDashboardController::class, 'index'])
        ->name('dashboard.parent')->middleware('role:orangtua');

    Route::get('/dashboard/student', [StudentDashboardController::class, 'index'])
        ->name('dashboard.student')->middleware('role:siswa');

    Route::get('/dashboard/teacher', [TeacherDashboardController::class, 'index'])
        ->name('dashboard.teacher')->middleware('role:guru');

    Route::get('/dashboard', function () {
        return match(auth()->user()->role) {
            'admin'       => redirect()->route('dashboard.admin'),
            'masteradmin' => redirect()->route('dashboard.masteradmin'),
            'orangtua'    => redirect()->route('dashboard.parent'),
            'siswa'       => redirect()->route('dashboard.student'),
            'guru'        => redirect()->route('dashboard.teacher'),
            default       => redirect('/'),
        };
    })->name('dashboard');

    // ---------------------------------------------------------------
    // Profile
    // ---------------------------------------------------------------
    Route::middleware('auth')->group(function () {
        Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // ---------------------------------------------------------------
    // Master Admin Routes
    // ---------------------------------------------------------------
    Route::middleware(['auth', 'role:masteradmin'])->prefix('masteradmin')->name('masteradmin.')->group(function () {

        Route::prefix('schools/approval')->name('schools.approval.')->group(function () {
            Route::get('/',                     [SchoolApprovalController::class, 'index'])->name('index');
            Route::get('/{school}',             [SchoolApprovalController::class, 'show'])->name('show');
            Route::post('/{school}/approve',    [SchoolApprovalController::class, 'approve'])->name('approve');
            Route::post('/{school}/reject',     [SchoolApprovalController::class, 'reject'])->name('reject');
        });

        Route::prefix('schools')->name('schools.')->group(function () {
            Route::get('/list',             [SchoolController::class, 'index'])->name('list');
            Route::get('/create',           [SchoolController::class, 'create'])->name('create');
            Route::post('/',                [SchoolController::class, 'store'])->name('store');
            Route::get('/{school}/detail',  [SchoolController::class, 'show'])->name('show');
            Route::get('/{school}/edit',    [SchoolController::class, 'edit'])->name('edit');
            Route::put('/{school}',         [SchoolController::class, 'update'])->name('update');
            Route::delete('/{school}',      [SchoolController::class, 'destroy'])->name('destroy');
            Route::put('/{school}/toggle-status', [SchoolController::class, 'toggleStatus'])->name('toggle-status');
        });

        Route::prefix('user-management')->name('user-management.')->group(function () {
            Route::get('teachers',          [\App\Http\Controllers\MasterAdmin\UserManagement\TeacherController::class, 'index'])->name('teachers.index');
            Route::get('teachers/{teacher}',[\App\Http\Controllers\MasterAdmin\UserManagement\TeacherController::class, 'show'])->name('teachers.show');
            Route::get('students',          [\App\Http\Controllers\MasterAdmin\UserManagement\StudentController::class, 'index'])->name('students.index');
            Route::get('students/{student}',[\App\Http\Controllers\MasterAdmin\UserManagement\StudentController::class, 'show'])->name('students.show');
            Route::get('parents',           [\App\Http\Controllers\MasterAdmin\UserManagement\ParentController::class, 'index'])->name('parents.index');
            Route::get('parents/{parent}',  [\App\Http\Controllers\MasterAdmin\UserManagement\ParentController::class, 'show'])->name('parents.show');
        });

        Route::resource('notification-templates', NotificationTemplateController::class);
        Route::patch('/notification-templates/{notificationTemplate}/toggle', [NotificationTemplateController::class, 'toggle'])
            ->name('notification-templates.toggle');

        // Settings
        Route::get('settings',          [\App\Http\Controllers\MasterAdmin\SettingsController::class, 'index'])->name('settings.index');
        Route::get('settings/ai',       [\App\Http\Controllers\MasterAdmin\SettingsController::class, 'aiPage'])->name('settings.ai');
        Route::post('settings/ai',      [\App\Http\Controllers\MasterAdmin\SettingsController::class, 'aiSave'])->name('settings.ai.save');
        Route::get('settings/notifications', [\App\Http\Controllers\MasterAdmin\SettingsController::class, 'notificationPage'])->name('settings.notifications');
        Route::post('settings/notifications', [\App\Http\Controllers\MasterAdmin\SettingsController::class, 'notificationSave'])->name('settings.notifications.save');
    });

    // Habit rules: cek apakah sekolah aktif validasi AI (untuk tampilkan ceklis di form)
    Route::get('/ai/school-status', function() {
        $schoolId = auth()->user()->school_id;
        $setting  = \App\Models\AiSetting::instance();
        return response()->json(['can_use_ai' => $setting->schoolCanUseAiValidation($schoolId ?? 0)]);
    })->name('ai.school-status');

    Route::middleware(['auth', 'role:admin'])->prefix('school-admin')->name('school-admin.')->group(function () {
        Route::get('notification-templates',[\App\Http\Controllers\MasterAdmin\NotificationTemplateController::class, 'index'])->name('notification-templates.index');
        Route::get('notification-templates/{notificationTemplate}',[\App\Http\Controllers\MasterAdmin\NotificationTemplateController::class, 'show'])->name('notification-templates.show');
        Route::get('notification-templates/{notificationTemplate}/edit',[\App\Http\Controllers\MasterAdmin\NotificationTemplateController::class, 'edit'])->name('notification-templates.edit');
        Route::put('notification-templates/{notificationTemplate}',[\App\Http\Controllers\MasterAdmin\NotificationTemplateController::class, 'update'])->name('notification-templates.update');
        Route::patch('notification-templates/{notificationTemplate}/toggle',[\App\Http\Controllers\MasterAdmin\NotificationTemplateController::class, 'toggle'])->name('notification-templates.toggle');
        // Hapus fork (kembali ke global) — hanya untuk template milik sekolah sendiri
        Route::delete('notification-templates/{notificationTemplate}/fork',[\App\Http\Controllers\MasterAdmin\NotificationTemplateController::class, 'destroyFork'])->name('notification-templates.destroy-fork');
    });

    // ---------------------------------------------------------------
    // School Admin Routes — user management & classes
    // ---------------------------------------------------------------
    Route::middleware(['auth', 'role:admin'])->prefix('school-admin')->name('school-admin.')->group(function () {

        Route::prefix('user-management')->name('user-management.')->group(function () {
            Route::get('students/import',           [StudentController::class, 'import'])->name('students.import');
            Route::post('students/import',          [StudentController::class, 'processImport'])->name('students.process-import');
            Route::get('students/download-template',[StudentController::class, 'downloadTemplate'])->name('students.download-template');
            Route::post('students/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggle-status');
            Route::resource('students', StudentController::class);

            Route::get('teachers/import',           [TeacherController::class, 'import'])->name('teachers.import');
            Route::post('teachers/import',          [TeacherController::class, 'processImport'])->name('teachers.process-import');
            Route::get('teachers/download-template',[TeacherController::class, 'downloadTemplate'])->name('teachers.download-template');
            Route::post('teachers/{teacher}/toggle-status', [TeacherController::class, 'toggleStatus'])->name('teachers.toggle-status');
            Route::resource('teachers', TeacherController::class);

            Route::get('parents/import',            [ParentController::class, 'import'])->name('parents.import');
            Route::post('parents/import',           [ParentController::class, 'processImport'])->name('parents.process-import');
            Route::get('parents/download-template', [ParentController::class, 'downloadTemplate'])->name('parents.download-template');
            Route::post('parents/{parent}/toggle-status', [ParentController::class, 'toggleStatus'])->name('parents.toggle-status');
            Route::resource('parents', ParentController::class);
        });

        Route::post('classes/{class}/toggle-status', [G7KaihClassController::class, 'toggleStatus'])->name('classes.toggle-status');
        Route::resource('classes', G7KaihClassController::class);

        Route::put('school-settings', [SchoolSettingController::class, 'update'])->name('settings.update');

        // QR Card Management
        Route::get('qr-cards',                [QrCardController::class, 'index'])->name('qr-cards.index');
        Route::post('qr-cards/settings',      [QrCardController::class, 'updateSettings'])->name('qr-cards.settings.update');
        Route::post('qr-cards/settings/reset',     [QrCardController::class, 'resetSettings'])->name('qr-cards.settings.reset'); 
        Route::post('qr-cards/logo-position', [QrCardController::class, 'saveLogoPosition'])->name('qr-cards.logo-position');
    });

    // ---------------------------------------------------------------
    // School Admin Routes — Habits (admin + guru + masteradmin)
    // ---------------------------------------------------------------
    Route::middleware(['auth', 'role:admin,guru,masteradmin'])->prefix('school-admin')->name('school-admin.')->group(function () {

        /*
        |--------------------------------------------------------------
        | Habit Routes
        |--------------------------------------------------------------
        */
        Route::resource('habits', HabitController::class);
        Route::post('habits/{habit}/toggle-status', [HabitController::class, 'toggleStatus'])
            ->name('habits.toggle-status');

        // ✅ Bulk generate prayer rules untuk seluruh item dalam satu habit
        Route::post('habits/{habit}/bulk-generate-prayer-rules', [HabitRuleController::class, 'bulkGeneratePrayerRules'])
            ->name('habits.bulk-generate-prayer-rules');

        /*
        |--------------------------------------------------------------
        | Habit Item Routes
        |--------------------------------------------------------------
        */
        Route::resource('habit-items', HabitItemController::class)->parameters([
            'habit-items' => 'item',
        ]);

        Route::post('habit-items/{item}/toggle-status', [HabitItemController::class, 'toggleStatus'])
            ->name('habit-items.toggle-status');

        // ✅ Sync prayer times → sekarang redirect (bukan JSON)
        Route::post('habit-items/{item}/sync-prayer-times', [HabitItemController::class, 'syncPrayerTimes'])
            ->name('habit-items.sync-prayer-times');

        // ✅ Generate prayer rules untuk satu item → sekarang redirect (bukan JSON)
        Route::post('habit-items/{habitItem}/generate-prayer-rules', [HabitRuleController::class, 'generatePrayerRules'])
            ->name('habit-items.generate-prayer-rules');

        /*
        |--------------------------------------------------------------
        | Habit Rule Routes
        |--------------------------------------------------------------
        | PENTING: letakkan named routes sebelum Route::resource agar
        | 'preview-prayer-times' tidak dianggap sebagai {rule} parameter.
        |--------------------------------------------------------------
        */

        // ✅ Preview waktu sholat → render view (bukan JSON)
        Route::get('habit-rules/preview-prayer-times', [HabitRuleController::class, 'previewPrayerTimes'])
            ->name('habit-rules.preview-prayer-times');

        // Update priorities (tetap JSON — dipakai drag-and-drop)
        Route::post('habit-rules/update-priorities', [HabitRuleController::class, 'updatePriorities'])
            ->name('habit-rules.update-priorities');

        Route::resource('habit-rules', HabitRuleController::class)->parameters([
            'habit-rules' => 'rule',
        ]);
    });

    // ---------------------------------------------------------------
    // Student Routes
    // ---------------------------------------------------------------
    Route::middleware(['auth', 'role:siswa'])->prefix('student')->name('student.')->group(function () {

    Route::get('habits/today',   [HabitSubmissionController::class, 'today'])->name('habits.today');
    Route::get('habits/history', [HabitSubmissionController::class, 'history'])->name('habits.history');

    Route::get('habits/submission/{submission}', 
        [HabitSubmissionController::class, 'show'])->name('habits.submission.show');

    Route::get('habits/{habit}/submit',  
        [HabitSubmissionController::class, 'create'])->name('habits.submit.create');

    Route::post('habits/{habit}/submit', 
        [HabitSubmissionController::class, 'store'])->name('habits.submit.store');

    Route::post('habits/{habit}/quick-submit',
        [HabitSubmissionController::class, 'quickSubmit'])->name('habits.quick-submit');
});

    // ---------------------------------------------------------------
    // Parent Validation Routes
    // ---------------------------------------------------------------
    Route::middleware(['auth', 'role:orangtua'])->prefix('parent')->name('parent.')->group(function () {
        Route::get('validations',                       [ParentHabitValidationController::class, 'index'])->name('validations.index');
        Route::get('validations/{submission}',          [ParentHabitValidationController::class, 'show'])->name('validations.show');
        Route::post('validations/{submission}/approve', [ParentHabitValidationController::class, 'approve'])->name('validations.approve');
        Route::post('validations/{submission}/reject',  [ParentHabitValidationController::class, 'reject'])->name('validations.reject');
    });

    // ---------------------------------------------------------------
    // Teacher Validation Routes
    // ---------------------------------------------------------------
Route::middleware(['auth', 'role:guru'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('validations',                       [TeacherHabitValidationController::class, 'index'])->name('validations.index');
    Route::get('validations/{submission}',          [TeacherHabitValidationController::class, 'show'])->name('validations.show');
    Route::post('validations/{submission}/approve', [TeacherHabitValidationController::class, 'approve'])->name('validations.approve');
    Route::post('validations/{submission}/reject',  [TeacherHabitValidationController::class, 'reject'])->name('validations.reject');
    Route::get('my-class',                           [\App\Http\Controllers\Teacher\MyClassController::class, 'index'])->name('my-class.index');
    Route::get('my-class/student/{student}/report',  [\App\Http\Controllers\Teacher\MyClassController::class, 'studentReport'])->name('my-class.student-report');
    Route::get('my-class/student/{student}/report/download', [\App\Http\Controllers\Teacher\MyClassController::class, 'studentReportDownload'])->name('my-class.student-report-download');

    // ── Prayer Attendance ──────────────────────────────────────────
    Route::get('prayer-attendance',                  [TeacherPrayerAttendanceController::class, 'index'])->name('prayer-attendance.index');
Route::post('prayer-attendance',                 [TeacherPrayerAttendanceController::class, 'store'])->name('prayer-attendance.store');
Route::delete('prayer-attendance/{submission}',  [TeacherPrayerAttendanceController::class, 'destroy'])->name('prayer-attendance.destroy');
});


// ── Admin Profile ────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('profile/admin')->name('profile.admin.')->group(function () {
    Route::get('/',         [AdminProfileController::class, 'show'])->name('show');
    Route::get('/edit',     [AdminProfileController::class, 'edit'])->name('edit');
    Route::patch('/',       [AdminProfileController::class, 'update'])->name('update');
    Route::patch('/password', [AdminProfileController::class, 'updatePassword'])->name('password');
});

// ── Master Admin Profile ─────────────────────────────────────────
Route::middleware(['auth', 'role:masteradmin'])->prefix('profile/masteradmin')->name('profile.masteradmin.')->group(function () {
    Route::get('/',         [MasterAdminProfileController::class, 'show'])->name('show');
    Route::get('/edit',     [MasterAdminProfileController::class, 'edit'])->name('edit');
    Route::patch('/',       [MasterAdminProfileController::class, 'update'])->name('update');
    Route::patch('/password', [MasterAdminProfileController::class, 'updatePassword'])->name('password');
});

// ── Student Profile ──────────────────────────────────────────────
Route::middleware(['auth', 'role:siswa'])->prefix('profile/student')->name('profile.student.')->group(function () {
    Route::get('/',         [StudentProfileController::class, 'show'])->name('show');
    Route::get('/edit',     [StudentProfileController::class, 'edit'])->name('edit');
    Route::patch('/',       [StudentProfileController::class, 'update'])->name('update');
    Route::patch('/password', [StudentProfileController::class, 'updatePassword'])->name('password');
});

// ── Teacher Profile ──────────────────────────────────────────────
Route::middleware(['auth', 'role:guru'])->prefix('profile/teacher')->name('profile.teacher.')->group(function () {
    Route::get('/',         [TeacherProfileController::class, 'show'])->name('show');
    Route::get('/edit',     [TeacherProfileController::class, 'edit'])->name('edit');
    Route::patch('/',       [TeacherProfileController::class, 'update'])->name('update');
    Route::patch('/password', [TeacherProfileController::class, 'updatePassword'])->name('password');
});

// ── Parent Profile ───────────────────────────────────────────────
Route::middleware(['auth', 'role:orangtua'])->prefix('profile/parent')->name('profile.parent.')->group(function () {
    Route::get('/',         [ParentProfileController::class, 'show'])->name('show');
    Route::get('/edit',     [ParentProfileController::class, 'edit'])->name('edit');
    Route::patch('/',       [ParentProfileController::class, 'update'])->name('update');
    Route::patch('/password', [ParentProfileController::class, 'updatePassword'])->name('password');
});

// ── Redirect /profile ke profil masing-masing role ──────────────
// Ganti route profile.edit bawaan Breeze:
Route::middleware('auth')->get('/profile', function () {
    return match (auth()->user()->role) {
        'masteradmin'=> redirect()->route('profile.masteradmin.show'),
        'admin'      => redirect()->route('profile.admin.show'),
        'siswa'      => redirect()->route('profile.student.show'),
        'guru'       => redirect()->route('profile.teacher.show'),
        'orangtua'   => redirect()->route('profile.parent.show'),
        default      => redirect()->route('dashboard'),
    };
})->name('profile.edit');

});

// AI Chat endpoint — di luar auth agar bisa untuk public
Route::post('/ai/chat', [\App\Http\Controllers\AiChatController::class, 'chat'])->name('ai.chat');