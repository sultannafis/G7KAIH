<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            // ── Global templates (masteradmin only) ──────────────────
            [
                'school_id'        => null,
                'event'            => 'SchoolApproved',
                'channel'          => 'email',
                'target_role'      => 'admin',
                'message_template' => "Halo {{admin_name}},\n\nSelamat! Pendaftaran sekolah {{school_name}} telah DISETUJUI pada {{date}}.\n\nAnda kini dapat mengakses dashboard sekolah Anda.\n\nSalam,\nTim G7KAIH",
                'editable_by'      => 'masteradmin',
                'is_active'        => true,
            ],
            [
                'school_id'        => null,
                'event'            => 'SchoolApproved',
                'channel'          => 'whatsapp',
                'target_role'      => 'admin',
                'message_template' => "✅ *Sekolah Disetujui*\n\nHalo {{admin_name}},\nSekolah *{{school_name}}* telah disetujui pada {{date}}.\n\nSilakan login ke sistem G7KAIH.",
                'editable_by'      => 'masteradmin',
                'is_active'        => true,
            ],
            [
                'school_id'        => null,
                'event'            => 'SchoolRejected',
                'channel'          => 'email',
                'target_role'      => 'admin',
                'message_template' => "Halo {{admin_name}},\n\nMohon maaf, pendaftaran sekolah {{school_name}} DITOLAK pada {{date}}.\n\nAlasan: {{rejection_reason}}\n\nJika ada pertanyaan, hubungi tim support kami.\n\nSalam,\nTim G7KAIH",
                'editable_by'      => 'masteradmin',
                'is_active'        => true,
            ],
            [
                'school_id'        => null,
                'event'            => 'SchoolRejected',
                'channel'          => 'whatsapp',
                'target_role'      => 'admin',
                'message_template' => "❌ *Pendaftaran Ditolak*\n\nHalo {{admin_name}},\nMaaf, sekolah *{{school_name}}* ditolak.\nAlasan: {{rejection_reason}}",
                'editable_by'      => 'masteradmin',
                'is_active'        => true,
            ],

            // ── School-level templates (admin_school editable) ────────
            [
                'school_id'        => null,
                'event'            => 'HabitSubmitted',
                'channel'          => 'dashboard',
                'target_role'      => 'guru',
                'message_template' => "{{student_name}} telah mengumpulkan habit {{habit_name}} pada {{date}}.",
                'editable_by'      => 'admin_school',
                'is_active'        => true,
            ],
            [
                'school_id'        => null,
                'event'            => 'HabitSubmitted',
                'channel'          => 'whatsapp',
                'target_role'      => 'orangtua',
                'message_template' => "📋 *Habit Terkirim*\n\nHalo,\n{{student_name}} telah mengumpulkan habit *{{habit_name}}* pada {{date}}.\n\nMohon validasi melalui aplikasi G7KAIH.",
                'editable_by'      => 'admin_school',
                'is_active'        => true,
            ],
            [
                'school_id'        => null,
                'event'            => 'HabitApproved',
                'channel'          => 'dashboard',
                'target_role'      => 'siswa',
                'message_template' => "Habit {{habit_name}} Anda telah disetujui pada {{date}}. Pertahankan! 🎉",
                'editable_by'      => 'admin_school',
                'is_active'        => true,
            ],
            [
                'school_id'        => null,
                'event'            => 'HabitRejected',
                'channel'          => 'dashboard',
                'target_role'      => 'siswa',
                'message_template' => "Habit {{habit_name}} Anda ditolak. Silakan coba lagi.",
                'editable_by'      => 'admin_school',
                'is_active'        => true,
            ],
            [
                'school_id'        => null,
                'event'            => 'ParentValidation',
                'channel'          => 'dashboard',
                'target_role'      => 'orangtua',
                'message_template' => "{{student_name}} membutuhkan validasi habit {{habit_name}} Anda pada {{date}}.",
                'editable_by'      => 'admin_school',
                'is_active'        => true,
            ],
        ];

        foreach ($templates as $tpl) {
            NotificationTemplate::updateOrCreate(
                [
                    'school_id'   => $tpl['school_id'],
                    'event'       => $tpl['event'],
                    'channel'     => $tpl['channel'],
                    'target_role' => $tpl['target_role'],
                ],
                $tpl
            );
        }
    }
}
