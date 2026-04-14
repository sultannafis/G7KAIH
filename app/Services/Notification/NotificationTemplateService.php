<?php

namespace App\Services\Notification;

use App\Models\NotificationTemplate;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationTemplateService
{
    /**
     * Ambil template notifikasi yang aktif dan paling relevan
     * Priority:
     * 1. Template sekolah (school_id)
     * 2. Template global (school_id = null)
     */
    public function getActiveTemplates(
        string $event,
        string $channel,
        string $targetRole,
        ?int $schoolId = null
    ): Collection {
        return NotificationTemplate::query()
            ->where('event', $event)
            ->where('channel', $channel)
            ->where(function ($q) use ($targetRole) {
                $q->whereJsonContains('target_role', $targetRole)
                  ->orWhereJsonContains('target_role', 'all');
            })
            ->where('is_active', true)
            ->where(function ($q) use ($schoolId) {
                if ($schoolId) {
                    $q->where('school_id', $schoolId)
                      ->orWhereNull('school_id');
                } else {
                    $q->whereNull('school_id');
                }
            })
            ->orderByRaw('school_id IS NULL') // sekolah > global
            ->get();
    }

    /**
     * Create template baru
     */
    public function create(array $data, User $actor): NotificationTemplate
    {
        $this->authorizeEdit($data['editable_by'], $actor);

        return NotificationTemplate::create([
            'school_id'        => $data['school_id'] ?? null,
            'event'            => $data['event'],
            'channel'          => $data['channel'],
            'target_role'      => $data['target_role'],
            'message_template' => $data['message_template'],
            'editable_by'      => $data['editable_by'],
            'is_active'        => $data['is_active'] ?? true,
        ]);
    }

    /**
     * Update template
     */
    public function update(
        NotificationTemplate $template,
        array $data,
        User $actor
    ): NotificationTemplate {
        $this->authorizeEdit($template->editable_by, $actor);

        $template->update([
            'message_template' => $data['message_template'],
            'is_active'        => $data['is_active'] ?? $template->is_active,
        ]);

        return $template;
    }

    /**
     * Aktif / nonaktif template
     */
    public function toggle(
        NotificationTemplate $template,
        User $actor
    ): NotificationTemplate {
        $this->authorizeEdit($template->editable_by, $actor);

        $template->update([
            'is_active' => !$template->is_active,
        ]);

        return $template;
    }

    /**
     * Validasi siapa yang boleh edit template
     */
    protected function authorizeEdit(string $editableBy, User $actor): void
    {
        if ($editableBy === 'masteradmin' && $actor->role !== 'masteradmin') {
            abort(403, 'Hanya MasterAdmin yang boleh mengubah template ini.');
        }

        if ($editableBy === 'admin_school' && !in_array($actor->role, ['admin', 'masteradmin'])) {
            abort(403, 'Hanya Admin Sekolah atau MasterAdmin yang boleh mengubah template ini.');
        }
    }
}
