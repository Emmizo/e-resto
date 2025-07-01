<?php

namespace App\Services;

use Pusher\PushNotifications\PushNotifications;

class PusherBeamsService
{
    protected $beams;

    public function __construct()
    {
        $this->beams = new PushNotifications([
            "instanceId" => config('pusher-beams.instance_id'),
            "secretKey" => config('pusher-beams.secret_key'),
        ]);
    }

    public function notifyInterests(array $interests, array $notification)
    {
        return $this->beams->publishToInterests(
            $interests,
            [
                "web" => [
                    "notification" => $notification
                ],
                "fcm" => [
                    "notification" => $notification
                ],
                "apns" => [
                    "aps" => [
                        "alert" => [
                            "title" => $notification['title'],
                            "body" => $notification['body'],
                        ],
                    ],
                ],
            ]
        );
    }
}
