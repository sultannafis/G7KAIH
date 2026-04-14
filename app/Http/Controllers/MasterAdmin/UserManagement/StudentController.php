<?php

namespace App\Http\Controllers\MasterAdmin\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of all students from all schools
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $schoolId = $request->input('school_id');
        $gradeLevel = $request->input('grade_level');

        $query = User::where('role', 'siswa')
            ->with(['student', 'school'])
            ->latest();

        // Search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhereHas('student', function($q) use ($search) {
                      $q->where('nisn', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%")
                        ->orWhere('class_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('school', function($q) use ($search) {
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

        // School filter
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        // Grade level filter
        if ($gradeLevel) {
            $query->whereHas('student', function($q) use ($gradeLevel) {
                $q->where('grade_level', $gradeLevel);
            });
        }

        $students = $query->paginate(15);

        // Get all schools for filter dropdown
        $schools = School::where('status', 'active')->orderBy('name')->get();

        return view('masteradmin.user-management.students.index', compact(
            'students', 
            'schools',
            'search', 
            'status', 
            'schoolId',
            'gradeLevel'
        ));
    }

    /**
     * Display the specified student
     */
    public function show(Student $student)
    {
        $student->load(['user.school', 'parents.user']);
        
        return view('masteradmin.user-management.students.show', compact('student'));
    }
}