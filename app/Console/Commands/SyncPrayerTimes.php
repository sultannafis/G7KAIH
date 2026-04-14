<?php

namespace App\Console\Commands;

use App\Models\School;
use App\Services\Prayer\PrayerTimeService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncPrayerTimes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prayer:sync 
                            {--school= : ID sekolah tertentu (opsional)}
                            {--date= : Tanggal yang akan di-sync (format: Y-m-d, default: hari ini)}
                            {--days=1 : Jumlah hari yang akan di-sync (default: 1)}
                            {--force : Force refresh data yang sudah ada}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync waktu sholat dari API Aladhan untuk sekolah';

    protected $prayerTimeService;

    /**
     * Create a new command instance.
     */
    public function __construct(PrayerTimeService $prayerTimeService)
    {
        parent::__construct();
        $this->prayerTimeService = $prayerTimeService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🕌 Starting Prayer Times Sync...');
        $this->newLine();

        // Get schools to sync
        $schools = $this->getSchools();

        if ($schools->isEmpty()) {
            $this->error('Tidak ada sekolah yang ditemukan!');
            return 1;
        }

        $this->info("Sekolah yang akan di-sync: {$schools->count()}");
        $this->newLine();

        // Get date range
        $startDate = $this->option('date') 
            ? Carbon::parse($this->option('date'))
            : Carbon::now();

        $days = (int) $this->option('days');
        $force = $this->option('force');

        // Progress bar
        $bar = $this->output->createProgressBar($schools->count() * $days);
        $bar->start();

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        // Sync each school
        foreach ($schools as $school) {
            for ($i = 0; $i < $days; $i++) {
                $date = $startDate->copy()->addDays($i);
                $dateFormatted = $date->format('d-m-Y');

                try {
                    if ($force) {
                        $prayerTime = $this->prayerTimeService->forceRefresh($school, $dateFormatted);
                    } else {
                        $prayerTime = $this->prayerTimeService->sync($school, $dateFormatted);
                    }

                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = [
                        'school' => $school->name,
                        'date' => $dateFormatted,
                        'error' => $e->getMessage(),
                    ];
                }

                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);

        // Display results
        $this->info('✅ Sync selesai!');
        $this->newLine();

        $this->table(
            ['Metric', 'Count'],
            [
                ['Sekolah', $schools->count()],
                ['Total Days', $days],
                ['Success', $successCount],
                ['Error', $errorCount],
            ]
        );

        // Display errors if any
        if ($errorCount > 0) {
            $this->newLine();
            $this->error('❌ Errors:');
            $this->table(
                ['School', 'Date', 'Error'],
                collect($errors)->map(function ($error) {
                    return [
                        $error['school'],
                        $error['date'],
                        \Illuminate\Support\Str::limit($error['error'], 50),
                    ];
                })->toArray()
            );
        }

        return $errorCount > 0 ? 1 : 0;
    }

    /**
     * Get schools to sync based on options.
     */
    protected function getSchools()
    {
        if ($schoolId = $this->option('school')) {
            $school = School::find($schoolId);
            
            if (!$school) {
                $this->error("Sekolah dengan ID {$schoolId} tidak ditemukan!");
                return collect();
            }

            return collect([$school]);
        }

        // Get all active schools
        return School::where('status', 'active')->get();
    }
}