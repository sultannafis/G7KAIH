<?php

namespace App\Http\Controllers\MasterAdmin\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Parents;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    /**
     * Display a listing of all parents from all schools
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $schoolId = $request->input('school_id');
        $relationship = $request->input('relationship');

        $query = User::where('role', 'orangtua')
            ->with(['parent.student.user', 'school'])
            ->latest();

        // Search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhereHas('parent', function($q) use ($search) {
                      $q->where('family_relationship', 'like', "%{$search}%")
                        ->orWhere('login_code', 'like', "%{$search}%")
                        ->orWhereHas('student', function($q) use ($search) {
                            $q->where('nisn', 'like', "%{$search}%")
                              ->orWhere('nis', 'like', "%{$search}%");
                        });
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

        // Relationship filter
        if ($relationship) {
            $query->whereHas('parent', function($q) use ($relationship) {
                $q->where('family_relationship', $relationship);
            });
        }

        $parents = $query->paginate(15);

        // Get all schools for filter dropdown
        $schools = School::where('status', 'active')->orderBy('name')->get();

        return view('masteradmin.user-management.parents.index', compact(
            'parents', 
            'schools',
            'search', 
            'status', 
            'schoolId',
            'relationship'
        ));
    }

    /**
     * Display the specified parent
     */
    public function show(Parents $parent)
    {
        $parent->load(['user.school', 'student.user']);
        
        return view('masteradmin.user-management.parents.show', compact('parent'));
    }
}