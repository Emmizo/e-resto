<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRestaurantPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = $request->user();

        // Get restaurant ID from various possible sources
        $restaurantId = $request->route('restaurantId') ??
                       $request->input('restaurant_id') ??
                       session('userData')['users']->restaurant_id;

        if (!$restaurantId) {
            abort(403, 'Restaurant not specified');
        }

        // Admin bypass (optional - you might want to keep this)
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Restaurant owner check (if they own this restaurant)
        if ($user->role === 'restaurant_owner') {
            $ownsRestaurant = \App\Models\Restaurant::where('id', $restaurantId)
                ->where('owner_id', $user->id)
                ->exists();

            if ($ownsRestaurant) {
                return $next($request);
            }
        }

        // Check specific permission
        if (!$user->hasRestaurantPermission($permission, $restaurantId)) {
            abort(403, "Unauthorized - You don't have {$permission} permission for this restaurant");
        }

        return $next($request);
    }
}
