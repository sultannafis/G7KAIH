<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\G7KaihClass;
use App\Models\Habit;
use App\Models\HabitItem;
use App\Models\HabitSubmission;
use App\Models\HabitValidation;
use App\Models\HabitRule;
use App\Models\Parents;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SmksInformatikaUtamaSeeder extends Seeder
{
    public function run()
    {
        $school = School::where('name', 'like', '%SMKS INFORMATIKA UTAMA%')->first();
        if (!$school) {
            $this->command->error('Sekolah SMKS INFORMATIKA UTAMA tidak ditemukan.');
            return;
        }

        $this->command->info("Memulai proses seeding untuk sekolah: {$school->name}");

        // ── 1. Pastikan ada 5 Guru ───────────────────────────────────────
        $teachers = User::where('school_id', $school->id)->where('role', 'guru')->get();
        $this->command->info("Guru saat ini: " . $teachers->count());

        while ($teachers->count() < 5) {
            $n    = $teachers->count() + 1;
            $user = User::create([
                'school_id'         => $school->id,
                'name'              => 'Guru Tambahan ' . $n,
                'email'             => 'guru.tambahan' . $n . '@smksinformatika.example.com',
                'role'              => 'guru',
                'password'          => Hash::make('password'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]);
            Teacher::create(['user_id' => $user->id, 'nip' => 'NIP' . rand(100000, 999999)]);
            $teachers = User::where('school_id', $school->id)->where('role', 'guru')->get();
        }
        $teachers = $teachers->take(5);

        // ── 2. Pastikan ada 5 Kelas ──────────────────────────────────────
        $existingClasses = G7KaihClass::where('school_id', $school->id)->get();
        $classNames      = ['RPL A', 'TKJ A', 'MM A', 'AKL A', 'OTKP A'];

        $classes = collect();
        foreach ($teachers as $index => $teacher) {
            if ($index < $existingClasses->count()) {
                $classRecord = $existingClasses[$index];
                $classRecord->update(['teacher_id' => $teacher->id]);
            } else {
                $classRecord = G7KaihClass::create([
                    'school_id'     => $school->id,
                    'name'          => $classNames[$index] ?? 'Kelas Tambahan ' . ($index + 1),
                    'teacher_id'    => $teacher->id,
                    'academic_year' => '2024/2025',
                    'is_active'     => true,
                    'created_by'    => $teacher->id,
                ]);
            }
            $classes->push($classRecord);
        }

        // ── 3. Ambil/buat Habits ─────────────────────────────────────────
        $habits = Habit::where('school_id', $school->id)
            ->with(['items.rules', 'rules'])
            ->get();
        if ($habits->count() == 0) {
            $dummyHabit = Habit::create([
                'school_id'   => $school->id,
                'name'        => 'Sholat Wajib Berjamaah',
                'description' => 'Sholat 5 waktu',
                'is_active'   => true,
            ]);
            $habits->push($dummyHabit);
        }

        // ── 4. Isi 10 Siswa per Kelas beserta orang tua & habit submission ─
        $firstClass = true;

        foreach ($classes as $classRecord) {
            $this->command->info("Memproses kelas: {$classRecord->name}");

            // Ambil guru kelas untuk dipakai sebagai validator guru
            $teacherUser = $classRecord->teacher; // relasi User (role=guru)

            // Ambil siswa yang sudah ada
            $classStudents = Student::where('g7_kaih_class_id', $classRecord->id)
                ->with(['user', 'parents.user'])
                ->get();

            // Tambah siswa sampai 10
            while ($classStudents->count() < 10) {
                $currCount = $classStudents->count();

                if ($firstClass && $currCount == 0) {
                    $studentName = 'Sultan';
                } elseif ($firstClass && $currCount == 1) {
                    $studentName = 'Mutiara';
                } else {
                    $studentName = 'Siswa ' . $classRecord->name . ' ' . ($currCount + 1);
                }

                $studentUser = User::create([
                    'school_id'         => $school->id,
                    'name'              => $studentName,
                    'email'             => Str::slug($studentName) . rand(100, 999) . '@siswa.example.com',
                    'role'              => 'siswa',
                    'password'          => Hash::make('password'),
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ]);

                $student = Student::create([
                    'user_id'           => $studentUser->id,
                    'g7_kaih_class_id'  => $classRecord->id,
                    'nisn'              => rand(1000000000, 9999999999),
                    'nis'               => rand(10000, 99999),
                    'gender'            => rand(0, 1) ? 'Laki-laki' : 'Perempuan',
                    'total_point'       => 0,
                ]);

                // Buat orang tua langsung saat siswa dibuat
                $this->ensureParent($school, $student, $studentName);

                $classStudents->push($student->load(['user', 'parents.user']));
            }

            $classStudents = $classStudents->take(10);

            // Pastikan SEMUA siswa existing juga punya orang tua
            foreach ($classStudents as $student) {
                if ($student->parents->isEmpty()) {
                    $this->command->info("    → Membuat orang tua untuk: {$student->user->name}");
                    $this->ensureParent($school, $student, $student->user->name);
                    $student->load('parents.user');
                }
            }

            // Urutkan: Sultan & Mutiara di depan (kelas pertama)
            if ($firstClass) {
                $classStudents = $classStudents->sortByDesc(function ($st) {
                    $name = strtolower($st->user->name);
                    if (str_contains($name, 'sultan'))  return 2;
                    if (str_contains($name, 'mutiara')) return 1;
                    return 0;
                })->values();
            }

            foreach ($classStudents as $index => $student) {
                // Kategori kehadiran & kualitas poin
                if ($index < 2) {
                    $label          = 'Sangat Baik/Terbiasa';
                    $complianceDays = 25; // >70% dari 30 hari
                    $ruleRank       = 0;  // Ambil rule dengan poin tertinggi
                } elseif ($index < 4) {
                    $label          = 'Perlu Perbaikan';
                    $complianceDays = 2;  // <36%
                    $ruleRank       = 2;  // Ambil rule dengan poin terendah
                } else {
                    $label          = 'Baik/Standar';
                    $complianceDays = 15; // 36-69%
                    $ruleRank       = 1;  // Ambil rule tengah
                }

                $this->command->info("  - {$student->user->name} [Kategori: {$label}, {$complianceDays} hari]");

                // Ambil orang tua siswa ini (untuk dipakai sebagai validator parent)
                $parentUser = $student->parents->first()?->user;

                $subCount = 0;
                for ($day = 0; $day < $complianceDays; $day++) {
                    $submittedAt    = Carbon::now()->subDays($day)->setTime(6, rand(0, 59));
                    $submissionDate = $submittedAt->toDateString();

                    foreach ($habits as $habit) {
                        // Pilih item: untuk multi_select ambil acak 1 item
                        $regularItems = $habit->items->where('is_activity_option', false);
                        $habitItem = $regularItems->isNotEmpty()
                            ? $regularItems->random()
                            : null;

                        // Pilih rule yang sesuai dengan item/habit dan rank siswa
                        $applicableRules = $habitItem
                            ? $habitItem->rules->sortByDesc('point')->values()
                            : $habit->rules->sortByDesc('point')->values();

                        $selectedRule = $applicableRules->get($ruleRank)
                            ?? $applicableRules->last(); // fallback ke yang ada

                        $rulePoint = $selectedRule?->point ?? 100;
                        $ruleId    = $selectedRule?->id ?? null;

                        // Cek apakah submission untuk tanggal+habit ini sudah ada
                        $existing = HabitSubmission::where('student_id', $student->id)
                            ->where('habit_id', $habit->id)
                            ->where('submission_date', $submissionDate)
                            ->first();

                        if ($existing) {
                            if ($existing->status !== 'teacher_valid') {
                                $this->escalateToFinal($existing, $parentUser, $teacherUser, $rulePoint);
                            }
                            continue;
                        }

                        // Buat submission baru — mulai dari pending_parent
                        $submission = HabitSubmission::create([
                            'student_id'       => $student->id,
                            'g7_kaih_class_id' => $classRecord->id,
                            'habit_id'         => $habit->id,
                            'habit_item_id'    => $habitItem?->id,
                            'habit_rule_id'    => $ruleId,
                            'submission_date'  => $submissionDate,
                            'submitted_at'     => $submittedAt,
                            'status'           => 'pending_parent',
                            'point'            => 0,
                        ]);

                        $this->escalateToFinal($submission, $parentUser, $teacherUser, $rulePoint);
                        $subCount++;
                    }
                }

                $this->command->info("    Total {$subCount} submissions dibuat.");
                $student->recalculateTotalPoint();
            }

            $firstClass = false;
        }

        $this->command->info('✅ Seeding selesai! Alur validasi siswa → orang tua → AI → guru sudah diterapkan.');
    }

    /**
     * Buat akun + record orang tua untuk siswa yang belum punya.
     */
    private function ensureParent(School $school, Student $student, string $studentName): void
    {
        if ($student->parents()->exists()) {
            return;
        }

        $parentName = 'Ortu ' . $studentName;
        $parentUser = User::create([
            'school_id'         => $school->id,
            'name'              => $parentName,
            'email'             => Str::slug($parentName) . rand(100, 999) . '@ortu.example.com',
            'role'              => 'orang_tua',
            'password'          => Hash::make('password'),
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        Parents::create([
            'user_id'             => $parentUser->id,
            'student_id'          => $student->id,
            'family_relationship' => rand(0, 1) ? 'Ayah' : 'Ibu',
        ]);
    }

    /**
     * Jalankan alur validasi bertahap:
     *   pending_parent → (parent approve) → pending_ai
     *                 → (AI approve)     → ai_valid
     *                 → (guru approve)   → teacher_valid
     *
     * Poin diambil dari HabitRule yang sudah dipilih di luar method ini.
     * Setiap langkah meninggalkan rekam jejak di tabel habit_validations.
     */
    private function escalateToFinal(
        HabitSubmission $sub,
        ?User           $parentUser,
        ?User           $teacherUser,
        int             $point = 100
    ): void {
        // ── STEP 1: Parent approve ──────────────────────────────────────
        if ($sub->status === 'pending_parent') {
            HabitValidation::create([
                'habit_submission_id' => $sub->id,
                'validator_type'      => 'parent',
                'validator_id'        => $parentUser?->id,
                'status'              => 'approved',
                'reason'              => null,
            ]);
            $sub->update(['status' => 'pending_ai']);
            $sub->refresh();
        }

        // ── STEP 2: AI approve ─────────────────────────────────────────
        if ($sub->status === 'pending_ai') {
            HabitValidation::create([
                'habit_submission_id' => $sub->id,
                'validator_type'      => 'ai',
                'validator_id'        => null,
                'status'              => 'approved',
                'reason'              => 'Terdeteksi otomatis oleh sistem AI.',
            ]);
            $sub->update([
                'status'          => 'ai_valid',
                'ai_confidence'   => rand(85, 99) / 100,
                'ai_needs_review' => false,
            ]);
            $sub->refresh();
        }

        // ── STEP 3: Guru approve → point dari rule ─────────────────────
        if ($sub->status === 'ai_valid') {
            HabitValidation::create([
                'habit_submission_id' => $sub->id,
                'validator_type'      => 'teacher',
                'validator_id'        => $teacherUser?->id,
                'status'              => 'approved',
                'reason'              => null,
            ]);
            $sub->update([
                'status' => 'teacher_valid',
                'point'  => $point, // poin dari HabitRule yang dipilih
            ]);
        }
    }
}
