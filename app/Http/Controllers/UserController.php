<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::leftJoin('restaurants', function($join) {
            $join->on('users.id', '=', 'restaurants.owner_id');
        })
        ->leftJoin('restaurant_employees', function($join) {
            $join->on('restaurant_employees.restaurant_id', '=', 'restaurants.id')
                 ->on('restaurant_employees.user_id', '=', 'users.id');
        })
        ->select(
            'users.*',
            'restaurants.name as restaurant_name',
            'restaurants.address as restaurant_address',
            'restaurants.phone_number as restaurant_phone',
            'restaurants.email as restaurant_email',
            'restaurants.website',
            'restaurant_employees.position as employee_role',
            \DB::raw('CASE WHEN users.id = restaurants.owner_id THEN "Owner" ELSE "" END as is_owner')
        )
        ->when(auth()->user()->role !== 'admin', function ($query) {
            $query->where(function ($q) {
                if (auth()->user()->role === 'restaurant_owner') {
                    // Display only employees of the owner's restaurant
                    $q->where('restaurants.owner_id', auth()->user()->id);
                } else {
                    // Employees should only see their own data
                    $q->where('restaurant_employees.user_id', auth()->user()->id);
                }
            });
        })
        ->get();

    return view('manage-users.index', ['users' => $users]);
        //
    }


}
