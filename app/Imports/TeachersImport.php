<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class TeachersImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
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

        $actualRowNumber = 11; // Row 11 adalah data pertama setelah header row 10

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
                $nip = $rowArray['nip_opsional'] ?? null;
                $nik = $rowArray['nik_opsional'] ?? null;
                $password = $rowArray['password'] ?? null;

                Log::info('Extracted values', [
                    'row' => $actualRowNumber,
                    'nama' => $nama,
                    'email' => $email,
                    'phone' => $nomorTelepon,
                    'agama' => $agama,
                    'nip_raw' => $nip,
                    'nik_raw' => $nik,
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

                // Convert NIP dan NIK dari scientific notation
                $nipConverted = $this->convertScientificNotation($nip);
                $nikConverted = $this->convertScientificNotation($nik);
                $phoneConverted = $this->convertScientificNotation($nomorTelepon);

                Log::info('Converted values', [
                    'row' => $actualRowNumber,
                    'phone_converted' => $phoneConverted,
                    'nip_converted' => $nipConverted,
                    'nik_converted' => $nikConverted
                ]);

                // Cek NIP unik jika diisi
                if (!empty($nipConverted) && Teacher::where('nip', $nipConverted)->exists()) {
                    $this->errors[] = "Baris {$actualRowNumber}: NIP {$nipConverted} sudah terdaftar";
                    $actualRowNumber++;
                    continue;
                }

                // Cek NIK unik jika diisi
                if (!empty($nikConverted) && Teacher::where('nik', $nikConverted)->exists()) {
                    $this->errors[] = "Baris {$actualRowNumber}: NIK {$nikConverted} sudah terdaftar";
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
                    'role' => 'guru',
                    'password' => Hash::make($password),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);

                // Create teacher
                $teacher = Teacher::create([
                    'user_id' => $user->id,
                    'nip' => $nipConverted,
                    'nik' => $nikConverted,
                ]);

                Log::info('Successfully imported', [
                    'row' => $actualRowNumber,
                    'user_id' => $user->id,
                    'teacher_id' => $teacher->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'nip' => $teacher->nip,
                    'nik' => $teacher->nik
                ]);

                // Send account creation notification
                try {
                    app(\App\Services\Notification\NotificationService::class)->send('AccountCreated', $user, [
                        'user_name' => $user->name,
                        'creator_name' => auth()->user()->name,
                        'email' => $user->email,
                        'password' => $password, // plain text
                        'student_nis' => '-',
                        'parent_login_code' => '-',
                    ], $this->schoolId);
                } catch (\Exception $e) {
                    Log::error('Import Notification failed for teacher', ['user_id' => $user->id, 'error' => $e->getMessage()]);
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
            'total_processed' => $actualRowNumber - 11
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_lengkap' => 'nullable|string|max:255',
            '*.email' => 'nullable|email|max:255',
            '*.nomor_telepon' => 'nullable',
            '*.agama' => 'nullable|string',
            '*.nip_opsional' => 'nullable',
            '*.nik_opsional' => 'nullable',
            '*.password' => 'nullable',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nama_lengkap.required' => 'Kolom Nama Lengkap wajib diisi',
            '*.email.required' => 'Kolom Email wajib diisi',
            '*.email.email' => 'Format email tidak valid',
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
            if ($row < 11) {
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
        return 10; // Header ada di row 10
    }
}