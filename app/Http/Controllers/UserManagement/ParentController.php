<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Parents;
use App\Models\User;
use App\Models\Student;
use App\Models\School;
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
    private function isMasterAdmin(): bool
    {
        return Auth::user()->role === 'masteradmin';
    }

    private function abortIfReadOnly(): void
    {
        if ($this->isMasterAdmin()) {
            abort(403, 'Master Admin hanya memiliki akses baca.');
        }
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $relationship = $request->input('relationship');
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100])
            ? (int) $request->input('per_page')
            : 10;

        if ($this->isMasterAdmin()) {
            $schoolId = $request->input('school_id');

            $query = User::where('role', 'orangtua')
                ->with(['parent.student.user', 'school'])
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

            if ($relationship) {
                $query->whereHas('parent', function ($q) use ($relationship) {
                    $q->where('family_relationship', $relationship);
                });
            }

            $parents = $query->paginate($perPage)->withQueryString();
            $schools = School::where('status', 'active')->orderBy('name')->get();

            return view('shared.user-management.parents.index', compact(
                'parents', 'schools', 'search', 'status', 'relationship', 'perPage'
            ));
        } else {
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

            $parents = $query->paginate($perPage)->withQueryString();
            $schools = collect();

            return view('shared.user-management.parents.index', compact(
                'parents', 'schools', 'search', 'status', 'relationship', 'perPage'
            ));
        }
    }

    public function show(Parents $parent)
    {
        if ($this->isMasterAdmin()) {
            $parent->load(['user.school', 'student.user']);
        } else {
            $school = Auth::user()->school;
            if ($parent->user->school_id !== $school->id) {
                abort(403, 'Akses ditolak.');
            }
        }

        return view('shared.user-management.parents.show', compact('parent'));
    }

    public function create()
    {
        $this->abortIfReadOnly();

        $school = Auth::user()->school;
        if (!$school) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di sekolah manapun.');
        }

        $students = User::where('school_id', $school->id)
            ->where('role', 'siswa')
            ->with('student')
            ->get();

        return view('shared.user-management.parents.create', compact('school', 'students'));
    }

    public function store(StoreParentRequest $request)
    {
        $this->abortIfReadOnly();

        DB::beginTransaction();

        try {
            $school = Auth::user()->school;

            if (!$school) {
                throw new \Exception('Anda tidak terdaftar di sekolah manapun.');
            }

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

            Parents::create([
                'user_id' => $user->id,
                'student_id' => $request->student_id,
                'family_relationship' => $request->family_relationship,
            ]);

            DB::commit();

            try {
                $user->load('parent');
                app(\App\Services\Notification\NotificationService::class)->send('AccountCreated', $user, [
                    'user_name' => $user->name,
                    'creator_name' => auth()->user()->name,
                    'email' => $request->email,
                    'password' => $request->password,
                    'student_nis' => '-',
                    'parent_login_code' => $user->parent->login_code ?? '-',
                ], $school->id);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim notifikasi AccountCreated (Orang Tua): ' . $e->getMessage());
            }

            return redirect()->route('user-management.parents.index')
                ->with('success', 'Data orang tua berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Parents $parent)
    {
        $this->abortIfReadOnly();

        $school = Auth::user()->school;
        if ($parent->user->school_id !== $school->id) {
            abort(403, 'Akses ditolak.');
        }

        $students = User::where('school_id', $school->id)
            ->where('role', 'siswa')
            ->with('student')
            ->get();

        return view('shared.user-management.parents.edit', compact('parent', 'students'));
    }

    public function update(UpdateParentRequest $request, Parents $parent)
    {
        $this->abortIfReadOnly();

        DB::beginTransaction();

        try {
            $school = Auth::user()->school;

            if ($parent->user->school_id !== $school->id) {
                throw new \Exception('Akses ditolak.');
            }

            $parent->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'religion' => $request->religion,
                'is_active' => $request->has('is_active'),
            ]);

            if ($request->filled('password')) {
                $parent->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            $parent->update([
                'student_id' => $request->student_id,
                'family_relationship' => $request->family_relationship,
            ]);

            DB::commit();

            return redirect()->route('user-management.parents.index')
                ->with('success', 'Data orang tua berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Parents $parent)
    {
        $this->abortIfReadOnly();

        DB::beginTransaction();

        try {
            $school = Auth::user()->school;

            if ($parent->user->school_id !== $school->id) {
                throw new \Exception('Akses ditolak.');
            }

            $parent->user->delete();

            DB::commit();

            return redirect()->route('user-management.parents.index')
                ->with('success', 'Data orang tua berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function import()
    {
        $this->abortIfReadOnly();
        return view('shared.user-management.parents.import');
    }

    public function processImport(Request $request)
    {
        $this->abortIfReadOnly();

        $request->validate(['file' => 'required|mimes:xlsx,xls,csv|max:5120']);

        DB::beginTransaction();

        try {
            $school = Auth::user()->school;
            if (!$school) { throw new \Exception('Anda tidak terdaftar di sekolah manapun.'); }

            $import = new ParentsImport($school->id);
            Excel::import($import, $request->file('file'));

            DB::commit();

            $message = "Import data orang tua berhasil.";
            $message .= " " . $import->getSuccessCount() . " data berhasil diimport.";

            $errors = $import->getErrors();
            if (!empty($errors)) {
                $errorMessage = " Beberapa data gagal diimport: <br>";
                foreach (array_slice($errors, 0, 10) as $error) { $errorMessage .= "• {$error}<br>"; }
                if (count($errors) > 10) { $errorMessage .= "• ... dan " . (count($errors) - 10) . " error lainnya.<br>"; }
                return redirect()->route('user-management.parents.index')
                    ->with('success', $message)->with('warning', $errorMessage);
            }

            return redirect()->route('user-management.parents.index')->with('success', $message);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) { $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors()); }
            return redirect()->back()->withInput()->withErrors(['import' => $errorMessages])->with('error', 'Terjadi kesalahan validasi saat import.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Parents $parent)
    {
        $this->abortIfReadOnly();

        $school = Auth::user()->school;
        if ($parent->user->school_id !== $school->id) { abort(403, 'Akses ditolak.'); }

        $parent->user->update(['is_active' => !$parent->user->is_active]);
        $status = $parent->user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Status orang tua berhasil {$status}.");
    }

    public function downloadTemplate()
    {
        $this->abortIfReadOnly();

        try {
            $path = storage_path('app/templates/parent_import_template.xlsx');
            if (!file_exists($path)) {
                $directory = storage_path('app/templates');
                if (!is_dir($directory)) { mkdir($directory, 0755, true); }
                $this->generateTemplate();
            }
            if (!file_exists($path)) { throw new \Exception('Template file tidak dapat dibuat.'); }
            if (!is_readable($path)) { throw new \Exception('Template file tidak dapat dibaca.'); }

            return response()->download($path, 'template_import_orangtua_' . date('Ymd_His') . '.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(false);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mendownload template: ' . $e->getMessage());
        }
    }

    private function generateTemplate()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'TEMPLATE IMPORT DATA ORANG TUA');
            $sheet->mergeCells('A1:I1');
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
                'A7' => '4. NISN/NIS Siswa harus sudah terdaftar di sistem',
                'A8' => '5. Hubungan Keluarga: Ayah, Ibu, Wali, Kakak, Nenek, Kakek, dll',
                'A9' => '6. Contoh data di baris 11-13 dapat dihapus',
                'A10' => '7. Kolom Kode Login opsional, akan di-generate otomatis jika dikosongkan',
                'A11' => '8. JANGAN MERUBAH NAMA KOLOM DI BARIS 12!'
            ];
            foreach ($instructions as $cell => $instruction) { $sheet->setCellValue($cell, $instruction); }

            $headers = [
                'A12' => 'Nama Lengkap*', 'B12' => 'Email*', 'C12' => 'Nomor Telepon',
                'D12' => 'Agama', 'E12' => 'NISN Siswa*', 'F12' => 'NIS Siswa*',
                'G12' => 'Hubungan Keluarga*', 'H12' => 'Password*', 'I12' => 'Kode Login'
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

            $sheet->getStyle('C13:C1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
            $sheet->getStyle('E13:F1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
            $sheet->getStyle('I13:I1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

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
                    if (in_array($col, ['C', 'E', 'F', 'I'])) {
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

            $widths = ['A' => 20, 'B' => 30, 'C' => 18, 'D' => 12, 'E' => 18, 'F' => 12, 'G' => 20, 'H' => 15, 'I' => 15];
            foreach ($widths as $c => $w) { $sheet->getColumnDimension($c)->setWidth($w); }

            $path = storage_path('app/templates/parent_import_template.xlsx');
            $directory = dirname($path);
            if (!is_dir($directory)) { mkdir($directory, 0755, true); }
            $writer = new Xlsx($spreadsheet);
            $writer->save($path);

            if (!file_exists($path)) { throw new \Exception('Gagal menyimpan template file.'); }
            return $path;
        } catch (\Exception $e) {
            throw new \Exception('Gagal membuat template: ' . $e->getMessage());
        }
    }
}
