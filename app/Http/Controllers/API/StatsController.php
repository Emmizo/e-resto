<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function finalStats(Request $request): JsonResponse
    {
        $user = $request->user();

        $ordersCount = Order::where('user_id', $user->id)->count();
        $reservationsCount = Reservation::where('user_id', $user->id)->count();
        // Assuming you have a favorites table or method. Adjust as needed:
        $favoritesCount = DB::table('favorite_menu_items')
            ->where('user_id', $user->id)
            ->count();

        $stats = [
            ['label' => 'Orders', 'value' => (string) $ordersCount],
            ['label' => 'Favorites', 'value' => (string) $favoritesCount],
            ['label' => 'Reservations', 'value' => (string) $reservationsCount],
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }
}
