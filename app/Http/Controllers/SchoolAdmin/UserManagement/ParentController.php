<?php

namespace App\Http\Controllers\SchoolAdmin\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Parents;
use App\Models\User;
use App\Models\Student;
use App\Http\Requests\StoreParentRequest;
use App\Http\Requests\UpdateParentRequest;
use App\Imports\ParentsImport;
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

class ParentController extends Controller
{
    /**
     * Download template Excel
     */
    public function downloadTemplate()
    {
        try {
            $path = storage_path('app/templates/parent_import_template.xlsx');

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

            return response()->download($path, 'template_import_orangtua_' . date('Ymd_His') . '.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="template_import_orangtua_' . date('Ymd_His') . '.xlsx"',
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
            $sheet->setCellValue('A1', 'TEMPLATE IMPORT DATA ORANG TUA');
            $sheet->mergeCells('A1:I1');

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
                'A7' => '4. NISN/NIS Siswa harus sudah terdaftar di sistem',
                'A8' => '5. Hubungan Keluarga: Ayah, Ibu, Wali, Kakak, Nenek, Kakek, dll',
                'A9' => '6. Contoh data di baris 11-13 dapat dihapus',
                'A10' => '7. Kolom Kode Login opsional, akan di-generate otomatis jika dikosongkan',
                'A11' => '8. JANGAN MERUBAH NAMA KOLOM DI BARIS 12!'
            ];

            foreach ($instructions as $cell => $instruction) {
                $sheet->setCellValue($cell, $instruction);
            }

            // Header kolom - PERBAIKAN: Sesuaikan dengan snake_case
            $headers = [
                'A12' => 'Nama Lengkap*',      // Akan jadi: nama_lengkap
                'B12' => 'Email*',              // Akan jadi: email
                'C12' => 'Nomor Telepon',       // Akan jadi: nomor_telepon
                'D12' => 'Agama',               // Akan jadi: agama
                'E12' => 'NISN Siswa*',         // Akan jadi: nisn_siswa
                'F12' => 'NIS Siswa*',          // Akan jadi: nis_siswa
                'G12' => 'Hubungan Keluarga*',  // Akan jadi: hubungan_keluarga
                'H12' => 'Password*',           // Akan jadi: password
                'I12' => 'Kode Login'           // Akan jadi: kode_login (opsional, auto-generate)
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

            // Format kolom sebagai TEXT untuk nomor panjang
            $sheet->getStyle('C13:C1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
            $sheet->getStyle('E13:F1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
            $sheet->getStyle('I13:I1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

            // Contoh data
            $examples = [
                ['Bayu Saptaji', 'bayu.saptaji@example.com', '081234567890', 'Islam', '0051234567', '12345', 'Ayah', 'Password123!', ''],
                ['Siti Aminah', 'siti.aminah@example.com', '082345678901', 'Islam', '0051234568', '12346', 'Ibu', 'Siti@2024', ''],
                ['Ahmad Wijaya', 'ahmad.wijaya@example.com', '083456789012', 'Kristen', '0051234569', '12347', 'Wali', 'Ahmad#123', '']
            ];

            $row = 13;
            foreach ($examples as $example) {
                $col = 'A';
                foreach ($example as $value) {
                    $cellCoordinate = $col . $row;

                    // Untuk kolom phone, NISN, dan NIS, set sebagai text explicitly
                    if (in_array($col, ['C', 'E', 'F', 'I'])) {
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
            $sheet->getColumnDimension('G')->setWidth(20);
            $sheet->getColumnDimension('H')->setWidth(15);
            $sheet->getColumnDimension('I')->setWidth(15);

            // Save file
            $path = storage_path('app/templates/parent_import_template.xlsx');

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
        $relationship = $request->input('relationship');

        // Ambil sekolah berdasarkan user yang login
        $school = Auth::user()->school;

        if (!$school) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di sekolah manapun.');
        }

        $query = User::where('school_id', $school->id)
            ->where('role', 'orangtua')
            ->with(['parent.student.user'])
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhereHas('parent', function ($q) use ($search) {
                        $q->where('family_relationship', 'like', "%{$search}%")
                            ->orWhere('login_code', 'like', "%{$search}%")
                            ->orWhereHas('student', function ($q) use ($search) {
                                $q->where('nisn', 'like', "%{$search}%")
                                    ->orWhere('nis', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($relationship) {
            $query->whereHas('parent', function ($q) use ($relationship) {
                $q->where('family_relationship', $relationship);
            });
        }

        $parents = $query->paginate(10);

        return view('school-admin.user-management.parents.index', compact('parents', 'search', 'status', 'relationship'));
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

        // Get all students from this school
        $students = User::where('school_id', $school->id)
            ->where('role', 'siswa')
            ->with('student')
            ->get();

        return view('school-admin.user-management.parents.create', compact('school', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParentRequest $request)
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
                'role' => 'orangtua',
                'password' => Hash::make($request->password),
                'is_active' => $request->has('is_active'),
            ]);

            // Create parent
            Parents::create([
                'user_id' => $user->id,
                'student_id' => $request->student_id,
                'family_relationship' => $request->family_relationship,
            ]);

            DB::commit();

            try {
                // reload parent relationship to get auto-generated login code if any
                $user->load('parent');
                app(\App\Services\Notification\NotificationService::class)->send('AccountCreated', $user, [
                    'user_name' => $user->name,
                    'creator_name' => auth()->user()->name,
                    'email' => $request->email,
                    'password' => $request->password,
                    'student_nis' => '-', // not strictly applicable for parent login creds in text
                    'parent_login_code' => $user->parent->login_code ?? '-',
                ], $school->id);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal mengirim notifikasi AccountCreated (Orang Tua): ' . $e->getMessage());
            }

            return redirect()->route('school-admin.user-management.parents.index')
                ->with('success', 'Data orang tua berhasil ditambahkan.');

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
    public function show(Parents $parent)
    {
        // Pastikan parent ini milik sekolah yang sama dengan user yang login
        $school = Auth::user()->school;

        if ($parent->user->school_id !== $school->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('school-admin.user-management.parents.show', compact('parent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parents $parent)
    {
        // Pastikan parent ini milik sekolah yang sama dengan user yang login
        $school = Auth::user()->school;

        if ($parent->user->school_id !== $school->id) {
            abort(403, 'Akses ditolak.');
        }

        // Get all students from this school
        $students = User::where('school_id', $school->id)
            ->where('role', 'siswa')
            ->with('student')
            ->get();

        return view('school-admin.user-management.parents.edit', compact('parent', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParentRequest $request, Parents $parent)
    {
        DB::beginTransaction();

        try {
            // Pastikan parent ini milik sekolah yang sama dengan user yang login
            $school = Auth::user()->school;

            if ($parent->user->school_id !== $school->id) {
                throw new \Exception('Akses ditolak.');
            }

            // Update user
            $parent->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'religion' => $request->religion,
                'is_active' => $request->has('is_active'),
            ]);

            // Update password jika diisi
            if ($request->filled('password')) {
                $parent->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            // Update parent
            $parent->update([
                'student_id' => $request->student_id,
                'family_relationship' => $request->family_relationship,
            ]);

            DB::commit();

            return redirect()->route('school-admin.user-management.parents.index')
                ->with('success', 'Data orang tua berhasil diperbarui.');

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
    public function destroy(Parents $parent)
    {
        DB::beginTransaction();

        try {
            // Pastikan parent ini milik sekolah yang sama dengan user yang login
            $school = Auth::user()->school;

            if ($parent->user->school_id !== $school->id) {
                throw new \Exception('Akses ditolak.');
            }

            // Hapus user (akan otomatis hapus parent karena cascade)
            $parent->user->delete();

            DB::commit();

            return redirect()->route('school-admin.user-management.parents.index')
                ->with('success', 'Data orang tua berhasil dihapus.');

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
        return view('school-admin.user-management.parents.import');
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

            $import = new ParentsImport($school->id);

            // Import dengan error handling
            Excel::import($import, $request->file('file'));

            DB::commit();

            // Prepare success message
            $message = "Import data orang tua berhasil.";
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

                return redirect()->route('school-admin.user-management.parents.index')
                    ->with('success', $message)
                    ->with('warning', $errorMessage);
            }

            return redirect()->route('school-admin.user-management.parents.index')
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
    public function toggleStatus(Parents $parent)
    {
        $school = Auth::user()->school;

        if ($parent->user->school_id !== $school->id) {
            abort(403, 'Akses ditolak.');
        }

        $parent->user->update([
            'is_active' => !$parent->user->is_active,
        ]);

        $status = $parent->user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Status orang tua berhasil {$status}.");
    }
}