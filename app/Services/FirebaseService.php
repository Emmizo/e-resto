<?php

namespace App\Services;

use App\Models\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Firebase\Messaging\WebPushConfig;
use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/resto-finder-d4214-firebase-adminsdk-fbsvc-7956a2024a.json'));

        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($token, $title, $body, $data = [], $userId = null)
    {
        try {
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(FirebaseNotification::create($title, $body))
                ->withData($data);

            $result = $this->messaging->send($message);
            \Log::info('Firebase notification sent', [
                'token' => $token,
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'result' => $result
            ]);

            // Save notification to DB if user_id is available
            $uid = $userId ?? ($data['user_id'] ?? null);
            $restaurantId = $data['restaurant_id'] ?? null;
            if ($uid) {
                Notification::create([
                    'user_id' => $uid,
                    'restaurant_id' => $restaurantId,
                    'title' => $title,
                    'body' => $body,
                    'data' => $data,
                    'is_read' => false,
                ]);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Firebase notification error: ' . $e->getMessage(), [
                'token' => $token,
                'title' => $title,
                'body' => $body,
                'data' => $data
            ]);
            return false;
        }
    }

    public function sendMulticastNotification($tokens, $title, $body, $data = [])
    {
        $notification = FirebaseNotification::create($title, $body);

        $message = CloudMessage::new()
            ->withNotification($notification)
            ->withData($data);

        try {
            $result = $this->messaging->sendMulticast($message, $tokens);
            \Log::info('Firebase multicast notification sent', [
                'tokens' => $tokens,
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'result' => $result
            ]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Firebase multicast notification error: ' . $e->getMessage(), [
                'tokens' => $tokens,
                'title' => $title,
                'body' => $body,
                'data' => $data
            ]);
            return false;
        }
    }
}
