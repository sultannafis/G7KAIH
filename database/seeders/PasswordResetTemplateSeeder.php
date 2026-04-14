<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class PasswordResetTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [];
        $roles = ['admin', 'masteradmin', 'siswa', 'Guru', 'orangtua'];

        foreach ($roles as $role) {
            $r = strtolower($role);
            // Email Template
            $templates[] = [
                'event'            => 'PasswordReset',
                'channel'          => 'email',
                'target_role'      => $r,
                'message_template' => "Halo {{user_name}},\n\nKami menerima permintaan untuk mereset password akun G7KAIH Anda.\n\nSilakan klik tombol di bawah ini (atau salin tautan) untuk melanjutkan proses reset password:\n\n{{reset_url}}\n\nTautan ini akan kedaluwarsa dalam 60 menit. Jika Anda tidak merasa melakukan permintaan ini, abaikan saja notifikasi ini.\n\nTerima Kasih,\nTim {{app_name}}",
                'editable_by'      => 'masteradmin',
                'is_active'        => true,
            ];

            // Whatsapp Template
            $templates[] = [
                'event'            => 'PasswordReset',
                'channel'          => 'whatsapp',
                'target_role'      => $r,
                'message_template' => "Halo *{{user_name}}*,\n\nKami menerima permintaan reset password untuk akun G7KAIH Anda. Klik link berikut untuk mereset password Anda:\n{{reset_url}}\n\nJika ini bukan Anda, mohon abaikan pesan ini.",
                'editable_by'      => 'masteradmin',
                'is_active'        => true,
            ];
        }

        foreach ($templates as $template) {
            NotificationTemplate::firstOrCreate(
                [
                    'event'       => $template['event'],
                    'channel'     => $template['channel'],
                    'target_role' => $template['target_role'],
                    'school_id'   => null,
                ],
                $template
            );
        }
    }
}
