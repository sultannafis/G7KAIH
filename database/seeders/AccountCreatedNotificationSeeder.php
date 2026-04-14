<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class AccountCreatedNotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus template AccountCreated lama agar tidak ada yang tertinggal
        \App\Models\NotificationTemplate::where('event', 'AccountCreated')
            ->whereNull('school_id')
            ->delete();

        $templates = [
            // ── EMAIL: Siswa ──────────────────────────────────────────────
            [
                'school_id'        => null,
                'event'            => 'AccountCreated',
                'channel'          => 'email',
                'target_role'      => ['siswa'],
                'message_template' =>
                    "Halo {{user_name}},\n\n" .
                    "Akun Anda telah dibuatkan oleh {{creator_name}} di platform G7KAIH.\n\n" .
                    "Berikut detail akun Anda:\n" .
                    "  Email    : {{email}}\n" .
                    "  NIS/NISN : {{student_nis}}\n\n" .
                    "Cara login:\n" .
                    "1. Buka halaman login di: {{login_url}}\n" .
                    "2. Masukkan email dan gunakan link reset di bawah untuk membuat kata sandi baru.\n\n" .
                    "Atur kata sandi Anda (link berlaku 24 jam):\n" .
                    "{{reset_url}}\n\n" .
                    "Setelah login, Anda juga dapat mengganti kata sandi kapan saja melalui menu Pengaturan.\n\n" .
                    "Terimakasih,\n" .
                    "Tim G7KAIH",
                'editable_by'      => 'admin_school',
                'is_active'        => true,
            ],
            // ── EMAIL: Orang Tua ──────────────────────────────────────────
            [
                'school_id'        => null,
                'event'            => 'AccountCreated',
                'channel'          => 'email',
                'target_role'      => ['orangtua'],
                'message_template' =>
                    "Halo {{user_name}},\n\n" .
                    "Akun Anda sebagai Orang Tua/Wali telah dibuatkan oleh {{creator_name}} di platform G7KAIH.\n\n" .
                    "Berikut detail akun Anda:\n" .
                    "  Email      : {{email}}\n" .
                    "  Kode Login : {{parent_login_code}}\n\n" .
                    "Cara login:\n" .
                    "1. Buka halaman login di: {{login_url}}\n" .
                    "2. Pilih menu login Orang Tua dan masukkan kode login di atas, atau login menggunakan email.\n" .
                    "3. Gunakan link di bawah untuk membuat kata sandi baru.\n\n" .
                    "Atur kata sandi Anda (link berlaku 24 jam):\n" .
                    "{{reset_url}}\n\n" .
                    "Setelah login, Anda juga dapat mengganti kata sandi kapan saja melalui menu Pengaturan.\n\n" .
                    "Terimakasih,\n" .
                    "Tim G7KAIH",
                'editable_by'      => 'admin_school',
                'is_active'        => true,
            ],
            // ── EMAIL: Guru ───────────────────────────────────────────────
            [
                'school_id'        => null,
                'event'            => 'AccountCreated',
                'channel'          => 'email',
                'target_role'      => ['guru'],
                'message_template' =>
                    "Halo {{user_name}},\n\n" .
                    "Akun Anda sebagai Guru telah dibuatkan oleh {{creator_name}} di platform G7KAIH.\n\n" .
                    "Berikut detail akun Anda:\n" .
                    "  Email : {{email}}\n\n" .
                    "Cara login:\n" .
                    "1. Buka halaman login di: {{login_url}}\n" .
                    "2. Masukkan email dan gunakan link reset di bawah untuk membuat kata sandi baru.\n\n" .
                    "Atur kata sandi Anda (link berlaku 24 jam):\n" .
                    "{{reset_url}}\n\n" .
                    "Setelah login, Anda juga dapat mengganti kata sandi kapan saja melalui menu Pengaturan.\n\n" .
                    "Terimakasih,\n" .
                    "Tim G7KAIH",
                'editable_by'      => 'admin_school',
                'is_active'        => true,
            ],

            // ── WHATSAPP: Siswa ───────────────────────────────────────────
            [
                'school_id'        => null,
                'event'            => 'AccountCreated',
                'channel'          => 'whatsapp',
                'target_role'      => ['siswa'],
                'message_template' =>
                    "Halo {{user_name}},\n\n" .
                    "Akun Anda telah dibuatkan oleh {{creator_name}} di platform G7KAIH.\n\n" .
                    "Detail akun:\n" .
                    "Email    : {{email}}\n" .
                    "NIS/NISN : {{student_nis}}\n\n" .
                    "Cara login:\n" .
                    "1. Buka: {{login_url}}\n" .
                    "2. Masukkan email, lalu atur kata sandi melalui link berikut (berlaku 24 jam):\n" .
                    "{{reset_url}}\n\n" .
                    "Setelah login, Anda dapat mengganti kata sandi melalui menu Pengaturan.\n\n" .
                    "Terimakasih,\nTim G7KAIH",
                'editable_by'      => 'admin_school',
                'is_active'        => true,
            ],
            // ── WHATSAPP: Orang Tua ───────────────────────────────────────
            [
                'school_id'        => null,
                'event'            => 'AccountCreated',
                'channel'          => 'whatsapp',
                'target_role'      => ['orangtua'],
                'message_template' =>
                    "Halo {{user_name}},\n\n" .
                    "Akun Anda sebagai Orang Tua/Wali telah dibuatkan oleh {{creator_name}} di platform G7KAIH.\n\n" .
                    "Detail akun:\n" .
                    "Email      : {{email}}\n" .
                    "Kode Login : {{parent_login_code}}\n\n" .
                    "Cara login:\n" .
                    "1. Buka: {{login_url}}\n" .
                    "2. Pilih menu login Orang Tua, masukkan kode login, atau login dengan email.\n" .
                    "3. Atur kata sandi melalui link berikut (berlaku 24 jam):\n" .
                    "{{reset_url}}\n\n" .
                    "Setelah login, Anda dapat mengganti kata sandi melalui menu Pengaturan.\n\n" .
                    "Terimakasih,\nTim G7KAIH",
                'editable_by'      => 'admin_school',
                'is_active'        => true,
            ],
            // ── WHATSAPP: Guru ────────────────────────────────────────────
            [
                'school_id'        => null,
                'event'            => 'AccountCreated',
                'channel'          => 'whatsapp',
                'target_role'      => ['guru'],
                'message_template' =>
                    "Halo {{user_name}},\n\n" .
                    "Akun Anda sebagai Guru telah dibuatkan oleh {{creator_name}} di platform G7KAIH.\n\n" .
                    "Detail akun:\n" .
                    "Email : {{email}}\n\n" .
                    "Cara login:\n" .
                    "1. Buka: {{login_url}}\n" .
                    "2. Masukkan email, lalu atur kata sandi melalui link berikut (berlaku 24 jam):\n" .
                    "{{reset_url}}\n\n" .
                    "Setelah login, Anda dapat mengganti kata sandi melalui menu Pengaturan.\n\n" .
                    "Terimakasih,\nTim G7KAIH",
                'editable_by'      => 'admin_school',
                'is_active'        => true,
            ],
        ];

        foreach ($templates as $tpl) {
            \App\Models\NotificationTemplate::create($tpl);
        }
    }
}

