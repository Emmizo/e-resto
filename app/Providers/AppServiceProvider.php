<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        Schema::defaultStringLength(191);

        // Increase memory limit for this request
        ini_set('memory_limit', '512M');

        // Optimize database connection
        DB::connection()->disableQueryLog();
        // $this->registerPolicies();

        // / Passport::routes();
        /*   Passport::hashClientSecrets();
          Passport::tokensExpireIn(now()->addDays(15));
          Passport::refreshTokensExpireIn(now()->addDays(30));
          Passport::personalAccessTokensExpireIn(now()->addMonths(6)); */

        \Blade::if('hasrestaurantpermission', function ($permission, $restaurantId = null) {
            return auth()->check() &&
                auth()->user()->hasRestaurantPermission($permission, $restaurantId);
        });

        view()->composer('*', function ($view) {
            if (!Auth::check()) {
                return redirect(route('login'));
            }
            $data = [];

            $users = User::leftJoin('restaurant_employees', function ($join) {
                $join->on('restaurant_employees.user_id', '=', 'users.id');
            })
                ->leftJoin('restaurants', function ($join) {
                    $join
                        ->on('restaurants.id', '=', 'restaurant_employees.restaurant_id')
                        ->orOn('restaurants.owner_id', '=', 'users.id');  // Include owner in employee list
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
                    $query
                        ->where('users.id', auth()->user()->id)
                        ->orWhere(function ($q) {
                            if (auth()->user()->role === 'restaurant_owner') {
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
                ->distinct();

            $data['users'] = $users->first();
            session(['userData' => $data]);
            $view->with('data', $data);
        });
    }
}
