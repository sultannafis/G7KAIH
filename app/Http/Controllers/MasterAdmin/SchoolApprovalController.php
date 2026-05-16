<?php

namespace App\Http\Controllers\MasterAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Notification\NotificationService;

class SchoolApprovalController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = School::where('status', 'pending')
            ->with(['users' => function ($q) {
                $q->where('role', 'admin');
            }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('users', function ($u) use ($search) {
                      $u->where('role', 'admin')->where('name', 'like', "%{$search}%");
                  });
            });
        }

        return view('masteradmin.schools.index', [
            'pendingSchools' => $query->latest()->paginate($perPage)->appends($request->query()),
        ]);
    }

    public function show(School $school)
    {
        return view('masteradmin.schools.show', [
            'school' => $school->load('users'),
            'admin'  => User::where('school_id', $school->id)
                            ->where('role', 'admin')
                            ->first(),
        ]);
    }

    public function approve(School $school)
    {
        DB::transaction(function () use ($school) {
            $school->update([
                'status' => 'active',
            ]);

            User::where('school_id', $school->id)
                ->update(['is_active' => true]);

            $admin = User::where('school_id', $school->id)
                        ->where('role', 'admin')
                        ->first();

            if ($admin) {
                $this->notificationService->send(
                    'SchoolApproved',
                    $admin,
                    [
                        'school_name' => $school->name,
                        'approval_date' => now()->translatedFormat('d F Y'),
                        'admin_name' => $admin->name,
                    ],
                    $school->id
                );
            }
        });

        return redirect()
            ->route('masteradmin.schools.approval.index')
            ->with('success', 'Sekolah berhasil disetujui dan notifikasi telah dikirim.');
    }

    public function reject(Request $request, School $school)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($school, $request) {
            $school->update([
                'status' => 'rejected',
                'rejection_reason' => $request->reason,
            ]);

            User::where('school_id', $school->id)
                ->update(['is_active' => false]);

            $admin = User::where('school_id', $school->id)
                        ->where('role', 'admin')
                        ->first();

            if ($admin) {
                $this->notificationService->send(
                    'SchoolRejected',
                    $admin,
                    [
                        'school_name' => $school->name,
                        'rejection_date' => now()->translatedFormat('d F Y'),
                        'rejection_reason' => $request->reason,
                        'admin_name' => $admin->name,
                    ],
                    $school->id
                );
            }
        });

        return redirect()
            ->route('masteradmin.schools.approval.index')
            ->with('error', 'Sekolah ditolak dan notifikasi telah dikirim.');
    }

    public function toggleStatus(School $school)
    {
        $newStatus = $school->status === 'active' ? 'in_active' : 'active';

        $school->update(['status' => $newStatus]);

        $label = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Sekolah berhasil {$label}.");
    }
}