<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Student;
use App\Models\HabitSubmission;
use App\Models\HabitValidation;

class ClearSmksSubmissionsSeeder extends Seeder
{
    public function run()
    {
        $school = School::where('name', 'like', '%SMKS INFORMATIKA UTAMA%')->first();
        if (!$school) {
            $this->command->error('Sekolah tidak ditemukan.');
            return;
        }

        $studentIds = Student::whereHas('user', fn($q) => $q->where('school_id', $school->id))
            ->pluck('id');

        $subIds = HabitSubmission::whereIn('student_id', $studentIds)->pluck('id');

        $valDeleted = HabitValidation::whereIn('habit_submission_id', $subIds)->delete();
        $subDeleted = HabitSubmission::whereIn('student_id', $studentIds)->delete();

        $this->command->info("Deleted {$valDeleted} validations dan {$subDeleted} submissions.");
    }
}
