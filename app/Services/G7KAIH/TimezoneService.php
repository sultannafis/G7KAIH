<?php

namespace App\Services\G7KAIH;

use App\Models\School;
use Carbon\Carbon;

class TimezoneService
{
    public function nowForSchool(School $school): Carbon
    {
        return Carbon::now($school->timezone);
    }

    public function parseForSchool(string $datetime, School $school): Carbon
    {
        return Carbon::parse($datetime, $school->timezone);
    }
}
