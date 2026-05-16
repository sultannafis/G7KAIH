<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;


class AccountCreatedNotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus template AccountCreated lama agar tidak ada yang tertinggal
        NotificationTemplate::where('event', 'AccountCreated')
            ->whereNull('school_id')
            ->delete();

        $roles = ['siswa', 'orangtua', 'guru'];

        $templates = [
            // ── EMAIL: Semua Role ──────────────────────────────────────────────
            [
                'school_id'        => null,
                'event'            => 'AccountCreated',
                'channel'          => 'email',
                'target_role'      => $roles,
                'message_template' =>
                    "Halo {{user_name}},\n\n" .
                    "Akun Anda telah dibuatkan oleh {{creator_name}} di platform G7KAIH.\n\n" .
                    "Berikut detail akun Anda:\n" .
                    "  Email : {{email}}\n\n" .
                    "Cara login:\n" .
                    "1. Buka halaman login di: {{login_url}}\n" .
                    "2. Masukkan email (atau kode login untuk Orang Tua) dan gunakan link reset di bawah untuk membuat kata sandi baru.\n\n" .
                    "Atur kata sandi Anda (link berlaku 24 jam):\n" .
                    "{{reset_url}}\n\n" .
                    "Setelah login, Anda juga dapat mengganti kata sandi kapan saja melalui menu Pengaturan.\n\n" .
                    "Terimakasih,\n" .
                    "Tim G7KAIH",
                'editable_by'      => 'admin_school',
                'is_active'        => true,
            ],
            // ── WHATSAPP: Semua Role ───────────────────────────────────────────
            [
                'school_id'        => null,
                'event'            => 'AccountCreated',
                'channel'          => 'whatsapp',
                'target_role'      => $roles,
                'message_template' =>
                    "Halo {{user_name}},\n\n" .
                    "Akun Anda telah dibuatkan oleh {{creator_name}} di platform G7KAIH.\n\n" .
                    "Detail akun:\n" .
                    "Email : {{email}}\n\n" .
                    "Cara login:\n" .
                    "1. Buka: {{login_url}}\n" .
                    "2. Masukkan email (atau kode login untuk Orang Tua), lalu atur kata sandi melalui link berikut (berlaku 24 jam):\n" .
                    "{{reset_url}}\n\n" .
                    "Setelah login, Anda dapat mengganti kata sandi melalui menu Pengaturan.\n\n" .
                    "Terimakasih,\nTim G7KAIH",
                'editable_by'      => 'admin_school',
                'is_active'        => true,
            ],
        ];

        foreach ($templates as $tpl) {
            NotificationTemplate::create($tpl);
        }
    }
}

