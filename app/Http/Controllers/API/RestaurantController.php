<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            // Get all active restaurants with their active menus and menu items
            $restaurants = Restaurant::with(['menus' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->with(['menuItems' => function ($query) {
                        $query->where('is_available', true);
                    }]);
            }])
                ->where('status', true)
                ->get()
                ->toArray();  // Convert to array to ensure we're working with an array

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
}
