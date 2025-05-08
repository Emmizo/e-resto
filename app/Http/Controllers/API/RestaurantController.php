<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FavoriteRestaurant;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Restaurants",
 *     description="API Endpoints for managing restaurants"
 * )
 */
class RestaurantController extends Controller
{
    /**
     * Display a listing of restaurants with their menus.
     *
     * @OA\Get(
     *     path="/restaurants",
     *     summary="List all active restaurants with their menus",
     *     tags={"Restaurants"},
     *     @OA\Response(
     *         response=200,
     *         description="Restaurants retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Restaurants retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Restaurant Name"),
     *                     @OA\Property(property="description", type="string", example="Restaurant Description"),
     *                     @OA\Property(property="address", type="string", example="123 Main St"),
     *                     @OA\Property(property="phone", type="string", example="+1234567890"),
     *                     @OA\Property(property="email", type="string", example="restaurant@example.com"),
     *                     @OA\Property(property="status", type="boolean", example=true),
     *                     @OA\Property(property="average_rating", type="number", format="float", example=4.5),
     *                     @OA\Property(
     *                         property="menus",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Main Menu"),
     *                             @OA\Property(property="is_active", type="boolean", example=true),
     *                             @OA\Property(
     *                                 property="menu_items",
     *                                 type="array",
     *                                 @OA\Items(
     *                                     type="object",
     *                                     @OA\Property(property="id", type="integer", example=1),
     *                                     @OA\Property(property="name", type="string", example="Burger"),
     *                                     @OA\Property(property="description", type="string", example="Delicious burger"),
     *                                     @OA\Property(property="price", type="number", format="float", example=9.99),
     *                                     @OA\Property(property="is_available", type="boolean", example=true)
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve restaurants"),
     *             @OA\Property(property="error", type="string", example="Error details")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $favoriteIds = [];
            if ($user) {
                $favoriteIds = FavoriteRestaurant::where('user_id', $user->id)
                    ->pluck('restaurant_id')
                    ->toArray();
            }
            // Get all active restaurants with their active menus and menu items
            $restaurants = Restaurant::with(['menus' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->with(['menuItems' => function ($query) {
                        $query->where('is_available', true);
                    }]);
            }, 'reviews'])
                ->where('is_approved', true)
                ->get();

            // Add average_rating and is_favorite to each restaurant
            $restaurants = $restaurants->map(function ($restaurant) use ($favoriteIds) {
                $restaurantArray = $restaurant->toArray();
                $restaurantArray['average_rating'] = round($restaurant->reviews->avg('rating'), 2) ?? null;
                $restaurantArray['is_favorite'] = in_array($restaurant->id, $favoriteIds);
                unset($restaurantArray['reviews']);
                return $restaurantArray;
            })->toArray();

            if (empty($restaurants)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No restaurants found',
                    'data' => []
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Restaurants retrieved successfully',
                'data' => $restaurants
            ]);
        } catch (\Exception $e) {
            \Log::error('Error retrieving restaurants: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve restaurants',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a restaurant to user's favorites
     */
    public function favoriteRestaurant(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);
        $user = Auth::user();
        $favorite = FavoriteRestaurant::firstOrCreate([
            'user_id' => $user->id,
            'restaurant_id' => $request->restaurant_id,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Restaurant added to favorites',
            'data' => $favorite
        ], 201);
    }

    /**
     * Remove a restaurant from user's favorites
     */
    public function unfavoriteRestaurant(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);
        $user = Auth::user();
        $deleted = FavoriteRestaurant::where('user_id', $user->id)
            ->where('restaurant_id', $request->restaurant_id)
            ->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Restaurant removed from favorites',
            'deleted' => $deleted > 0
        ]);
    }

    /**
     * List all favorite restaurants for the authenticated user
     */
    public function listFavoriteRestaurants(Request $request)
    {
        $user = Auth::user();
        $favorites = FavoriteRestaurant::with('restaurant')
            ->where('user_id', $user->id)
            ->get();
        $result = $favorites->map(function ($favorite) {
            $restaurant = $favorite->restaurant;
            $reviews = $restaurant->reviews;
            return [
                'restaurant' => $restaurant,
                'average_rating' => $reviews->avg('rating') ? round($reviews->avg('rating'), 2) : null,
                'reviews_count' => $reviews->count(),
            ];
        });
        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }
}
