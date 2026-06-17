<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    public function getConfig()
    {
        return response()->json([
            'apiKey'            => config('services.firebase.api_key'),
            'authDomain'        => config('services.firebase.auth_domain'),
            'projectId'         => config('services.firebase.project_id'),
            'storageBucket'     => config('services.firebase.storage_bucket'),
            'messagingSenderId' => config('services.firebase.messaging_sender_id'),
            'appId'             => config('services.firebase.app_id'),
        ]);
    }

    public function saveFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = auth()->user();
        if ($user) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }

        return response()->json(['status' => 200, 'message' => 'FCM token saved']);
    }
}
