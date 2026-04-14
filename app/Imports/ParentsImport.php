<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Parents;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class ParentsImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    private $schoolId;
    private $successCount = 0;
    private $errors = [];

    public function __construct($schoolId)
    {
        $this->schoolId = $schoolId;
    }

    /**
     * Convert scientific notation to string
     */
    private function convertScientificNotation($value)
    {
        if (empty($value)) {
            return null;
        }

        if (is_string($value)) {
            return trim($value);
        }

        if (is_numeric($value)) {
            return sprintf('%.0f', $value);
        }

        return trim((string) $value);
    }

    /**
     * Check if row is truly empty
     */
    private function isRowEmpty($row)
    {
        $rowArray = $row->toArray();
        $values = array_values($rowArray);
        $firstFewValues = array_slice($values, 0, 4);

        foreach ($firstFewValues as $value) {
            if (!empty($value)) {
                return false;
            }
        }

        return true;
    }

    public function collection(Collection $rows)
    {
        Log::info('Import started', [
            'total_rows' => $rows->count(),
            'school_id' => $this->schoolId
        ]);

        if ($rows->isEmpty()) {
            Log::error('No rows found in Excel file');
            $this->errors[] = "File Excel kosong atau format header tidak sesuai";
            return;
        }

        $actualRowNumber = 13;

        foreach ($rows as $index => $row) {
            try {
                if ($this->isRowEmpty($row)) {
                    $actualRowNumber++;
                    continue;
                }

                $rowArray = $row->toArray();

                // PERBAIKAN: Laravel Excel otomatis convert header ke snake_case
                // "Nama Lengkap" → "nama_lengkap"
                // "NISN Siswa" → "nisn_siswa"
                // "NIS Siswa" → "nis_siswa"
                // "Hubungan Keluarga" → "hubungan_keluarga"
                // "Nomor Telepon" → "nomor_telepon"

                Log::info('Row keys and values', [
                    'row_number' => $actualRowNumber,
                    'keys' => array_keys($rowArray),
                    'values' => $rowArray
                ]);

                // Extract values - sudah benar
                $nama = $rowArray['nama_lengkap'] ?? null;
                $email = $rowArray['email'] ?? null;
                $nomorTelepon = $rowArray['nomor_telepon'] ?? null;
                $agama = $rowArray['agama'] ?? null;
                $nisnSiswa = $rowArray['nisn_siswa'] ?? null;
                $nisSiswa = $rowArray['nis_siswa'] ?? null;
                $hubunganKeluarga = $rowArray['hubungan_keluarga'] ?? null;
                $password = $rowArray['password'] ?? null;
                $kodeLogin = $rowArray['kode_login'] ?? null;

                Log::info('Extracted values', [
                    'row' => $actualRowNumber,
                    'nama' => $nama,
                    'email' => $email,
                    'nisn_siswa' => $nisnSiswa,
                    'nis_siswa' => $nisSiswa,
                    'relationship' => $hubunganKeluarga
                ]);

                // Validasi field wajib
                if (empty($nama)) {
                    $this->errors[] = "Baris {$actualRowNumber}: Kolom Nama Lengkap wajib diisi";
                    $actualRowNumber++;
                    continue;
                }

                if (empty($email)) {
                    $this->errors[] = "Baris {$actualRowNumber}: Kolom Email wajib diisi";
                    $actualRowNumber++;
                    continue;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[] = "Baris {$actualRowNumber}: Format email tidak valid";
                    $actualRowNumber++;
                    continue;
                }

                if (empty($hubunganKeluarga)) {
                    $this->errors[] = "Baris {$actualRowNumber}: Kolom Hubungan Keluarga wajib diisi";
                    $actualRowNumber++;
                    continue;
                }

                // PERBAIKAN: Validasi NISN atau NIS harus ada salah satu
                if (empty($nisnSiswa) && empty($nisSiswa)) {
                    $this->errors[] = "Baris {$actualRowNumber}: NISN Siswa atau NIS Siswa harus diisi minimal salah satu";
                    $actualRowNumber++;
                    continue;
                }

                // Generate password default jika tidak ada
                $password = !empty($password) ? $password : $this->generateRandomPassword();

                if (strlen($password) < 8) {
                    $this->errors[] = "Baris {$actualRowNumber}: Password minimal 8 karakter";
                    $actualRowNumber++;
                    continue;
                }

                // Cek apakah email sudah ada
                $emailCheck = trim(strtolower($email));
                if (User::where('email', $emailCheck)->exists()) {
                    $this->errors[] = "Baris {$actualRowNumber}: Email {$emailCheck} sudah terdaftar";
                    $actualRowNumber++;
                    continue;
                }

                // Convert NISN dan NIS
                $nisnConverted = $this->convertScientificNotation($nisnSiswa);
                $nisConverted = $this->convertScientificNotation($nisSiswa);
                $phoneConverted = $this->convertScientificNotation($nomorTelepon);

                // Cari student berdasarkan NISN atau NIS
                $student = null;

                if (!empty($nisnConverted)) {
                    $student = Student::where('nisn', $nisnConverted)->first();

                    if ($student) {
                        Log::info("Student found by NISN", [
                            'row' => $actualRowNumber,
                            'nisn' => $nisnConverted,
                            'student_id' => $student->id
                        ]);
                    }
                }

                if (!$student && !empty($nisConverted)) {
                    $student = Student::where('nis', $nisConverted)->first();

                    if ($student) {
                        Log::info("Student found by NIS", [
                            'row' => $actualRowNumber,
                            'nis' => $nisConverted,
                            'student_id' => $student->id
                        ]);
                    }
                }

                if (!$student) {
                    $this->errors[] = "Baris {$actualRowNumber}: Siswa dengan NISN '{$nisnConverted}' atau NIS '{$nisConverted}' tidak ditemukan di sistem";
                    $actualRowNumber++;
                    continue;
                }

                // Validasi siswa di sekolah yang sama
                if ($student->user->school_id !== $this->schoolId) {
                    $this->errors[] = "Baris {$actualRowNumber}: Siswa tidak terdaftar di sekolah Anda";
                    $actualRowNumber++;
                    continue;
                }

                // Create user
                $user = User::create([
                    'school_id' => $this->schoolId,
                    'name' => trim($nama),
                    'email' => $emailCheck,
                    'phone_number' => $phoneConverted,
                    'religion' => !empty($agama) ? trim($agama) : null,
                    'role' => 'orangtua',
                    'password' => Hash::make($password),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);

                // Create parent with login_code
                $parent = Parents::create([
                    'user_id' => $user->id,
                    'student_id' => $student->id,
                    'family_relationship' => trim($hubunganKeluarga),
                    'login_code' => !empty($kodeLogin) ? trim($kodeLogin) : null, // auto-generated by model boot if null
                ]);

                Log::info('Successfully imported', [
                    'row' => $actualRowNumber,
                    'user_id' => $user->id,
                    'parent_id' => $parent->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'student_id' => $student->id,
                    'relationship' => $parent->family_relationship
                ]);

                // Send account creation notification
                try {
                    $user->load('parent');
                    app(\App\Services\Notification\NotificationService::class)->send('AccountCreated', $user, [
                        'user_name' => $user->name,
                        'creator_name' => auth()->user()->name,
                        'email' => $user->email,
                        'password' => $password, // plain text
                        'student_nis' => '-',
                        'parent_login_code' => $user->parent->login_code ?? '-',
                    ], $this->schoolId);
                } catch (\Exception $e) {
                    Log::error('Import Notification failed for parent', ['user_id' => $user->id, 'error' => $e->getMessage()]);
                }

                $this->successCount++;
                $actualRowNumber++;

            } catch (\Exception $e) {
                Log::error('Import row error', [
                    'row' => $actualRowNumber,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                $this->errors[] = "Baris {$actualRowNumber}: " . $e->getMessage();
                $actualRowNumber++;
            }
        }

        Log::info('Import completed', [
            'success_count' => $this->successCount,
            'error_count' => count($this->errors),
            'total_processed' => $actualRowNumber - 13
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_lengkap' => 'nullable|string|max:255',
            '*.email' => 'nullable|email|max:255',
            '*.nomor_telepon' => 'nullable',
            '*.agama' => 'nullable|string',
            '*.nisn_siswa' => 'nullable',
            '*.nis_siswa' => 'nullable',
            '*.hubungan_keluarga' => 'nullable|string|max:50',
            '*.password' => 'nullable',
            '*.kode_login' => 'nullable|string',
        ];
    }

    private function generateRandomPassword($length = 12)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $password;
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $row = $failure->row();
            if ($row < 13) {
                continue;
            }

            $this->errors[] = "Baris {$row}: " . implode(', ', $failure->errors());
        }
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function headingRow(): int
    {
        return 12;
    }
}