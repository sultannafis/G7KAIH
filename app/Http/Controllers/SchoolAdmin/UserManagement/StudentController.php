<?php

namespace App\Http\Controllers\SchoolAdmin\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Imports\StudentsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StudentController extends Controller
{
    /**
     * Download template Excel
     */
    public function downloadTemplate()
    {
        try {
            $path = storage_path('app/templates/student_import_template.xlsx');

            Log::info('Download template diminta', [
                'path' => $path,
                'exists' => file_exists($path),
                'user' => auth()->id()
            ]);

            // Generate template jika belum ada
            if (!file_exists($path)) {
                $directory = storage_path('app/templates');
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
                $this->generateTemplate();
            }

            // Verifikasi file sudah dibuat
            if (!file_exists($path)) {
                throw new \Exception('Template file tidak dapat dibuat.');
            }

            // Verifikasi file bisa dibaca
            if (!is_readable($path)) {
                throw new \Exception('Template file tidak dapat dibaca. Periksa permission file.');
            }

            Log::info('Download template berhasil', [
                'file_size' => filesize($path),
                'mime_type' => mime_content_type($path)
            ]);

            return response()->download($path, 'template_import_siswa_' . date('Ymd_His') . '.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="template_import_siswa_' . date('Ymd_His') . '.xlsx"',
            ])->deleteFileAfterSend(false);

        } catch (\Exception $e) {
            Log::error('Gagal download template', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mendownload template: ' . $e->getMessage());
        }
    }

    /**
     * Generate template Excel
     */
    private function generateTemplate()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set judul
            $sheet->setCellValue('A1', 'TEMPLATE IMPORT DATA SISWA');
            $sheet->mergeCells('A1:K1');

            // Set judul styling
            $sheet->getStyle('A1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => '2E86C1']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);
            $sheet->getRowDimension('1')->setRowHeight(25);

            // Instruksi
            $sheet->setCellValue('A3', 'INSTRUKSI:');
            $sheet->getStyle('A3')->getFont()->setBold(true);

            $instructions = [
                'A4' => '1. Kolom bertanda * (bintang) wajib diisi',
                'A5' => '2. Email harus unik dan belum terdaftar',
                'A6' => '3. Password minimal 8 karakter',
                'A7' => '4. NISN dan NIS harus unik jika diisi',
                'A8' => '5. Tingkat Kelas: X, XI, atau XII',
                'A9' => '6. Jenis Kelamin: Laki-laki atau Perempuan',
                'A10' => '7. JANGAN MERUBAH NAMA KOLOM DI BARIS 11!'
            ];

            foreach ($instructions as $cell => $instruction) {
                $sheet->setCellValue($cell, $instruction);
            }

            // PENTING: Header kolom - gunakan nama PERSIS seperti di StudentsImport
            $headers = [
                'A11' => 'Nama Lengkap*',
                'B11' => 'Email*',
                'C11' => 'Nomor Telepon',
                'D11' => 'Agama',
                'E11' => 'NISN (Opsional)',
                'F11' => 'NIS (Opsional)',
                'G11' => 'Tingkat Kelas*',
                'H11' => 'Nama Kelas*',
                'I11' => 'Jurusan',
                'J11' => 'Jenis Kelamin',
                'K11' => 'Password*'
            ];

            foreach ($headers as $cell => $header) {
                $sheet->setCellValue($cell, $header);
                $sheet->getStyle($cell)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => '3498DB']
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ]
                ]);
            }

            // CRITICAL: Format kolom sebagai TEXT untuk nomor panjang
            $sheet->getStyle('C12:C1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
            $sheet->getStyle('E12:E1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
            $sheet->getStyle('F12:F1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

            // Contoh data - SET SEBAGAI TEXT EXPLICITLY
            $examples = [
                ['Ahmad Fauzi', 'ahmad.fauzi@example.com', '081234567890', 'Islam', '0051234567', '12345', 'X', 'RPL 1', 'RPL', 'Laki-laki', 'Password123!'],
                ['Siti Nurhaliza', 'siti.nurhaliza@example.com', '082345678901', 'Islam', '0051234568', '12346', 'XI', 'TKJ 2', 'TKJ', 'Perempuan', 'Siti@2024'],
                ['Budi Santoso', 'budi.santoso@example.com', '083456789012', 'Kristen', '0051234569', '12347', 'XII', 'IPA 1', 'IPA', 'Laki-laki', 'Budi#123']
            ];

            $row = 12;
            foreach ($examples as $example) {
                $col = 'A';
                foreach ($example as $value) {
                    $cellCoordinate = $col . $row;

                    // Untuk kolom phone, NISN, dan NIS, set sebagai text explicitly
                    if (in_array($col, ['C', 'E', 'F'])) {
                        $sheet->setCellValueExplicit($cellCoordinate, $value, DataType::TYPE_STRING);
                    } else {
                        $sheet->setCellValue($cellCoordinate, $value);
                    }

                    // Style untuk contoh data
                    $sheet->getStyle($cellCoordinate)->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                        ]
                    ]);

                    $col++;
                }
                $row++;
            }

            // Set column width
            $sheet->getColumnDimension('A')->setWidth(20);
            $sheet->getColumnDimension('B')->setWidth(30);
            $sheet->getColumnDimension('C')->setWidth(18);
            $sheet->getColumnDimension('D')->setWidth(12);
            $sheet->getColumnDimension('E')->setWidth(18);
            $sheet->getColumnDimension('F')->setWidth(12);
            $sheet->getColumnDimension('G')->setWidth(15);
            $sheet->getColumnDimension('H')->setWidth(15);
            $sheet->getColumnDimension('I')->setWidth(15);
            $sheet->getColumnDimension('J')->setWidth(16);
            $sheet->getColumnDimension('K')->setWidth(15);

            // Save file
            $path = storage_path('app/templates/student_import_template.xlsx');

            // Pastikan direktori ada
            $directory = dirname($path);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($path);

            // Verifikasi file dibuat
            if (!file_exists($path)) {
                throw new \Exception('Gagal menyimpan template file.');
            }

            return $path;

        } catch (\Exception $e) {
            throw new \Exception('Gagal membuat template: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $gradeLevel = $request->input('grade_level');

        // Ambil sekolah berdasarkan user yang login
        $school = Auth::user()->school;

        if (!$school) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di sekolah manapun.');
        }

        $query = User::where('school_id', $school->id)
            ->where('role', 'siswa')
            ->with('student')
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($q) use ($search) {
                        $q->where('nisn', 'like', "%{$search}%")
                            ->orWhere('nis', 'like', "%{$search}%")
                            ->orWhere('class_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($gradeLevel) {
            $query->whereHas('student', function ($q) use ($gradeLevel) {
                $q->where('grade_level', $gradeLevel);
            });
        }

        $students = $query->paginate(10);

        return view('school-admin.user-management.students.index', compact('students', 'search', 'status', 'gradeLevel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $school = Auth::user()->school;

        if (!$school) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di sekolah manapun.');
        }

        // TAMBAHAN: Get all classes from this school
        $classes = \App\Models\G7KaihClass::where('school_id', $school->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('school-admin.user-management.students.create', compact('school', 'classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        DB::beginTransaction();

        try {
            $school = Auth::user()->school;

            if (!$school) {
                throw new \Exception('Anda tidak terdaftar di sekolah manapun.');
            }

            // Create user
            $user = User::create([
                'school_id' => $school->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'religion' => $request->religion,
                'role' => 'siswa',
                'password' => Hash::make($request->password),
                'is_active' => $request->has('is_active'),
            ]);

            // Create student
            Student::create([
                'user_id' => $user->id,
                'nisn' => $request->nisn,
                'nis' => $request->nis,
                'grade_level' => $request->grade_level,
                'class_name' => $request->class_name,
                'major' => $request->major,
                'g7_kaih_class_id' => $request->g7_kaih_class_id,
                'gender' => $request->gender,
            ]);

            DB::commit();

            try {
                app(\App\Services\Notification\NotificationService::class)->send('AccountCreated', $user, [
                    'user_name' => $user->name,
                    'creator_name' => auth()->user()->name,
                    'email' => $request->email,
                    'password' => $request->password,
                    'student_nis' => $request->nis ?? $request->nisn ?? '-',
                    'parent_login_code' => '-',
                ], $school->id);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal mengirim notifikasi AccountCreated (Siswa): ' . $e->getMessage());
            }

            return redirect()->route('school-admin.user-management.students.index')
                ->with('success', 'Data siswa berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        // Pastikan student ini milik sekolah yang sama dengan user yang login
        $school = Auth::user()->school;

        if ($student->user->school_id !== $school->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('school-admin.user-management.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        // Pastikan student ini milik sekolah yang sama dengan user yang login
        $school = Auth::user()->school;

        if ($student->user->school_id !== $school->id) {
            abort(403, 'Akses ditolak.');
        }

        // TAMBAHAN: Get all classes from this school
        $classes = \App\Models\G7KaihClass::where('school_id', $school->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('school-admin.user-management.students.edit', compact('student', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        DB::beginTransaction();

        try {
            // Pastikan student ini milik sekolah yang sama dengan user yang login
            $school = Auth::user()->school;

            if ($student->user->school_id !== $school->id) {
                throw new \Exception('Akses ditolak.');
            }

            // Update user
            $student->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'religion' => $request->religion,
                'is_active' => $request->has('is_active'),
            ]);

            // Update password jika diisi
            if ($request->filled('password')) {
                $student->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            // Update student
            $student->update([
                'nisn' => $request->nisn,
                'nis' => $request->nis,
                'grade_level' => $request->grade_level,
                'class_name' => $request->class_name,
                'major' => $request->major,
                'g7_kaih_class_id' => $request->g7_kaih_class_id,
                'gender' => $request->gender,
            ]);

            DB::commit();

            return redirect()->route('school-admin.user-management.students.index')
                ->with('success', 'Data siswa berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        DB::beginTransaction();

        try {
            // Pastikan student ini milik sekolah yang sama dengan user yang login
            $school = Auth::user()->school;

            if ($student->user->school_id !== $school->id) {
                throw new \Exception('Akses ditolak.');
            }

            // Hapus user (akan otomatis hapus student karena cascade)
            $student->user->delete();

            DB::commit();

            return redirect()->route('school-admin.user-management.students.index')
                ->with('success', 'Data siswa berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show import form
     */
    public function import()
    {
        return view('school-admin.user-management.students.import');
    }

    /**
     * Process Excel import
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120', // 5MB max
        ]);

        DB::beginTransaction();

        try {
            $school = Auth::user()->school;

            if (!$school) {
                throw new \Exception('Anda tidak terdaftar di sekolah manapun.');
            }

            $import = new StudentsImport($school->id);

            // Import dengan error handling
            Excel::import($import, $request->file('file'));

            DB::commit();

            // Prepare success message
            $message = "Import data siswa berhasil.";
            $message .= " " . $import->getSuccessCount() . " data berhasil diimport.";

            // Show errors if any
            $errors = $import->getErrors();
            if (!empty($errors)) {
                $errorMessage = " Beberapa data gagal diimport: <br>";
                foreach (array_slice($errors, 0, 10) as $error) {
                    $errorMessage .= "• {$error}<br>";
                }
                if (count($errors) > 10) {
                    $errorMessage .= "• ... dan " . (count($errors) - 10) . " error lainnya.<br>";
                }

                return redirect()->route('school-admin.user-management.students.index')
                    ->with('success', $message)
                    ->with('warning', $errorMessage);
            }

            return redirect()->route('school-admin.user-management.students.index')
                ->with('success', $message);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();

            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['import' => $errorMessages])
                ->with('error', 'Terjadi kesalahan validasi saat import.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Import error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status aktif/non-aktif
     */
    public function toggleStatus(Student $student)
    {
        $school = Auth::user()->school;

        if ($student->user->school_id !== $school->id) {
            abort(403, 'Akses ditolak.');
        }

        $student->user->update([
            'is_active' => !$student->user->is_active,
        ]);

        $status = $student->user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Status siswa berhasil {$status}.");
    }
}