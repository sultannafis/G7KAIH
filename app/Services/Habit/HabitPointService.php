<?php

namespace App\Services\Habit;

use App\Models\HabitRule;
use Carbon\Carbon;

class HabitPointService
{
    public function calculate(?HabitRule $rule, ?Carbon $submittedAt): int
    {
        if (!$rule) {
            return 0;
        }

        if (!$rule->start_time || !$rule->end_time) {
            return $rule->points;
        }

        return $submittedAt->between(
            Carbon::parse($rule->start_time),
            Carbon::parse($rule->end_time)
        ) ? $rule->points : 0;
    }
}
