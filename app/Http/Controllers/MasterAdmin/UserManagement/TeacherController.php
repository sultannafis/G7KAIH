<?php

namespace App\Http\Controllers\MasterAdmin\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of all teachers from all schools
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $schoolId = $request->input('school_id');

        $query = User::where('role', 'guru')
            ->with(['teacher', 'school'])
            ->latest();

        // Search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhereHas('teacher', function($q) use ($search) {
                      $q->where('nip', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
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

        $teachers = $query->paginate(15);

        // Get all schools for filter dropdown
        $schools = School::where('status', 'active')->orderBy('name')->get();

        return view('masteradmin.user-management.teachers.index', compact(
            'teachers', 
            'schools',
            'search', 
            'status', 
            'schoolId'
        ));
    }

    /**
     * Display the specified teacher
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['user.school']);
        
        return view('masteradmin.user-management.teachers.show', compact('teacher'));
    }
}