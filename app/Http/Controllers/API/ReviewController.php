<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Reviews",
 *     description="API Endpoints for restaurant reviews"
 * )
 */
class ReviewController extends Controller
{
    /**
     * @OA\Post(
     *     path="/reviews",
     *     summary="Create a new review",
     *     tags={"Reviews"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"restaurant_id", "rating", "comment"},
     *             @OA\Property(property="restaurant_id", type="integer", example=1),
     *             @OA\Property(property="rating", type="number", format="float", minimum=1, maximum=5, example=4.5),
     *             @OA\Property(property="comment", type="string", example="Great food and service!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Review created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Review submitted successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="restaurant_id", type="integer", example=1),
     *                 @OA\Property(property="rating", type="number", format="float", example=4.5),
     *                 @OA\Property(property="comment", type="string", example="Great food and service!"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Restaurant not found")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $restaurant = Restaurant::find($request->restaurant_id);
        if (!$restaurant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Restaurant not found'
            ], 404);
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'restaurant_id' => $request->restaurant_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Review submitted successfully',
            'data' => $review
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/restaurants/{restaurant}/reviews",
     *     summary="Get reviews for a specific restaurant",
     *     tags={"Reviews"},
     *     @OA\Parameter(
     *         name="restaurant",
     *         in="path",
     *         required=true,
     *         description="Restaurant ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of reviews",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="restaurant_id", type="integer", example=1),
     *                 @OA\Property(property="rating", type="number", format="float", example=4.5),
     *                 @OA\Property(property="comment", type="string", example="Great food and service!"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe")
     *                 )
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Restaurant not found")
     *         )
     *     )
     * )
     */
    public function restaurantReviews(Restaurant $restaurant)
    {
        $reviews = Review::with('user:id,name')
            ->where('restaurant_id', $restaurant->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $reviews
        ]);
    }

    /**
     * @OA\Get(
     *     path="/reviews/user",
     *     summary="Get authenticated user's reviews",
     *     tags={"Reviews"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of user's reviews",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="restaurant_id", type="integer", example=1),
     *                 @OA\Property(property="rating", type="number", format="float", example=4.5),
     *                 @OA\Property(property="comment", type="string", example="Great food and service!"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="restaurant", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Restaurant Name")
     *                 )
     *             ))
     *         )
     *     )
     * )
     */
    public function userReviews()
    {
        $reviews = Review::with('restaurant:id,name')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $reviews
        ]);
    }
}
