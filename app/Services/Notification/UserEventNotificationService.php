<?php

namespace App\Services\Notification;

use App\Models\School;
use App\Models\User;

/**
 * Service terpusat untuk notifikasi event user & sekolah.
 *
 * Cara pakai di controller:
 *   app(UserEventNotificationService::class)->userActivated($user);
 *   app(UserEventNotificationService::class)->userDeactivated($user);
 *   app(UserEventNotificationService::class)->schoolSuspended($school);
 *   app(UserEventNotificationService::class)->schoolActivated($school);
 */
class UserEventNotificationService
{
    public function __construct(
        protected NotificationService $notificationService,
    ) {
    }

    // ──────────────────────────────────────────────────────────────
    // User Events
    // ──────────────────────────────────────────────────────────────

    private function buildUserVariables(User $user, string $dateKey): array
    {
        $admin = $user->school ? $this->getSchoolAdmin($user->school) : null;
        $adminName = $admin ? $admin->name : $user->name;

        $vars = [
            'user_name' => $user->name,
            'admin_name' => $adminName,
            $dateKey => now()->translatedFormat('d F Y'),
            'school_name' => $user->school?->name ?? '',
        ];

        if ($user->role === 'siswa') {
            $vars['student_name'] = $user->name;
            if ($user->student) {
                $vars['student_nis'] = $user->student->nis;
                $vars['student_nisn'] = $user->student->nisn;
            }
        } elseif ($user->role === 'orangtua') {
            $vars['parent_name'] = $user->name;
            if ($user->parent) {
                $vars['parent_relation'] = $user->parent->family_relationship;
                if ($user->parent->student && $user->parent->student->user) {
                    $vars['student_name'] = $user->parent->student->user->name;
                    $vars['student_nis'] = $user->parent->student->nis;
                    $vars['student_nisn'] = $user->parent->student->nisn;
                }
            }
        } elseif ($user->role === 'guru') {
            $vars['teacher_name'] = $user->name;
        }

        return $vars;
    }

    /**
     * Kirim notifikasi saat akun pengguna DIAKTIFKAN.
     * Event: UserActivated → ke user itu sendiri.
     */
    public function userActivated(User $user): void
    {
        $this->notificationService->send(
            'UserActivated',
            $user,
            $this->buildUserVariables($user, 'activation_date'),
            $user->school_id
        );
    }

    /**
     * Kirim notifikasi saat akun pengguna DINONAKTIFKAN.
     * Event: UserDeactivated → ke user itu sendiri.
     */
    public function userDeactivated(User $user): void
    {
        $this->notificationService->send(
            'UserDeactivated',
            $user,
            $this->buildUserVariables($user, 'deactivation_date'),
            $user->school_id
        );
    }

    /**
     * Kirim notifikasi saat akun meminta reset password.
     * Event: PasswordReset → ke user itu sendiri.
     */
    public function passwordResetRequested(User $user, string $token): void
    {
        $vars = $this->buildUserVariables($user, 'request_date');
        $vars['reset_url'] = route('password.reset', ['token' => $token, 'email' => $user->email]);

        $this->notificationService->send(
            'PasswordReset',
            $user,
            $vars,
            $user->school_id
        );
    }

    // ──────────────────────────────────────────────────────────────
    // School Events
    // ──────────────────────────────────────────────────────────────

    /**
     * Kirim notifikasi saat sekolah DINONAKTIFKAN.
     * Event: SchoolSuspended → ke admin sekolah.
     */
    public function schoolSuspended(School $school): void
    {
        $admin = $this->getSchoolAdmin($school);
        if (!$admin)
            return;

        $this->notificationService->send(
            'SchoolSuspended',
            $admin,
            [
                'school_name' => $school->name,
                'admin_name' => $admin->name,
                'suspension_date' => now()->translatedFormat('d F Y'),
            ],
            $school->id
        );
    }

    /**
     * Kirim notifikasi saat sekolah DIAKTIFKAN KEMBALI.
     * Event: SchoolActivated → ke admin sekolah.
     */
    public function schoolActivated(School $school): void
    {
        $admin = $this->getSchoolAdmin($school);
        if (!$admin)
            return;

        $this->notificationService->send(
            'SchoolActivated',
            $admin,
            [
                'school_name' => $school->name,
                'admin_name' => $admin->name,
                'activation_date' => now()->translatedFormat('d F Y'),
            ],
            $school->id
        );
    }

    // ──────────────────────────────────────────────────────────────
    // Internal Helper
    // ──────────────────────────────────────────────────────────────

    private function getSchoolAdmin(School $school): ?User
    {
        return User::where('school_id', $school->id)
            ->where('role', 'admin')
            ->first();
    }
}
