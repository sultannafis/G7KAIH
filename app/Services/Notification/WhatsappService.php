<?php

namespace App\Services\Notification;

use App\Models\NotificationLog;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class WhatsappService
{
    public function send(string $phone, string $message, User $user, string $event = 'WA'): void
    {
        // Bersihkan nomor: pastikan format 628xxx (tanpa +)
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        }

        $instanceId = config('services.greenapi.instance_id');
        $token      = config('services.greenapi.token');
        $baseUrl    = config('services.greenapi.api_url', 'https://api.green-api.com');

        // URL format Green API: /waInstance{id}/sendMessage/{token}
        $url = rtrim($baseUrl, '/') . "/waInstance{$instanceId}/sendMessage/{$token}";

        try {
            $response = Http::timeout(10)->post($url, [
                'chatId'  => $phone . '@c.us',
                'message' => $message,
            ]);

            $status = $response->successful() ? 'sent' : 'failed';
            $error  = $response->successful() ? null : $response->body();

            NotificationLog::create([
                'event'         => $event,
                'channel'       => 'whatsapp',
                'user_id'       => $user->id,
                'school_id'     => $user->school_id,
                'recipient'     => $phone,
                'message'       => $message,
                'status'        => $status,
                'error_message' => $error,
            ]);
        } catch (\Throwable $e) {
            NotificationLog::create([
                'event'         => $event,
                'channel'       => 'whatsapp',
                'user_id'       => $user->id,
                'school_id'     => $user->school_id,
                'recipient'     => $phone,
                'message'       => $message,
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}