<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NotificationTemplate;

class NotificationTemplatePolicy
{
    public function edit(User $user, NotificationTemplate $template)
    {
        if ($template->editable_by === 'masteradmin' && $user->role !== 'masteradmin') {
            return false;
        }

        if ($template->editable_by === 'admin_school' && !in_array($user->role, ['admin', 'masteradmin'])) {
            return false;
        }

        return true;
    }

    public function delete(User $user, NotificationTemplate $template)
    {
        return $user->role === 'masteradmin';
    }
}