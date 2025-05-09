<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FcmService
{
    public static function send($fcmToken, $title, $body, $data = [])
    {
        $serverKey = config('services.fcm.server_key');
        $payload = [
            'to' => $fcmToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $payload);

        return $response->json();
    }
}
