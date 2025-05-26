<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\RestaurantEmployee;
use App\Services\FirebaseService;
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
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

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
     *             @OA\Property(property="special_requests", type="string", example="Window seat preferred"),
     *             @OA\Property(property="phone_number", type="string", example="123-456-7890")
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
            'number_of_guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
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
                'phone_number' => $request->phone_number,
                'status' => 'pending'
            ]);

            $restaurant = $reservation->restaurant;
            if ($restaurant && $restaurant->email) {
                try {
                    \Mail::to($restaurant->email)->send(new \App\Mail\NewReservationNotification($reservation));
                    \Log::info('Reservation email sent to: ' . $restaurant->email);
                } catch (\Exception $e) {
                    \Log::error('Reservation email failed: ' . $e->getMessage());
                }
            }

            // Send FCM notification to managers, chefs, waiters, and the restaurant owner
            $restaurantStaff = RestaurantEmployee::join('users', 'restaurant_employees.user_id', '=', 'users.id')
                ->where('restaurant_employees.restaurant_id', $request->restaurant_id)
                ->whereIn('restaurant_employees.position', ['manager', 'chef', 'waiter'])
                ->whereNotNull('users.fcm_token')
                ->get();

            // Get the restaurant owner
            $owner = null;
            if ($restaurant && $restaurant->owner_id) {
                $owner = \App\Models\User::where('id', $restaurant->owner_id)
                    ->where('role', 'restaurant_owner')
                    ->whereNotNull('fcm_token')
                    ->first();
            }

            \Log::info('Reservation FCM: Staff to notify', [
                'restaurant_id' => $request->restaurant_id,
                'staff_ids' => $restaurantStaff->pluck('id')->toArray(),
                'owner_id' => $owner ? $owner->id : null,
                'count' => $restaurantStaff->count() + ($owner ? 1 : 0)
            ]);

            $notifiedUserIds = $restaurantStaff->pluck('user_id')->toArray();
            foreach ($restaurantStaff as $staff) {
                $title = 'New Reservation Received';
                $body = "New reservation #{$reservation->id} for {$reservation->number_of_people} people at {$reservation->reservation_time}.";
                $data = [
                    'reservation_id' => $reservation->id,
                    'type' => 'new_reservation'
                ];

                $this->firebaseService->sendNotification(
                    $staff->fcm_token,
                    $title,
                    $body,
                    $data
                );

                \Log::info('Reservation FCM: Sent notification', [
                    'staff_user_id' => $staff->id,
                    'fcm_token' => $staff->fcm_token,
                    'reservation_id' => $reservation->id
                ]);
            }

            // Notify owner if not already notified
            if ($owner && !in_array($owner->id, $notifiedUserIds)) {
                $title = 'New Reservation Received';
                $body = "New reservation #{$reservation->id} for {$reservation->number_of_people} people at {$reservation->reservation_time}.";
                $data = [
                    'reservation_id' => $reservation->id,
                    'type' => 'new_reservation'
                ];
                $this->firebaseService->sendNotification(
                    $owner->fcm_token,
                    $title,
                    $body,
                    $data
                );
                \Log::info('Reservation FCM: Sent notification to owner', [
                    'owner_id' => $owner->id,
                    'fcm_token' => $owner->fcm_token,
                    'reservation_id' => $reservation->id
                ]);
            }

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
     *             @OA\Property(property="status", type="string", enum={"pending", "confirmed", "cancelled", "completed"}, example="confirmed"),
     *             @OA\Property(property="phone_number", type="string", example="123-456-7890")
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
        \Log::info('API ReservationController@update called', ['reservation_id' => $id, 'user_id' => auth()->id(), 'request' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'reservation_time' => 'nullable|date',
            'number_of_guests' => 'nullable|integer|min:1|max:20',
            'special_requests' => 'nullable|string|max:500',
            'status' => 'nullable|in:pending,confirmed,cancelled,completed',
            'phone_number' => 'nullable|string|max:20',
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

            $oldStatus = $reservation->getOriginal('status');
            if ($request->has('status')) {
                $reservation->status = $request->status;
            }
            $updateData = [];
            if ($request->has('reservation_time')) {
                $updateData['reservation_time'] = $request->reservation_time;
            }
            if ($request->has('number_of_guests')) {
                $updateData['number_of_people'] = $request->number_of_guests;
            }
            if ($request->has('special_requests')) {
                $updateData['special_requests'] = $request->special_requests;
            }
            if ($request->has('phone_number')) {
                $updateData['phone_number'] = $request->phone_number;
            }

            $reservation->update($updateData);

            if ($oldStatus !== $reservation->status && $reservation->user) {
                try {
                    \Mail::to($reservation->user->email)->send(new \App\Mail\ReservationStatusUpdated($reservation));
                } catch (\Exception $e) {
                    \Log::error('Failed to send reservation status update email: ' . $e->getMessage());
                }
                // Send FCM and persistent notification
                if ($reservation->user->fcm_token) {
                    $title = 'Reservation Status Updated';
                    $body = "Your reservation #{$reservation->id} status has been updated to: " . ucfirst($reservation->status);
                    $data = [
                        'reservation_id' => $reservation->id,
                        'restaurant_id' => $reservation->restaurant_id,
                        'status' => $reservation->status,
                        'type' => 'reservation_status',
                    ];
                    $this->firebaseService->sendNotification(
                        $reservation->user->fcm_token,
                        $title,
                        $body,
                        $data,
                        $reservation->user_id
                    );
                }
            }

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

    /**
     * Cancel a reservation.
     *
     * @OA\Post(
     *     path="/reservations/{id}/cancel",
     *     summary="Cancel a reservation",
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
     *         description="Reservation cancelled successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Reservation cancelled successfully"),
     *             @OA\Property(property="data", type="object")
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
    public function cancel(Request $request, $id): JsonResponse
    {
        $reservation = Reservation::where('user_id', auth()->id())->find($id);
        if (!$reservation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Reservation not found'
            ], 404);
        }
        if ($reservation->status === 'cancelled') {
            return response()->json([
                'status' => 'error',
                'message' => 'Reservation is already cancelled.'
            ], 400);
        }
        $reservation->status = 'cancelled';
        $reservation->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Reservation cancelled successfully',
            'data' => $reservation
        ]);
    }
}
