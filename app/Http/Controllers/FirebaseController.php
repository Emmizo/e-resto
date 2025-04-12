<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    /**
     * Get Firebase configuration for client-side initialization
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConfig()
    {
        return response()->json([
            'apiKey' => config('services.firebase.api_key'),
            'authDomain' => config('services.firebase.auth_domain'),
            'projectId' => config('services.firebase.project_id'),
            'storageBucket' => config('services.firebase.storage_bucket'),
            'messagingSenderId' => config('services.firebase.messaging_sender_id'),
            'appId' => config('services.firebase.app_id'),
            'measurementId' => config('services.firebase.measurement_id'),
        ]);
    }
}
