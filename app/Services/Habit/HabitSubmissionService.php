<?php

namespace App\Services\Habit;

use App\Models\HabitSubmission;
use App\Models\User;
use App\Services\Media\MediaUploadService;

class HabitSubmissionService
{
    public function submit(
        User $student,
        int $habitId,
        array $files = [],
        ?string $description = null
    ): HabitSubmission {
        $submission = HabitSubmission::create([
            'student_id' => $student->student->id,
            'habit_id' => $habitId,
            'description' => $description,
            'status' => 'pending_parent',
        ]);

        foreach ($files as $file) {
            $media = app(MediaUploadService::class)->upload($file);
            $submission->mediaFiles()->attach($media->id);
        }

        return $submission;
    }
}
