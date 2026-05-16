<?php

namespace App\Services\AI;

use App\Models\AiSetting;
use App\Models\HabitSubmission;
use App\Models\HabitValidation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiValidationService
{
    /**
     * Cek apakah submission ini perlu/boleh divalidasi AI.
     * Kondisi: rule punya allow_ai_validation=true + sekolah aktif + ada foto
     */
    public function shouldValidate(HabitSubmission $submission): bool
    {
        $setting = AiSetting::instance();

        // Global validasi AI harus aktif
        if (!$setting->ai_validation_enabled)
            return false;

        // Rule harus mengizinkan AI validasi
        if (!$submission->rule?->allow_ai_validation)
            return false;

        // Sekolah siswa harus termasuk yang diizinkan
        $schoolId = $submission->student?->user?->school_id;
        if (!$schoolId || !$setting->schoolCanUseAiValidation($schoolId))
            return false;

        return true;
    }

    /**
     * Jalankan validasi AI untuk satu submission.
     * - Kalau ada foto → kirim ke Claude vision
     * - Kalau tidak ada foto → validasi berdasarkan deskripsi teks saja
     *
     * Return: ['approved' => bool, 'reason' => string, 'needs_review' => bool]
     */
    public function validate(HabitSubmission $submission): array
    {
        $setting   = AiSetting::instance();
        $habitName = $submission->habit?->name ?? 'tidak diketahui';
        
        $itemName  = $submission->habitItem?->name;
        if (!$itemName && $submission->habit?->is_multi_select) {
            $submission->loadMissing('selectedActivities');
            $itemName = $submission->selectedActivities->pluck('name')->join(' | ');
        }
        
        $desc      = $submission->description ?? '';
        $ruleName  = $submission->rule?->name ?? '';
        $timeStr   = $submission->submitted_at ? $submission->submitted_at->setTimezone('Asia/Jakarta')->format('H:i') . ' WIB' : 'tidak tercatat';
        $dateStr   = $submission->submission_date ? $submission->submission_date->format('d F Y') : 'tidak tercatat';

        // Kumpulkan foto dari mediaFiles
        $mediaFiles = $submission->mediaFiles ?? collect();
        $hasPhoto   = $mediaFiles->isNotEmpty();

        // Prompt dari DB, ganti placeholder [NAMA_HABIT] dengan nama habit asli
        $basePrompt = str_replace('[NAMA_HABIT]', $habitName, $setting->ai_validation_prompt ?? '');

        // Jika prompt DB belum punya instruksi JSON, kita tambahkan
        $systemPrompt = $basePrompt
            . "\n\nNama habit yang divalidasi: '{$habitName}'."
            . "\n\nKamu HARUS membalas HANYA dalam format JSON berikut, tidak ada teks lain:"
            . "\n{\"approved\": true/false, \"confidence\": 0-100, \"reason\": \"penjelasan evaluasi dari waktu, deskripsi, dan foto (jika ada)\", \"needs_review\": true/false}"
            . "\n\nATURAN KHUSUS PENGISIAN 'reason':"
            . "\n- Jelaskan hasil evaluasimu terhadap aspek-aspek yang ada: (1) Waktu Submit, (2) Deskripsi, dan (3) Foto (hanya JIKA ada foto yang dikirimkan)."
            . "\n- Jelaskan apakah aspek-aspek tersebut masuk akal, saling mendukung, dan relevan dengan habit '{$habitName}'."
            . "\n- JIKA TIDAK ADA FOTO (contohnya habit yang tidak mewajibkan foto), JANGAN bahas atau sebut soal foto sama sekali di dalam 'reason', cukup jelaskan evaluasi dari Waktu Submit dan Deskripsi saja."
            . "\n- Pastikan 'reason' tidak hanya fokus pada satu aspek, tapi merangkum analisis dari semua aspek yang dikirimkan oleh siswa.";

        // Bangun pesan user
        $userText  = "Habit: {$habitName}" . ($itemName ? " > {$itemName}" : "") . "\n";
        $userText .= "Rule: {$ruleName}\n";
        $userText .= "Waktu Submit: {$timeStr}\n";
        $userText .= "Tanggal Submit: {$dateStr}\n";
        if ($desc)
            $userText .= "Deskripsi dari siswa: {$desc}\n";
        
        $userText .= "\nNilai apakah bukti ini valid untuk habit '{$habitName}' tersebut.";
        if (!$hasPhoto) {
            $userText .= "\nCATATAN: Tidak ada foto bukti. Gunakan konteks waktu di atas untuk memvalidasi.";
        }

        // Bangun content array (OpenAI/Groq Vision format)
        if ($hasPhoto) {
            $content = [
                ['type' => 'text', 'text' => $userText]
            ];
            foreach ($mediaFiles->take(3) as $media) {
                try {
                    $imageData = $this->fetchImageAsBase64($media->url);
                    if ($imageData) {
                        $content[] = [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => 'data:' . $imageData['mime'] . ';base64,' . $imageData['data'],
                            ],
                        ];
                    }
                } catch (\Throwable $e) {
                    Log::warning("AI Validation: gagal fetch foto {$media->url}: " . $e->getMessage());
                }
            }
        } else {
            // Jika tidak ada foto, kirimkan sebagai string biasa untuk stabilitas
            $content = $userText;
        }

        // Panggil Groq API (OpenAI Compatible)
        try {
            $url = 'https://api.groq.com/openai/v1/chat/completions';
            
            $payload = [
                'model' => 'meta-llama/llama-4-scout-17b-16e-instruct',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $content],
                ],
                'temperature' => 0,
                'max_tokens' => 500,
                'response_format' => ['type' => 'json_object']
            ];

            $response = Http::timeout(35)->withHeaders([
                'Authorization' => 'Bearer ' . config('services.groq.key'),
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            if ($response->failed()) {
                Log::error('AI Validation Groq API error: ' . $response->body());
                return $this->fallbackResult('API error (' . $response->status() . ')');
            }

            $result = $response->json('choices.0.message.content');
            if (is_string($result)) {
                $result = json_decode($result, true);
            }

            if (!is_array($result)) {
                return $this->fallbackResult('Respons AI tidak valid');
            }

            return [
                'approved' => (bool) ($result['approved'] ?? true),
                'confidence' => (int) ($result['confidence'] ?? 50),
                'reason' => (string) ($result['reason'] ?? 'Divalidasi oleh AI'),
                'needs_review' => (bool) ($result['needs_review'] ?? false),
            ];

        } catch (\Throwable $e) {
            Log::error('AI Validation exception (Groq): ' . $e->getMessage());
            return $this->fallbackResult($e->getMessage());
        }
    }

    /**
     * Simpan hasil validasi AI ke DB dan update status submission.
     * - approved + tidak needs_review → status ai_valid, poin dikunci
     * - approved + needs_review      → status ai_valid tapi flag needs_review
     * - rejected                     → status ai_valid (tetap masuk) tapi flagged, guru wajib review
     */
    public function applyResult(HabitSubmission $submission, array $result): void
    {
        $approved = $result['approved'];
        $reason = $result['reason'];
        $confidence = (int) ($result['confidence'] ?? 0);
        
        // Sistem otomatis melanjutkan (memberi poin & ai_valid) HANYA JIKA:
        // 1. AI menyetujui (approved = true)
        // 2. Confidence di atas 70%
        // 3. AI tidak memflag needs_review secara eksplisit
        $needsReview = !$approved || $confidence <= 70 || !empty($result['needs_review']);

        // Selalu log validasi AI
        $validation = HabitValidation::create([
            'habit_submission_id' => $submission->id,
            'validator_type' => 'ai',
            'validator_id' => null,
            'status' => $approved ? 'approved' : 'rejected',
            'reason' => $reason . ($needsReview ? ' [Perlu review guru]' : ''),
        ]);

        if ($submission->submitted_at) {
            $validation->created_at = $submission->submitted_at;
            $validation->save();
        }

        // Poin dari rule
        $point = $submission->rule?->point ?? 0;

        // Jika perlu review guru (baik karena ditolak, confidence <= 70%, atau flagged AI), statusnya pending_teacher dan poin ditahan
        $finalStatus = $needsReview ? 'pending_teacher' : 'ai_valid';
        $finalPoint  = (!$needsReview) ? $point : 0;

        $submission->update([
            'status' => $finalStatus,
            'point' => $finalPoint,
            'ai_needs_review' => $needsReview,
            'ai_confidence' => $result['confidence'],
        ]);

        // RE-CALCULATE STUDENT TOTAL POINTS
        if ($submission->student) {
            $submission->student->recalculateTotalPoint();
        }
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    private function fetchImageAsBase64(string $url): ?array
    {
        $response = Http::timeout(10)->get($url);
        if ($response->failed())
            return null;

        $mime = $response->header('Content-Type') ?? 'image/jpeg';
        // Pastikan hanya image yang dikirim
        if (!str_starts_with($mime, 'image/'))
            return null;

        return [
            'mime' => explode(';', $mime)[0],
            'data' => base64_encode($response->body()),
        ];
    }

    private function fallbackResult(string $reason): array
    {
        return [
            'approved' => true,  // fallback: lolos tapi perlu review
            'confidence' => 0,
            'reason' => 'AI tidak dapat memvalidasi: ' . $reason,
            'needs_review' => true,
        ];
    }
}
