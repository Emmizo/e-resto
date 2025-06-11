<?php

namespace App\Http\Controllers;

use App\Events\ReservationCreated;
use App\Mail\ReservationStatusUpdated;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::select('reservations.*', 'users.first_name', 'users.last_name')
            ->join('users', 'reservations.user_id', '=', 'users.id')
            ->where('reservations.restaurant_id', session('userData')['users']->restaurant_id)
            ->orderBy('reservations.reservation_time', 'desc')
            ->get();
        return view('manage-reservations.index', compact('reservations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed. Please check your input.'
            ], 422);
        }

        $reservation = Reservation::with('user')->findOrFail($id);
        $oldStatus = $reservation->status;

        $reservation->status = $request->status;
        $reservation->save();

        // Broadcast the reservation update
        event(new ReservationCreated($reservation));

        $user = $reservation->user;
        $fcmToken = $user->fcm_token;
        if ($fcmToken) {
            FcmService::send(
                $fcmToken,
                'Reservation Update',
                "Your reservation at {$reservation->restaurant->name} is now {$reservation->status}!",
                [
                    'type' => 'reservation_status',
                    'reservation_id' => (string) $reservation->id,
                    'status' => $reservation->status,
                ]
            );
        }

        if ($oldStatus !== $reservation->status && $reservation->user) {
            try {
                Mail::to($reservation->user->email)->send(new ReservationStatusUpdated($reservation));
            } catch (\Exception $e) {
                \Log::error('Failed to send reservation status update email: ' . $e->getMessage());
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Reservation status updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        // Broadcast the reservation deletion
        event(new ReservationCreated($reservation));
        $reservation->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Reservation deleted successfully'
        ]);
    }

    /**
     * Display the specified reservation.
     */
    public function show($id)
    {
        $reservation = \App\Models\Reservation::with(['user', 'restaurant'])->findOrFail($id);
        return view('manage-reservations.show', compact('reservation'));
    }
}
