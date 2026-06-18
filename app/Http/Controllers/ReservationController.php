<?php

namespace App\Http\Controllers;

use App\Events\ReservationCreated;
use App\Events\ReservationStatusChanged;
use App\Mail\ReservationStatusUpdated;
use App\Models\Notification;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\User;
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
        $isAdmin = \Illuminate\Support\Facades\Auth::user()->role === 'admin';
        $restaurantId = $isAdmin ? null : session('userData')['users']->restaurant_id;

        $reservations = Reservation::select('reservations.*', 'users.first_name', 'users.last_name', 'restaurants.name as restaurant_name')
            ->join('users', 'reservations.user_id', '=', 'users.id')
            ->join('restaurants', 'reservations.restaurant_id', '=', 'restaurants.id')
            ->when(!$isAdmin, fn($q) => $q->where('reservations.restaurant_id', $restaurantId))
            ->orderByRaw("FIELD(reservations.status, 'pending', 'confirmed', 'completed', 'cancelled')")
            ->orderBy('reservations.reservation_time', 'asc')
            ->get();
        return view('manage-reservations.index', compact('reservations', 'isAdmin'));
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

        $reservation = Reservation::with(['user', 'restaurant'])->findOrFail($id);
        $oldStatus = $reservation->status;

        $reservation->status = $request->status;
        $reservation->save();

        // Broadcast status change to client in real-time
        if ($oldStatus !== $reservation->status) {
            event(new ReservationStatusChanged($reservation));

            Notification::create([
                'user_id'       => $reservation->user_id,
                'restaurant_id' => $reservation->restaurant_id,
                'title'         => 'Reservation ' . ucfirst($reservation->status),
                'body'          => 'Your reservation at ' . ($reservation->restaurant->name ?? 'the restaurant') . ' has been ' . $reservation->status . '.',
                'data'          => ['type' => 'reservation_status', 'reservation_id' => $reservation->id, 'status' => $reservation->status],
                'is_read'       => false,
            ]);
        }

        $user = $reservation->user;
        if ($user) {
            try {
                Mail::to($user->email)->send(new ReservationStatusUpdated($reservation));
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
        $user        = auth()->user();
        $reservation = \App\Models\Reservation::with(['user', 'restaurant'])->findOrFail($id);

        // Clients can only see their own reservations
        if ($user->role === 'client' || $user->role === 'Client') {
            if ($reservation->user_id !== $user->id) {
                abort(403);
            }
            return view('client.reservation-detail', compact('reservation'));
        }

        return view('manage-reservations.show', compact('reservation'));
    }
}
