<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class StudentsImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
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

        // Jika sudah string, return as is
        if (is_string($value)) {
            return trim($value);
        }

        // Jika numeric (termasuk scientific notation), convert ke string
        if (is_numeric($value)) {
            // Gunakan sprintf untuk convert scientific notation ke string lengkap
            return sprintf('%.0f', $value);
        }

        return trim((string) $value);
    }

    /**
     * Check if row is truly empty (all important fields are empty)
     */
    private function isRowEmpty($row)
    {
        $rowArray = $row->toArray();

        // Get first few values to check
        $values = array_values($rowArray);
        $firstFewValues = array_slice($values, 0, 4); // Check first 4 columns

        // Row dianggap kosong jika semua nilai kosong atau null
        foreach ($firstFewValues as $value) {
            if (!empty($value)) {
                return false; // Ada nilai, berarti tidak kosong
            }
        }

        return true; // Semua kosong
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

        $actualRowNumber = 12; // Row 12 adalah data pertama setelah header row 11

        foreach ($rows as $index => $row) {
            try {
                // Skip baris kosong
                if ($this->isRowEmpty($row)) {
                    $actualRowNumber++;
                    continue;
                }

                // DEBUGGING: Log semua keys yang ada
                $rowArray = $row->toArray();
                Log::info('Row keys and values', [
                    'row_number' => $actualRowNumber,
                    'keys' => array_keys($rowArray),
                    'values' => $rowArray
                ]);

                // PERBAIKAN: Akses langsung dengan key yang tepat dari Excel
                // Laravel Excel akan convert header menjadi snake_case
                $nama = $rowArray['nama_lengkap'] ?? null;
                $email = $rowArray['email'] ?? null;
                $nomorTelepon = $rowArray['nomor_telepon'] ?? null;
                $agama = $rowArray['agama'] ?? null;
                $nisn = $rowArray['nisn_opsional'] ?? null;
                $nis = $rowArray['nis_opsional'] ?? null;
                $tingkatKelas = $rowArray['tingkat_kelas'] ?? null;
                $namaKelas = $rowArray['nama_kelas'] ?? null;
                $jurusan = $rowArray['jurusan'] ?? null;
                $jenisKelamin = $rowArray['jenis_kelamin'] ?? null;
                $password = $rowArray['password'] ?? null;

                Log::info('Extracted values', [
                    'row' => $actualRowNumber,
                    'nama' => $nama,
                    'email' => $email,
                    'phone' => $nomorTelepon,
                    'agama' => $agama,
                    'nisn_raw' => $nisn,
                    'nis_raw' => $nis,
                    'grade_level' => $tingkatKelas,
                    'class_name' => $namaKelas,
                    'major' => $jurusan,
                    'gender' => $jenisKelamin,
                    'password' => $password ? '***' : null
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

                // Validate email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[] = "Baris {$actualRowNumber}: Format email tidak valid";
                    $actualRowNumber++;
                    continue;
                }

                if (empty($tingkatKelas)) {
                    $this->errors[] = "Baris {$actualRowNumber}: Kolom Tingkat Kelas wajib diisi";
                    $actualRowNumber++;
                    continue;
                }

                // Validasi tingkat kelas
                $validGradeLevels = ['X', 'XI', 'XII'];
                $tingkatKelas = strtoupper(trim($tingkatKelas));
                if (!in_array($tingkatKelas, $validGradeLevels)) {
                    $this->errors[] = "Baris {$actualRowNumber}: Tingkat Kelas harus X, XI, atau XII";
                    $actualRowNumber++;
                    continue;
                }

                if (empty($namaKelas)) {
                    $this->errors[] = "Baris {$actualRowNumber}: Kolom Nama Kelas wajib diisi";
                    $actualRowNumber++;
                    continue;
                }

                // Validasi jenis kelamin jika diisi
                if (!empty($jenisKelamin)) {
                    $jenisKelamin = trim($jenisKelamin);
                    if (!in_array($jenisKelamin, ['Laki-laki', 'Perempuan'])) {
                        $this->errors[] = "Baris {$actualRowNumber}: Jenis Kelamin harus 'Laki-laki' atau 'Perempuan'";
                        $actualRowNumber++;
                        continue;
                    }
                }

                // Generate password default jika tidak ada
                $password = !empty($password) ? $password : $this->generateRandomPassword();

                // Validasi panjang password
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

                // Convert NISN, NIS, dan phone dari scientific notation
                $nisnConverted = $this->convertScientificNotation($nisn);
                $nisConverted = $this->convertScientificNotation($nis);
                $phoneConverted = $this->convertScientificNotation($nomorTelepon);

                Log::info('Converted values', [
                    'row' => $actualRowNumber,
                    'phone_converted' => $phoneConverted,
                    'nisn_converted' => $nisnConverted,
                    'nis_converted' => $nisConverted
                ]);

                // Cek NISN unik jika diisi
                if (!empty($nisnConverted) && Student::where('nisn', $nisnConverted)->exists()) {
                    $this->errors[] = "Baris {$actualRowNumber}: NISN {$nisnConverted} sudah terdaftar";
                    $actualRowNumber++;
                    continue;
                }

                // Cek NIS unik jika diisi
                if (!empty($nisConverted) && Student::where('nis', $nisConverted)->exists()) {
                    $this->errors[] = "Baris {$actualRowNumber}: NIS {$nisConverted} sudah terdaftar";
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
                    'role' => 'siswa',
                    'password' => Hash::make($password),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);

                // Create student
                $student = Student::create([
                    'user_id' => $user->id,
                    'nisn' => $nisnConverted,
                    'nis' => $nisConverted,
                    'grade_level' => $tingkatKelas,
                    'class_name' => trim($namaKelas),
                    'major' => !empty($jurusan) ? trim($jurusan) : null,
                    'gender' => !empty($jenisKelamin) ? $jenisKelamin : null,
                ]);

                Log::info('Successfully imported', [
                    'row' => $actualRowNumber,
                    'user_id' => $user->id,
                    'student_id' => $student->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'nisn' => $student->nisn,
                    'nis' => $student->nis,
                    'grade_level' => $student->grade_level,
                    'class_name' => $student->class_name
                ]);

                // Send account creation notification
                try {
                    app(\App\Services\Notification\NotificationService::class)->send('AccountCreated', $user, [
                        'user_name' => $user->name,
                        'creator_name' => auth()->user()->name,
                        'email' => $user->email,
                        'password' => $password, // plain text
                        'student_nis' => $student->nis ?? $student->nisn ?? '-',
                        'parent_login_code' => '-',
                    ], $this->schoolId);
                } catch (\Exception $e) {
                    Log::error('Import Notification failed for student', ['user_id' => $user->id, 'error' => $e->getMessage()]);
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
            'total_processed' => $actualRowNumber - 12
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_lengkap' => 'nullable|string|max:255',
            '*.email' => 'nullable|email|max:255',
            '*.nomor_telepon' => 'nullable',
            '*.agama' => 'nullable|string',
            '*.nisn_opsional' => 'nullable',
            '*.nis_opsional' => 'nullable',
            '*.tingkat_kelas' => 'nullable|in:X,XI,XII',
            '*.nama_kelas' => 'nullable|string|max:50',
            '*.jurusan' => 'nullable|string|max:50',
            '*.jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            '*.password' => 'nullable',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nama_lengkap.required' => 'Kolom Nama Lengkap wajib diisi',
            '*.email.required' => 'Kolom Email wajib diisi',
            '*.email.email' => 'Format email tidak valid',
            '*.tingkat_kelas.in' => 'Tingkat kelas harus X, XI, atau XII',
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
            if ($row < 12) {
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

    public function getRowCount()
    {
        return $this->successCount;
    }

    public function headingRow(): int
    {
        return 11; // Header ada di row 11
    }
}