<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\NotificationLog;

class MasterAdminDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.masteradmin', [
            'totalSchools'   => School::count(),
            'pendingSchools' => School::where('status', 'pending')->count(),
            'activeSchools'  => School::where('status', 'active')->count(),
            'rejectedSchools' => School::where('status', 'rejected')->count(),
            'totalUsers'     => User::count(),
            'recentActivity' => NotificationLog::where('event', 'LIKE', 'School%')
                ->with('user')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}