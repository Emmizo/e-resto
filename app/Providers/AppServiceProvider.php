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
            'restaurants.image as restaurant_logo',
            'restaurants.website',
            'restaurants.is_approved',
            'restaurants.id as restaurant_id',
            'restaurant_employees.position as employee_role',
            \DB::raw('CASE WHEN users.id = restaurants.owner_id THEN "Owner" ELSE "" END as is_owner')
        )
        ->when(auth()->user()->role !== 'admin', function ($query) {
            $query->where(function ($q) {
                if (auth()->user()->role === 'restaurant_owner') {
                    // Restaurant owners should see only their employees
                    $q->where('restaurants.owner_id', auth()->user()->id);
                } else {
                    // Employees should only see their workmates (employees of the same restaurant)
                    $q->whereIn('restaurant_employees.restaurant_id', function ($subquery) {
                        $subquery->select('restaurant_id')
                            ->from('restaurant_employees')
                            ->where('user_id', auth()->user()->id);
                    })->where('users.id', '!=', auth()->user()->id);
                }
            });
        })
        ->when(auth()->user()->role === 'admin', function ($query) {
            // Admins should only see restaurant owners
            $query->whereNotNull('restaurants.owner_id');
        });
        $data['users'] = $users->first();

            $view->with('data', $data);
        });
    }
}
