<?php

namespace App\Services\School;

use App\Models\School;
use App\Services\Notification\NotificationService;

class SchoolApprovalService
{
    public function approve(School $school): void
    {
        $school->update(['status' => 'active']);

        app(NotificationService::class)->send(
            'SchoolApproved',
            $school->admin,
            ['school' => $school->name],
            $school->id
        );
    }
}
