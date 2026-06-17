<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    public static function send(string $token, string $title, string $body, array $data = []): void
    {
        $serverKey = config('services.firebase.server_key');

        if (!$serverKey) {
            Log::warning('FCM server key not configured — skipping push notification.');
            return;
        }

        try {
            Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type'  => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $token,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('FCM send error: ' . $e->getMessage());
        }
    }
}
