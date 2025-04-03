<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dashboardData = [
            'total_users' => \App\Models\User::leftJoin('restaurant_employees', function ($join) {
                $join->on('restaurant_employees.user_id', '=', 'users.id');
            })
                ->leftJoin('restaurants', function ($join) {
                    $join
                        ->on('restaurants.id', '=', 'restaurant_employees.restaurant_id')
                        ->orOn('restaurants.owner_id', '=', 'users.id');  // Include owner in employee list
                })
                ->where(function ($query) {
                    // Always include the current user
                    $query
                        ->where('users.id', auth()->user()->id)
                        ->orWhere(function ($q) {
                            if (auth()->user()->role === 'restaurant_owner' || 'restaurant_employees.position' === 'manager') {
                                // Restaurant owners should see their employees
                                $q->where('restaurants.owner_id', auth()->user()->id);
                            } elseif (auth()->user()->role === 'restaurant_employee') {
                                // Employees should see their workmates in the same restaurant
                                $q
                                    ->whereIn('restaurant_employees.restaurant_id', function ($subquery) {
                                        $subquery
                                            ->select('restaurant_id')
                                            ->from('restaurant_employees')
                                            ->where('user_id', auth()->user()->id);
                                    })
                                    ->where('users.id', '!=', auth()->user()->id);
                            } elseif (auth()->user()->role === 'admin') {
                                // Admins should see all users associated with restaurants
                                $q->whereNotNull('restaurants.owner_id');
                            }
                        });
                })
                ->count(),
            'total_restaurants' => \App\Models\Restaurant::count(),
            'total_orders' => \App\Models\Order::count(),
            'total_revenue' => \App\Models\Order::sum('total_amount'),
        ];
        return view('dashboard', compact('dashboardData'));
        //
    }
}
