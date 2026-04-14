<?php

namespace App\Services\Notification;

use App\Models\Notification;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use App\Models\User;
use App\Mail\SchoolNotificationMail;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function send(
        string $event,
        User $user,
        array $variables = [],
        ?int $schoolId = null
    ): void {
        $templates = NotificationTemplate::query()
            ->where('event', $event)
            ->where(function ($q) use ($user) {
                $q->whereJsonContains('target_role', $user->role)
                  ->orWhereJsonContains('target_role', 'all');
            })
            ->where('is_active', true)
            ->where(function ($q) use ($schoolId) {
                $q->whereNull('school_id')
                    ->orWhere('school_id', $schoolId);
            })
            ->orderBy('school_id', 'DESC') // template sekolah > global
            ->get()
            ->unique('channel');

        // Generate reset URL sekali saja agar token tidak saling menimpa
        if (!isset($variables['reset_url'])) {
            try {
                $token = \Illuminate\Support\Facades\Password::createToken($user);
                $variables['reset_url'] = rtrim(config('app.url'), '/')
                    . '/reset-password/' . $token
                    . '?email=' . rawurlencode($user->email);
            } catch (\Exception $e) {
                $variables['reset_url'] = rtrim(config('app.url'), '/') . '/forgot-password';
            }
        }

        if (!isset($variables['login_url'])) {
            $variables['login_url'] = rtrim(config('app.url'), '/') . '/login';
        }

        foreach ($templates as $template) {
            $message = $this->parseTemplate($template->message_template, $variables, $user);

            switch ($template->channel) {
                case 'dashboard':
                    $this->sendDashboard($user, $message, $event);
                    break;
                case 'email':
                    $this->sendEmail($user, $message, $event, $variables);
                    break;
                case 'whatsapp':
                    $this->sendWhatsapp($user, $message, $event);
                    break;
            }
        }
    }

    /**
     * Parse {{variable}} → nilai nyata
     *
     * Variabel yang didukung:
     *
     * [Umum]
     *   {{date}}               – Tanggal hari ini (format lokal)
     *   {{school_name}}        – Nama sekolah
     *   {{app_name}}           – Nama aplikasi
     *
     * [Pengguna]
     *   {{admin_name}}         – Nama admin sekolah
     *   {{student_name}}       – Nama siswa
     *   {{student_nisn}}       – NISN siswa
     *   {{student_nis}}        – NIS siswa
     *   {{teacher_name}}       – Nama guru / wali kelas
     *   {{parent_name}}        – Nama orang tua / wali
     *   {{parent_relation}}    – Hubungan keluarga (Ayah / Ibu / Wali)
     *
     * [Habit]
     *   {{habit_name}}         – Nama kategori habit
     *   {{habit_item_name}}    – Nama item habit yang disubmit
     *   {{habit_description}}  – Deskripsi habit
     *   {{submission_date}}    – Tanggal pengiriman habit
     *   {{submission_point}}   – Poin yang diperoleh dari submission ini
     *   {{total_point}}        – Total akumulasi poin siswa
     *   {{rejection_reason}}   – Alasan penolakan habit
     *   {{validation_status}}  – Label status validasi terkini
     *
     * [Kelas & Sholat]
     *   {{class_name}}         – Nama kelas G7 KAIH
     *   {{academic_year}}      – Tahun ajaran
     *   {{prayer_name}}        – Nama waktu sholat (Subuh / Dzuhur / dst)
     *   {{prayer_time}}        – Waktu sholat
     */
    protected function parseTemplate(string $template, array $vars, ?User $user = null): string
    {
        // Isi otomatis jika tidak disediakan pemanggil
        $vars['date']      ??= now()->translatedFormat('d F Y');
        $vars['app_name']  ??= config('app.name', 'G7KAIH');
        // reset_url dan login_url sudah di-inject oleh send() sebelum loop
        $vars['login_url'] ??= rtrim(config('app.url'), '/') . '/login';
        $vars['reset_url'] ??= rtrim(config('app.url'), '/') . '/forgot-password';

        foreach ($vars as $key => $value) {
            $template = preg_replace(
                '/\{\{\s*' . preg_quote($key, '/') . '\s*\}\}/',
                $value ?? '',
                $template
            );
        }

        return $template;
    }

    protected function sendDashboard(User $user, string $message, string $event): void
    {
        Notification::create([
            'user_id' => $user->id,
            'title' => $this->getNotificationTitle($event),
            'message' => $message,
        ]);
    }

    protected function sendEmail(User $user, string $message, string $event, array $variables): void
    {
        if (empty($user->email))
            return;

        try {
            Mail::to($user->email)->send(new SchoolNotificationMail(
                title: $this->getNotificationTitle($event),
                content: $message,
                variables: $variables,
                eventType: $event,
            ));

            $this->log($event, 'email', $user, $user->email, $message, 'sent');
        } catch (\Throwable $e) {
            $this->log($event, 'email', $user, $user->email, $message, 'failed', $e->getMessage());
        }
    }

    protected function sendWhatsapp(User $user, string $message, string $event): void
    {
        if (empty($user->phone_number))
            return;

        app(WhatsappService::class)->send($user->phone_number, $message, $user, $event);
    }

    /**
     * Judul notifikasi untuk setiap event.
     * Selalu tambahkan entry baru di sini jika menambah event baru.
     */
    protected function getNotificationTitle(string $event): string
    {
        return match ($event) {
            // Pendaftaran Sekolah
            'SchoolRegistered' => 'Pendaftaran Sekolah Baru',
            'SchoolApproved' => 'Pendaftaran Sekolah Disetujui',
            'SchoolRejected' => 'Pendaftaran Sekolah Ditolak',
            'SchoolSuspended' => 'Sekolah Dinonaktifkan',
            'SchoolActivated' => 'Sekolah Diaktifkan Kembali',

            // Akun & Pengguna
            'AccountCreated' => 'Akun Baru Anda Telah Dibuat',
            'UserRegistered' => 'Akun Pengguna Baru',
            'UserActivated' => 'Akun Diaktifkan',
            'UserDeactivated' => 'Akun Dinonaktifkan',
            'PasswordReset' => 'Reset Password',

            // Habit Siswa
            'HabitSubmitted' => 'Habit Baru Terkirim',
            'HabitApproved' => 'Habit Disetujui',
            'HabitRejected' => 'Habit Ditolak',
            'HabitPointAdded' => 'Poin Habit Ditambahkan',

            // Validasi
            'ParentValidationRequired' => 'Validasi Orang Tua Diperlukan',
            'ParentValidationApproved' => 'Orang Tua Menyetujui Habit',
            'ParentValidationRejected' => 'Orang Tua Menolak Habit',
            'TeacherValidationRequired' => 'Validasi Guru Diperlukan',
            'AIValidationPassed' => 'Lolos Validasi AI',
            'AIValidationFailed' => 'Ditolak Validasi AI',

            // Sholat & Ibadah
            'PrayerAttendanceRecorded' => 'Absensi Sholat Dicatat',
            'PrayerAttendanceMissed' => 'Sholat Terlewat',

            // Kelas G7 KAIH
            'StudentAddedToClass' => 'Ditambahkan ke Kelas',
            'StudentRemovedFromClass' => 'Dikeluarkan dari Kelas',
            'ClassScheduleChanged' => 'Jadwal Kelas Berubah',

            // Laporan
            'WeeklyReportReady' => 'Laporan Mingguan Siap',
            'MonthlyReportReady' => 'Laporan Bulanan Siap',
            'LowPointAlert' => 'Peringatan Poin Rendah',

            default => 'Notifikasi G7KAIH',
        };
    }

    protected function log(
        string $event,
        string $channel,
        User $user,
        string $recipient,
        string $message,
        string $status,
        ?string $error = null
    ): void {
        NotificationLog::create([
            'event' => $event,
            'channel' => $channel,
            'user_id' => $user->id,
            'school_id' => $user->school_id,
            'recipient' => $recipient,
            'message' => $message,
            'status' => $status,
            'error_message' => $error,
        ]);
    }
}