<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Passport::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Passport::routes();
        view()->composer('*', function ($view) {
            if (!Auth::check()) {
                return redirect(route('login'));
            }
            $data = [];


            $users = User::leftJoin('restaurant_employees', function ($join) {
                $join->on('restaurant_employees.user_id', '=', 'users.id');
            })
            ->leftJoin('restaurants', function ($join) {
                $join->on('restaurants.id', '=', 'restaurant_employees.restaurant_id')
                     ->orOn('restaurants.owner_id', '=', 'users.id'); // Include owner in employee list
            })
            ->select(
                'users.*',
                'restaurants.name as restaurant_name',
                'restaurants.address as restaurant_address',
                'restaurants.phone_number as restaurant_phone',
                'restaurants.email as restaurant_email',
                'restaurants.website',
                'restaurants.image as restaurant_logo',
                'restaurants.is_approved',
                'restaurants.id as restaurant_id',
                'restaurant_employees.position as employee_role',
                \DB::raw('CASE WHEN users.id = restaurants.owner_id THEN "Owner" ELSE "Employee" END as is_owner')
            )
            ->where(function ($query) {
                // Always include the current user
                $query->where('users.id', auth()->user()->id)
                      ->orWhere(function ($q) {
                          if (auth()->user()->role === 'restaurant_owner') {
                              // Restaurant owners should see their employees
                              $q->where('restaurants.owner_id', auth()->user()->id);
                          } elseif (auth()->user()->role === 'restaurant_employee') {
                              // Employees should see their workmates in the same restaurant
                              $q->whereIn('restaurant_employees.restaurant_id', function ($subquery) {
                                  $subquery->select('restaurant_id')
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
            ->distinct();

        $data['users'] = $users->first();
        session(['userData' => $data]);
            $view->with('data', $data);
        });

    }
}
