<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestaurantPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permissionName)
    {
        $user = auth()->user();

        // If user is a restaurant owner, they have full access
        if ($user->role === 'restaurant_owner') {
            return $next($request);
        }

        // Get the restaurant ID from the request or route
        $restaurantId = $request->route('restaurant_id')
            ?? $request->input('restaurant_id')
            ?? session('current_restaurant_id');

        // Check if the user has the specific permission for this restaurant
        if (!$user->hasRestaurantPermission($restaurantId, $permissionName)) {
            // Redirect or return unauthorized response
            return response()->json([
                'message' => 'Unauthorized. You do not have permission to perform this action.'
            ], 403);
        }

        return $next($request);
    }
}
