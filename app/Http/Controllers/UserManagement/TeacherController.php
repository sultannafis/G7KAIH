<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\School;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Imports\TeachersImport;
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

class TeacherController extends Controller
{
    /**
     * Helper: check if current user is masteradmin (read-only)
     */
    private function isMasterAdmin(): bool
    {
        return Auth::user()->role === 'masteradmin';
    }

    /**
     * Helper: abort if masteradmin tries to mutate
     */
    private function abortIfReadOnly(): void
    {
        if ($this->isMasterAdmin()) {
            abort(403, 'Master Admin hanya memiliki akses baca.');
        }
    }

    /**
     * Display a listing of teachers.
     * Admin: scoped to their school. MasterAdmin: all schools.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100])
            ? (int) $request->input('per_page')
            : 10;

        if ($this->isMasterAdmin()) {
            // MasterAdmin: see all schools
            $schoolId = $request->input('school_id');

            $query = User::where('role', 'guru')
                ->with(['teacher', 'school'])
                ->latest();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%")
                      ->orWhereHas('teacher', function ($q) use ($search) {
                          $q->where('nip', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%");
                      })
                      ->orWhereHas('school', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            }

            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }

            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }

            $teachers = $query->paginate($perPage)->withQueryString();
            $schools = School::where('status', 'active')->orderBy('name')->get();

            return view('shared.user-management.teachers.index', compact(
                'teachers', 'schools', 'search', 'status', 'perPage'
            ));
        } else {
            // Admin: scoped to their school
            $school = Auth::user()->school;

            if (!$school) {
                return redirect()->back()->with('error', 'Anda tidak terdaftar di sekolah manapun.');
            }

            $query = User::where('school_id', $school->id)
                ->where('role', 'guru')
                ->with('teacher')
                ->latest();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%")
                        ->orWhereHas('teacher', function ($q) use ($search) {
                            $q->where('nip', 'like', "%{$search}%")
                                ->orWhere('nik', 'like', "%{$search}%");
                        });
                });
            }

            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }

            $teachers = $query->paginate($perPage)->withQueryString();
            $schools = collect(); // empty for admin

            return view('shared.user-management.teachers.index', compact(
                'teachers', 'schools', 'search', 'status', 'perPage'
            ));
        }
    }

    /**
     * Display the specified teacher.
     */
    public function show(Teacher $teacher)
    {
        if ($this->isMasterAdmin()) {
            $teacher->load(['user.school']);
        } else {
            $school = Auth::user()->school;
            if ($teacher->user->school_id !== $school->id) {
                abort(403, 'Akses ditolak.');
            }
        }

        return view('shared.user-management.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->abortIfReadOnly();

        $school = Auth::user()->school;
        if (!$school) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di sekolah manapun.');
        }

        return view('shared.user-management.teachers.create', compact('school'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        $this->abortIfReadOnly();

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
                'role' => 'guru',
                'password' => Hash::make($request->password),
                'is_active' => $request->has('is_active'),
            ]);

            // Create teacher
            Teacher::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'nik' => $request->nik,
            ]);

            DB::commit();

            try {
                app(\App\Services\Notification\NotificationService::class)->send('AccountCreated', $user, [
                    'user_name' => $user->name,
                    'creator_name' => auth()->user()->name,
                    'email' => $request->email,
                    'password' => $request->password,
                    'student_nis' => '-',
                    'parent_login_code' => '-',
                ], $school->id);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim notifikasi AccountCreated (Guru): ' . $e->getMessage());
            }

            return redirect()->route('user-management.teachers.index')
                ->with('success', 'Data guru berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        $this->abortIfReadOnly();

        $school = Auth::user()->school;
        if ($teacher->user->school_id !== $school->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('shared.user-management.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $this->abortIfReadOnly();

        DB::beginTransaction();

        try {
            $school = Auth::user()->school;

            if ($teacher->user->school_id !== $school->id) {
                throw new \Exception('Akses ditolak.');
            }

            // Update user
            $teacher->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'religion' => $request->religion,
                'is_active' => $request->has('is_active'),
            ]);

            // Update password jika diisi
            if ($request->filled('password')) {
                $teacher->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            // Update teacher
            $teacher->update([
                'nip' => $request->nip,
                'nik' => $request->nik,
            ]);

            DB::commit();

            return redirect()->route('user-management.teachers.index')
                ->with('success', 'Data guru berhasil diperbarui.');

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
    public function destroy(Teacher $teacher)
    {
        $this->abortIfReadOnly();

        DB::beginTransaction();

        try {
            $school = Auth::user()->school;

            if ($teacher->user->school_id !== $school->id) {
                throw new \Exception('Akses ditolak.');
            }

            $teacher->user->delete();

            DB::commit();

            return redirect()->route('user-management.teachers.index')
                ->with('success', 'Data guru berhasil dihapus.');

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
        $this->abortIfReadOnly();
        return view('shared.user-management.teachers.import');
    }

    /**
     * Process Excel import
     */
    public function processImport(Request $request)
    {
        $this->abortIfReadOnly();

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        DB::beginTransaction();

        try {
            $school = Auth::user()->school;

            if (!$school) {
                throw new \Exception('Anda tidak terdaftar di sekolah manapun.');
            }

            $import = new TeachersImport($school->id);

            Excel::import($import, $request->file('file'));

            DB::commit();

            $message = "Import data guru berhasil.";
            $message .= " " . $import->getSuccessCount() . " data berhasil diimport.";

            $errors = $import->getErrors();
            if (!empty($errors)) {
                $errorMessage = " Beberapa data gagal diimport: <br>";
                foreach (array_slice($errors, 0, 10) as $error) {
                    $errorMessage .= "• {$error}<br>";
                }
                if (count($errors) > 10) {
                    $errorMessage .= "• ... dan " . (count($errors) - 10) . " error lainnya.<br>";
                }

                return redirect()->route('user-management.teachers.index')
                    ->with('success', $message)
                    ->with('warning', $errorMessage);
            }

            return redirect()->route('user-management.teachers.index')
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
    public function toggleStatus(Teacher $teacher)
    {
        $this->abortIfReadOnly();

        $school = Auth::user()->school;

        if ($teacher->user->school_id !== $school->id) {
            abort(403, 'Akses ditolak.');
        }

        $teacher->user->update([
            'is_active' => !$teacher->user->is_active,
        ]);

        $status = $teacher->user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Status guru berhasil {$status}.");
    }

    /**
     * Download template Excel
     */
    public function downloadTemplate()
    {
        $this->abortIfReadOnly();

        try {
            $path = storage_path('app/templates/teacher_import_template.xlsx');

            Log::info('Download template diminta', [
                'path' => $path,
                'exists' => file_exists($path),
                'user' => auth()->id()
            ]);

            if (!file_exists($path)) {
                $directory = storage_path('app/templates');
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
                $this->generateTemplate();
            }

            if (!file_exists($path)) {
                throw new \Exception('Template file tidak dapat dibuat.');
            }

            if (!is_readable($path)) {
                throw new \Exception('Template file tidak dapat dibaca. Periksa permission file.');
            }

            Log::info('Download template berhasil', [
                'file_size' => filesize($path),
                'mime_type' => mime_content_type($path)
            ]);

            return response()->download($path, 'template_import_guru_' . date('Ymd_His') . '.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="template_import_guru_' . date('Ymd_His') . '.xlsx"',
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

            $sheet->setCellValue('A1', 'TEMPLATE IMPORT DATA GURU');
            $sheet->mergeCells('A1:G1');

            $sheet->getStyle('A1')->applyFromArray([
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '2E86C1']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
            ]);
            $sheet->getRowDimension('1')->setRowHeight(25);

            $sheet->setCellValue('A3', 'INSTRUKSI:');
            $sheet->getStyle('A3')->getFont()->setBold(true);

            $instructions = [
                'A4' => '1. Kolom bertanda * (bintang) wajib diisi',
                'A5' => '2. Email harus unik dan belum terdaftar',
                'A6' => '3. Password minimal 8 karakter',
                'A7' => '4. NIP dan NIK harus unik jika diisi',
                'A8' => '5. Contoh data di baris 11-13 dapat dihapus',
                'A9' => '6. JANGAN MERUBAH NAMA KOLOM DI BARIS 10!'
            ];

            foreach ($instructions as $cell => $instruction) {
                $sheet->setCellValue($cell, $instruction);
            }

            $headers = [
                'A10' => 'Nama Lengkap*', 'B10' => 'Email*', 'C10' => 'Nomor Telepon*',
                'D10' => 'Agama', 'E10' => 'NIP (Opsional)', 'F10' => 'NIK (Opsional)', 'G10' => 'Password*'
            ];

            foreach ($headers as $cell => $header) {
                $sheet->setCellValue($cell, $header);
                $sheet->getStyle($cell)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '3498DB']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ]);
            }

            $sheet->getStyle('C11:C1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
            $sheet->getStyle('E11:E1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
            $sheet->getStyle('F11:F1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

            $examples = [
                ['Sultan Nafis', 'sultannafis24@gmail.com', '081234567890', 'Islam', '198012312345678', '3275010101800001', 'Sultan_1324'],
                ['Putri Sahliyah', 'putrisahliyah@gmail.com', '082345678901', 'Islam', '198112312346789', '3275010202810002', 'Putri@123'],
                ['Agus Wijaya', 'agus.wijaya@gmail.com', '083456789012', 'Kristen', '', '3275010303820003', 'Password123']
            ];

            $row = 11;
            foreach ($examples as $example) {
                $col = 'A';
                foreach ($example as $value) {
                    $cellCoordinate = $col . $row;
                    if (in_array($col, ['C', 'E', 'F'])) {
                        $sheet->setCellValueExplicit($cellCoordinate, $value, DataType::TYPE_STRING);
                    } else {
                        $sheet->setCellValue($cellCoordinate, $value);
                    }
                    $sheet->getStyle($cellCoordinate)->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);
                    $col++;
                }
                $row++;
            }

            $sheet->getColumnDimension('A')->setWidth(20);
            $sheet->getColumnDimension('B')->setWidth(30);
            $sheet->getColumnDimension('C')->setWidth(18);
            $sheet->getColumnDimension('D')->setWidth(12);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(15);

            $path = storage_path('app/templates/teacher_import_template.xlsx');
            $directory = dirname($path);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($path);

            if (!file_exists($path)) {
                throw new \Exception('Gagal menyimpan template file.');
            }

            return $path;

        } catch (\Exception $e) {
            throw new \Exception('Gagal membuat template: ' . $e->getMessage());
        }
    }
}
