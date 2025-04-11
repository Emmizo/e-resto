<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Reservations",
 *     description="API Endpoints for managing restaurant reservations"
 * )
 */
class ReservationController extends Controller
{
    /**
     * Display a listing of reservations.
     *
     * @OA\Get(
     *     path="/reservations",
     *     summary="List all reservations",
     *     tags={"Reservations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter reservations by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "confirmed", "cancelled", "completed"})
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Filter reservations by date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of reservations",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Reservations retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", format="int64", example=1),
     *                     @OA\Property(property="user_id", type="integer", format="int64", example=1),
     *                     @OA\Property(property="restaurant_id", type="integer", format="int64", example=1),
     *                     @OA\Property(property="reservation_time", type="string", format="date-time", example="2024-04-15 19:00:00"),
     *                     @OA\Property(property="number_of_people", type="integer", example=4),
     *                     @OA\Property(property="special_requests", type="string", example="Window seat preferred"),
     *                     @OA\Property(property="status", type="string", enum={"pending", "confirmed", "cancelled", "completed"}, example="pending"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(
     *                         property="user",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", format="int64", example=1),
     *                         @OA\Property(property="name", type="string", example="John Doe"),
     *                         @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *                     ),
     *                     @OA\Property(
     *                         property="restaurant",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", format="int64", example=1),
     *                         @OA\Property(property="name", type="string", example="Fine Dining Restaurant"),
     *                         @OA\Property(property="address", type="string", example="123 Main St, City")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $query = Reservation::with(['user', 'restaurant'])
                ->where('user_id', $user->id);

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('date')) {
                $query->whereDate('reservation_time', $request->date);
            }

            $reservations = $query->orderBy('reservation_time', 'desc')->get();

            if ($reservations->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No reservations found',
                    'data' => []
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Reservations retrieved successfully',
                'data' => $reservations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve reservations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created reservation.
     *
     * @OA\Post(
     *     path="/reservations",
     *     summary="Create a new reservation",
     *     tags={"Reservations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"restaurant_id", "reservation_time", "number_of_guests"},
     *             @OA\Property(property="restaurant_id", type="integer", format="int64", example=1),
     *             @OA\Property(property="reservation_time", type="string", format="date-time", example="2024-04-15 19:00:00"),
     *             @OA\Property(property="number_of_guests", type="integer", example=4),
     *             @OA\Property(property="special_requests", type="string", example="Window seat preferred")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reservation created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Reservation created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="user_id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="restaurant_id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="reservation_time", type="string", format="date-time", example="2024-04-15 19:00:00"),
     *                 @OA\Property(property="number_of_guests", type="integer", example=4),
     *                 @OA\Property(property="special_requests", type="string", example="Window seat preferred"),
     *                 @OA\Property(property="status", type="string", enum={"pending", "confirmed", "cancelled", "completed"}, example="pending"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input data"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'reservation_time' => 'required',
            'number_of_guests' => 'required|integer|min:1|max:20',
            'special_requests' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $reservation = Reservation::create([
                'user_id' => auth()->id(),
                'restaurant_id' => $request->restaurant_id,
                'reservation_time' => $request->reservation_time,
                'number_of_people' => $request->number_of_guests,
                'special_requests' => $request->special_requests,
                'status' => 'pending'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Reservation created successfully',
                'data' => $reservation->load(['user', 'restaurant'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create reservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified reservation.
     *
     * @OA\Get(
     *     path="/reservations/{id}",
     *     summary="Get reservation details",
     *     tags={"Reservations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Reservation ID",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Reservation retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="user_id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="restaurant_id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="reservation_time", type="string", format="date-time", example="2024-04-15 19:00:00"),
     *                 @OA\Property(property="number_of_people", type="integer", example=4),
     *                 @OA\Property(property="special_requests", type="string", example="Window seat preferred"),
     *                 @OA\Property(property="status", type="string", enum={"pending", "confirmed", "cancelled", "completed"}, example="pending"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", format="int64", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *                 ),
     *                 @OA\Property(
     *                     property="restaurant",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", format="int64", example=1),
     *                     @OA\Property(property="name", type="string", example="Fine Dining Restaurant"),
     *                     @OA\Property(property="address", type="string", example="123 Main St, City")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $reservation = Reservation::with(['user', 'restaurant'])->find($id);

            if (!$reservation) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Reservation not found'
                ], 404);
            }

            if ($reservation->user_id !== auth()->id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access'
                ], 403);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Reservation retrieved successfully',
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve reservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified reservation.
     *
     * @OA\Put(
     *     path="/reservations/{id}",
     *     summary="Update reservation details",
     *     tags={"Reservations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Reservation ID",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="reservation_time", type="string", format="date-time", example="2024-04-15 19:00:00"),
     *             @OA\Property(property="number_of_people", type="integer", example=4),
     *             @OA\Property(property="special_requests", type="string", example="Window seat preferred"),
     *             @OA\Property(property="status", type="string", enum={"pending", "confirmed", "cancelled", "completed"}, example="confirmed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Reservation updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="user_id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="restaurant_id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="reservation_time", type="string", format="date-time", example="2024-04-15 19:00:00"),
     *                 @OA\Property(property="number_of_people", type="integer", example=4),
     *                 @OA\Property(property="special_requests", type="string", example="Window seat preferred"),
     *                 @OA\Property(property="status", type="string", enum={"pending", "confirmed", "cancelled", "completed"}, example="confirmed"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input data"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'reservation_time' => 'nullable|date',
            'number_of_guests' => 'nullable|integer|min:1|max:20',
            'special_requests' => 'nullable|string|max:500',
            'status' => 'nullable|in:pending,confirmed,cancelled,completed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $reservation = Reservation::where('user_id', auth()->id())
                ->find($id);

            if (!$reservation) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Reservation not found'
                ], 404);
            }

            $reservation->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Reservation updated successfully',
                'data' => $reservation->load(['user', 'restaurant'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update reservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified reservation.
     *
     * @OA\Delete(
     *     path="/reservations/{id}",
     *     summary="Delete a reservation",
     *     tags={"Reservations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Reservation ID",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Reservation deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $reservation = Reservation::where('user_id', auth()->id())
                ->find($id);

            if (!$reservation) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Reservation not found'
                ], 404);
            }

            $reservation->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Reservation deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete reservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
