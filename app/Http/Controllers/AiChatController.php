<?php

namespace App\Http\Controllers;

use App\Models\AiSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    /**
     * POST /ai/chat — dipanggil widget chatbot di frontend
     * Mendukung teks biasa maupun lampiran gambar (base64 array).
     */
    public function chat(Request $request)
    {
        $setting = AiSetting::instance();

        if (!$setting->is_enabled) {
            return response()->json(['error' => 'AI tidak aktif.'], 403);
        }

        $request->validate([
            'message'       => 'nullable|string|max:2000',
            'context'       => 'nullable|string|in:habit,habit_item,general',
            'field'         => 'nullable|string',
            'images'        => 'nullable|array|max:5',
            'images.*.data' => 'nullable|string',   // base64 murni
            'images.*.mime' => 'nullable|string',   // e.g. image/jpeg
        ]);

        $user     = auth()->user();
        $role     = $user?->role ?? 'user';
        $fieldCtx = $request->input('context', 'general');
        $field    = $request->input('field', '');
        $message  = trim($request->input('message', '')) ?: 'Tolong analisis gambar ini.';
        $images   = $request->input('images', []);

        // Ambil system prompt dari DB
        $basePrompt = $setting->ai_chat_system_prompt
            ?? "Nama kamu adalah {$setting->app_name} AI Assistant. Selalu jawab dalam Bahasa Indonesia.";

        $systemPrompt = $basePrompt . $this->buildFieldContext($role, $fieldCtx, $field);

        // Pilih model & bangun content berdasarkan ada/tidaknya gambar
        $hasImages = collect($images)->filter(fn($i) => !empty($i['data']))->isNotEmpty();

        if ($hasImages) {
            // Model vision (Llama 4 Scout) untuk gambar
            $model = 'meta-llama/llama-4-scout-17b-16e-instruct';

            $contentParts = [['type' => 'text', 'text' => $message]];

            foreach (array_slice($images, 0, 4) as $img) {
                $mime = $img['mime'] ?? 'image/jpeg';
                $data = $img['data'] ?? '';
                if (!$data || !str_starts_with($mime, 'image/')) continue;

                $contentParts[] = [
                    'type'      => 'image_url',
                    'image_url' => ['url' => 'data:' . $mime . ';base64,' . $data],
                ];
            }

            $userContent = $contentParts;
        } else {
            // Teks saja — model versatile
            $model       = 'llama-3.3-70b-versatile';
            $userContent = $message;
        }

        // Kirim ke Groq (OpenAI-compatible)
        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . config('services.groq.key'),
                'Content-Type'  => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'       => $model,
                'max_tokens'  => 600,
                'temperature' => 0.7,
                'messages'    => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user',   'content' => $userContent],
                ],
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('AiChat connection error: ' . $e->getMessage());
            return response()->json(['error' => 'Koneksi ke AI gagal. Periksa koneksi server.'], 500);
        }

        if ($response->failed()) {
            $body = $response->json();
            $errMsg = $body['error']['message'] ?? ('HTTP ' . $response->status());
            \Illuminate\Support\Facades\Log::error('AiChat Groq error: ' . $errMsg);
            return response()->json(['error' => 'AI error: ' . $errMsg], 200); // 200 agar JS bisa baca pesan
        }

        $text = $response->json('choices.0.message.content', '');
        return response()->json(['reply' => trim($text)]);
    }

    /**
     * Tambahkan instruksi konteks field ke system prompt.
     */
    private function buildFieldContext(string $role, string $fieldCtx, ?string $field = ''): string
    {
        $roleLabel = match ($role) {
            'masteradmin' => 'Master Admin',
            'admin'       => 'Admin Sekolah',
            'guru'        => 'Guru',
            'siswa'       => 'Siswa',
            'orangtua'    => 'Orang Tua',
            default       => 'Pengguna',
        };

        $ctx = "\n\n[KONTEKS SESI]\nPengguna saat ini: {$roleLabel}.";

        if ($fieldCtx === 'habit') {
            $ctx .= "\nMode: bantu buat NAMA HABIT dan DESKRIPSI HABIT.";
            if ($field === 'name')
                $ctx .= "\nBerikan 1 nama habit langsung, tanpa preamble, tanpa tanda kutip, maks 5 kata.";
            if ($field === 'description')
                $ctx .= "\nBerikan 1-2 kalimat deskripsi langsung, tanpa preamble.";
        } elseif ($fieldCtx === 'habit_item') {
            $ctx .= "\nMode: bantu buat NAMA ITEM HABIT dan DESKRIPSI singkat.";
            if ($field === 'name')
                $ctx .= "\nBerikan 1 nama item langsung, tanpa preamble, tanpa tanda kutip, maks 4 kata.";
            if ($field === 'description')
                $ctx .= "\nBerikan 1 kalimat deskripsi langsung, tanpa preamble.";
        } else {
            $ctx .= "\nMode: jawab pertanyaan umum dengan ramah dan membantu.";
        }

        return $ctx;
    }
}