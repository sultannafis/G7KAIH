<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  
  <br/>
  
  # 🚀 G7KAIH - Sistem Manajemen Sekolah & Habit Tracker
  
  [![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
  [![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
  [![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev)
  [![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)

  <p align="center">
    <strong>Aplikasi Modern Manajemen Sekolah Berbasis Role (RBAC) dengan Fitur Pelacakan Kebiasaan (Habit Tracking) & AI.</strong>
  </p>
</div>

---

## ✨ Fitur Utama

Aplikasi ini menggunakan sistem **Role-Based Access Control (RBAC)** yang sangat detail, membagi hak akses ke dalam 5 tingkatan pengguna:

### 👑 1. Master Admin
* Manajemen Approval Sekolah (Setujui/Tolak pendaftaran sekolah baru).
* Manajemen Manajemen Pengaturan Sistem & AI.
* Manajemen Template Notifikasi.

### 🏫 2. Admin Sekolah (School Admin)
* **Manajemen Pengguna Terpusat:** Mengelola Guru, Siswa, dan Orang Tua (CRUD, Import/Export Excel).
* **Manajemen Kelas & QR Card:** Pembuatan kelas dan cetak Kartu QR untuk siswa.
* **Manajemen Habit:** Membuat aturan dan item pembiasaan (seperti Sholat, kegiatan harian) dengan prioritas dan jadwal.

### 👨‍🏫 3. Guru (Teacher)
* **Validasi Habit:** Memvalidasi (Setujui/Tolak) laporan pembiasaan (habit) yang dikirim siswa.
* **Absensi Sholat:** Pencatatan dan manajemen kehadiran sholat siswa.
* **Manajemen Kelasku:** Melihat daftar siswa di kelasnya, memantau laporan, dan mengunduh Rapor dalam bentuk PDF.

### 👨‍👩‍👧 4. Orang Tua (Parent)
* **Dashboard Pemantauan:** Memantau perkembangan dan aktivitas habit anak secara real-time.
* **Validasi Orang Tua:** Menyetujui atau menolak laporan kegiatan di rumah yang disubmit oleh anak (Tanda tangan digital).

### 🎓 5. Siswa (Student)
* **Dashboard Interaktif:** Melihat progres dan statistik habit harian.
* **Submit Habit:** Mengirimkan laporan kegiatan dan sholat (Reguler & Quick Submit) lengkap dengan bukti foto/keterangan.

### 🤖 Fitur Canggih Lainnya
* **Integrasi AI Chat** untuk bantuan interaktif.
* **Cetak Rapor & Export Data:** Dukungan `DomPDF` dan `Maatwebsite Excel`.
* **Sistem Notifikasi & QR Code:** Dukungan `Simple QrCode` dan manajemen template notifikasi.
* **Cloud Storage:** Dukungan `Cloudinary` untuk penyimpanan gambar/foto bukti habit.

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

| Kategori | Teknologi |
| :--- | :--- |
| **Framework Backend** | [Laravel 12](https://laravel.com/) (PHP ^8.2) |
| **Framework Frontend** | [Tailwind CSS v3](https://tailwindcss.com/) & [Alpine.js](https://alpinejs.dev/) |
| **Database** | MySQL / SQLite |
| **PDF Generator** | [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) |
| **Excel Export/Import** | [maatwebsite/excel](https://laravel-excel.com/) |
| **QR Code** | [simplesoftwareio/simple-qrcode](https://www.simplesoftwareio.com/docs/simple-qrcode) |
| **Image Hosting** | [Cloudinary Laravel](https://cloudinary.com/) |
| **Icons** | [Blade Heroicons](https://github.com/blade-ui-kit/blade-heroicons) |

---

## ⚙️ Cara Instalasi (Installation Guide)

Ikuti langkah-langkah di bawah ini untuk menjalankan project di komputer lokal kamu (Localhost):

### 1. Persyaratan Sistem (Prerequisites)
Pastikan kamu sudah menginstal:
- **PHP** >= 8.2
- **Composer** (Package Manager untuk PHP)
- **Node.js & NPM** (Package Manager untuk JavaScript)
- **Database Server** (MySQL/MariaDB via XAMPP/Laragon, dll)

### 2. Langkah Instalasi

Clone repository ini ke komputer kamu:
```bash
git clone https://github.com/username/G7KAIH.git
cd G7KAIH
```

Install semua dependensi PHP menggunakan Composer:
```bash
composer install
```

Install semua dependensi Frontend menggunakan NPM:
```bash
npm install
```

Salin file environment dan atur konfigurasi database:
```bash
cp .env.example .env
```
*(Jangan lupa buka file `.env` dan atur `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` sesuai dengan database kamu)*

Generate Application Key:
```bash
php artisan key:generate
```

Jalankan Migrasi Database (dan Seeder jika ada):
```bash
php artisan migrate --seed
```
*(Tambahkan `--seed` jika kamu memiliki data dummy bawaan untuk user dan role)*

Jalankan Build Frontend (Vite):
```bash
npm run build
```

### 3. Menjalankan Aplikasi
Jalankan server lokal artisan:
```bash
php artisan serve
```

Di terminal atau tab baru, jalankan Vite untuk proses development (jika sedang mengedit tampilan):
```bash
npm run dev
```

Buka browser kamu dan akses: **`http://localhost:8000`** 🎉

---

<div align="center">
  <img src="https://media.giphy.com/media/LmNwrBhejkK9EFP504/giphy.gif" width="150" alt="Coding Animation">
  <p><b>Happy Coding! Dibuat dengan ❤️ untuk Pendidikan.</b></p>
</div>
