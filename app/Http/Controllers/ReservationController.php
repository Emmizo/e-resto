<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Restaurant;
use Illuminate\Http\Request;
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
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed. Please check your input.'
            ], 422);
        }

        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => $request->status]);

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
        $reservation->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Reservation deleted successfully'
        ]);
    }
}
