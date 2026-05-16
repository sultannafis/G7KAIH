<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\G7KaihClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class G7KaihClassController extends Controller
{
    /**
     * Display a listing of the classes
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $academicYear = $request->input('academic_year');

        $school = Auth::user()->school;

        if (!$school) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di sekolah manapun.');
        }

        $query = G7KaihClass::where('school_id', $school->id)
            ->with(['teacher', 'students'])
            ->latest();

        // Search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('academic_year', 'like', "%{$search}%")
                  ->orWhereHas('teacher', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        // Academic year filter
        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }

        $classes = $query->paginate(10);

        // Get unique academic years for filter
        $academicYears = G7KaihClass::where('school_id', $school->id)
            ->distinct()
            ->pluck('academic_year');

        return view('school-admin.classes.index', compact(
            'classes',
            'academicYears',
            'search',
            'status',
            'academicYear'
        ));
    }

    /**
     * Show the form for creating a new class
     */
    public function create()
    {
        $school = Auth::user()->school;

        if (!$school) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di sekolah manapun.');
        }

        // Get all teachers from this school
        $teachers = User::where('school_id', $school->id)
            ->where('role', 'guru')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('school-admin.classes.create', compact('school', 'teachers'));
    }

    /**
     * Store a newly created class
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'academic_year' => 'required|string|max:20',
            'teacher_id' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();

        try {
            $school = Auth::user()->school;

            if (!$school) {
                throw new \Exception('Anda tidak terdaftar di sekolah manapun.');
            }

            // Verify teacher belongs to the same school
            $teacher = User::where('id', $request->teacher_id)
                ->where('school_id', $school->id)
                ->where('role', 'guru')
                ->first();

            if (!$teacher) {
                throw new \Exception('Guru yang dipilih tidak valid.');
            }

            G7KaihClass::create([
                'school_id' => $school->id,
                'name' => $request->name,
                'description' => $request->description,
                'academic_year' => $request->academic_year,
                'teacher_id' => $request->teacher_id,
                'is_active' => $request->has('is_active'),
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('school-admin.classes.index')
                ->with('success', 'Kelas berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error creating class:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified class
     */
    public function show(G7KaihClass $class)
    {
        $school = Auth::user()->school;

        if ($class->school_id !== $school->id) {
            abort(403, 'Akses ditolak.');
        }

        $class->load(['teacher']);
        
        $search = request('search');
        $students = $class->students()->with('user');
        
        if ($search) {
            $students->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('nisn', 'like', "%{$search}%")
              ->orWhere('nis', 'like', "%{$search}%");
        }
        
        $students = $students->paginate(request('per_page', 10));

        return view('school-admin.classes.show', compact('class', 'students', 'search'));
    }

    /**
     * Show the form for editing the specified class
     */
    public function edit(G7KaihClass $class)
    {
        $school = Auth::user()->school;

        if ($class->school_id !== $school->id) {
            abort(403, 'Akses ditolak.');
        }

        // Get all teachers from this school
        $teachers = User::where('school_id', $school->id)
            ->where('role', 'guru')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('school-admin.classes.edit', compact('class', 'teachers'));
    }

    /**
     * Update the specified class
     */
    public function update(Request $request, G7KaihClass $class)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'academic_year' => 'required|string|max:20',
            'teacher_id' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();

        try {
            $school = Auth::user()->school;

            if ($class->school_id !== $school->id) {
                throw new \Exception('Akses ditolak.');
            }

            // Verify teacher belongs to the same school
            $teacher = User::where('id', $request->teacher_id)
                ->where('school_id', $school->id)
                ->where('role', 'guru')
                ->first();

            if (!$teacher) {
                throw new \Exception('Guru yang dipilih tidak valid.');
            }

            $class->update([
                'name' => $request->name,
                'description' => $request->description,
                'academic_year' => $request->academic_year,
                'teacher_id' => $request->teacher_id,
                'is_active' => $request->has('is_active'),
            ]);

            DB::commit();

            return redirect()->route('school-admin.classes.index')
                ->with('success', 'Kelas berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified class
     */
    public function destroy(G7KaihClass $class)
    {
        DB::beginTransaction();

        try {
            $school = Auth::user()->school;

            if ($class->school_id !== $school->id) {
                throw new \Exception('Akses ditolak.');
            }

            // Check if class has students
            if ($class->students()->count() > 0) {
                throw new \Exception('Kelas tidak dapat dihapus karena masih memiliki siswa.');
            }

            $class->delete();

            DB::commit();

            return redirect()->route('school-admin.classes.index')
                ->with('success', 'Kelas berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle class status (active/inactive)
     */
    public function toggleStatus(G7KaihClass $class)
    {
        $school = Auth::user()->school;

        if ($class->school_id !== $school->id) {
            abort(403, 'Akses ditolak.');
        }

        $class->update([
            'is_active' => !$class->is_active,
        ]);

        $status = $class->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Kelas berhasil {$status}.");
    }
}