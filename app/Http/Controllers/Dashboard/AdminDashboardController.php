<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\HabitSubmission;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->school_id;

        return view('dashboard.admin', [
            'totalTeachers' => User::where('school_id', $schoolId)->where('role', 'guru')->count(),
            'totalStudents' => User::where('school_id', $schoolId)->where('role', 'siswa')->count(),
            'totalParents'  => User::where('school_id', $schoolId)->where('role', 'orangtua')->count(),
            'todaySubmissions' => HabitSubmission::whereDate('created_at', now())->count(),
        ]);
    }
}
