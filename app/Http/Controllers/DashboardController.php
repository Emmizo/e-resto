<?php

namespace App\Http\Controllers;

use App\Events\ServiceStatusUpdated;
use App\Mail\RestaurantApprovedMail;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\PromoBanner;
use App\Models\Restaurant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the restaurant ID based on user role
        $restaurantId = null;
        $user = Auth::user();

        if ($user->role == 'admin') {
            // Admin can see all data
            $restaurantId = null;
        } else {
            // Other roles can only see their restaurant data
            $restaurantId = session('userData')['users']->restaurant_id ?? null;
        }

        // Get date range from request or default to today
        $dateRange = $request->get('range', 'today');
        $startDate = null;
        $endDate = Carbon::now();

        switch ($dateRange) {
            case 'today':
                $startDate = Carbon::today();
                break;
            case 'week':
                $startDate = Carbon::now()->subWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->subMonth();
                break;
        }

        // Get activity data based on date range
        $orderActivityData = collect([]);
        $reservationActivityData = collect([]);
        $activityLabels = collect([]);

        if ($dateRange === 'today') {
            // For today, show hourly data
            for ($i = 0; $i < 24; $i++) {
                $hour = Carbon::now()->startOfDay()->addHours($i);
                $activityLabels->push($hour->format('H:00'));

                // Get orders for this hour
                $orderQuery = Order::whereBetween('created_at', [$hour, $hour->copy()->addHour()]);
                if ($restaurantId) {
                    $orderQuery->where('restaurant_id', $restaurantId);
                }
                $orderActivityData->push($orderQuery->count());

                // Get reservations for this hour
                $reservationQuery = \App\Models\Reservation::whereBetween('created_at', [$hour, $hour->copy()->addHour()]);
                if ($restaurantId) {
                    $reservationQuery->where('restaurant_id', $restaurantId);
                }
                $reservationActivityData->push($reservationQuery->count());
            }
        } else {
            // For week/month, show daily data
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                $activityLabels->push($currentDate->format('M d'));

                // Get orders for this day
                $orderQuery = Order::whereDate('created_at', $currentDate);
                if ($restaurantId) {
                    $orderQuery->where('restaurant_id', $restaurantId);
                }
                $orderActivityData->push($orderQuery->count());

                // Get reservations for this day
                $reservationQuery = \App\Models\Reservation::whereDate('created_at', $currentDate);
                if ($restaurantId) {
                    $reservationQuery->where('restaurant_id', $restaurantId);
                }
                $reservationActivityData->push($reservationQuery->count());

                $currentDate->addDay();
            }
        }

        // Get order types distribution for the selected date range
        $orderTypesQuery = Order::when($restaurantId, function ($query) use ($restaurantId) {
            return $query->where('restaurant_id', $restaurantId);
        })->when($startDate, function ($query) use ($startDate, $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        });

        $orderTypes = [
            'dine_in' => $orderTypesQuery->clone()->where('order_type', 'dine_in')->count(),
            'takeaway' => $orderTypesQuery->clone()->where('order_type', 'takeaway')->count(),
            'delivery' => $orderTypesQuery->clone()->where('order_type', 'delivery')->count()
        ];

        // Get daily orders count for the selected date range
        $dailyOrders = Order::when($restaurantId, function ($query) use ($restaurantId) {
            return $query->where('restaurant_id', $restaurantId);
        })->when($startDate, function ($query) use ($startDate, $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        })->count();

        // Get top menu items for the selected date range
        $topMenuItems = MenuItem::join('menus', 'menu_items.menu_id', '=', 'menus.id')
            ->when($restaurantId, function ($query) use ($restaurantId) {
                return $query->where('menus.restaurant_id', $restaurantId);
            })
            ->select('menu_items.*')
            ->selectRaw('COUNT(order_items.id) as total_orders')
            ->selectRaw('SUM(order_items.price) as total_revenue')
            ->leftJoin('order_items', 'menu_items.id', '=', 'order_items.menu_item_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->when($startDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('orders.created_at', [$startDate, $endDate]);
            })
            ->where('orders.status', 'completed')
            ->groupBy('menu_items.id', 'menu_items.name', 'menu_items.category', 'menu_items.price', 'menu_items.description', 'menu_items.image', 'menus.restaurant_id', 'menu_items.created_at', 'menu_items.updated_at')
            ->orderBy('total_orders', 'desc')
            ->limit(4)
            ->get();

        // Get users based on role
        $users = collect();
        if ($user->role == 'admin') {
            // Admin sees all users
            $users = User::all();
        } else {
            // Restaurant owners/managers see their employees
            $users = User::whereHas('restaurantEmployees', function ($query) use ($restaurantId) {
                $query->where('restaurant_id', $restaurantId);
            })->get();
        }

        // Get restaurant ratings (admin sees all, owners see their own)
        $topRestaurants = collect();
        if ($user->role == 'admin') {
            // Admin sees all restaurants with ratings
            $topRestaurants = Restaurant::select('restaurants.id', 'restaurants.name', 'restaurants.cuisine_id')
                ->leftJoin('reviews', 'restaurants.id', '=', 'reviews.restaurant_id')
                ->selectRaw('AVG(reviews.rating) as rating')
                ->selectRaw('COUNT(reviews.id) as review_count')
                ->groupBy('restaurants.id', 'restaurants.name', 'restaurants.cuisine_id')
                ->having('review_count', '>', 0)
                ->orderBy('rating', 'desc')
                ->limit(4)
                ->with('cuisine')
                ->get();
        } else {
            // Restaurant owners see their own restaurant's rating
            $topRestaurants = Restaurant::select('restaurants.id', 'restaurants.name', 'restaurants.cuisine_id')
                ->leftJoin('reviews', 'restaurants.id', '=', 'reviews.restaurant_id')
                ->selectRaw('AVG(reviews.rating) as rating')
                ->selectRaw('COUNT(reviews.id) as review_count')
                ->where('restaurants.id', $restaurantId)
                ->groupBy('restaurants.id', 'restaurants.name', 'restaurants.cuisine_id')
                ->with('cuisine')
                ->get();
        }

        // Get recent orders based on user role
        $recentOrders = collect();
        if ($user->role == 'admin') {
            // Admin sees recent orders from all restaurants
            $recentOrders = Order::with('restaurant')
                ->when($startDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->latest()
                ->limit(10)
                ->get();
        } else {
            // Restaurant users see only their restaurant's orders
            $recentOrders = Order::with('restaurant')
                ->where('restaurant_id', $restaurantId)
                ->when($startDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->latest()
                ->limit(10)
                ->get();
        }

        // Build dashboard data with restaurant-specific filters
        $dashboardData = [
            'total_users' => $users->count(),
            'users' => $users,
            'total_restaurants' => $restaurantId ? 1 : Restaurant::count(),
            'total_orders' => Order::when($restaurantId, function ($query) use ($restaurantId) {
                return $query->where('restaurant_id', $restaurantId);
            })->when($startDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count(),
            'total_revenue' => Order::when($restaurantId, function ($query) use ($restaurantId) {
                return $query->where('restaurant_id', $restaurantId);
            })->when($startDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })->sum('total_amount'),
            'activity_labels' => $activityLabels->toArray(),
            'order_activity_data' => $orderActivityData->toArray(),
            'reservation_activity_data' => $reservationActivityData->toArray(),
            'recommendation_data' => array_values($orderTypes),
            'top_restaurants' => $topRestaurants,
            'daily_recommendations' => $dailyOrders,
            'recent_orders' => $recentOrders,
            'reservations_today' => \App\Models\Reservation::when($restaurantId, function ($query) use ($restaurantId) {
                return $query->where('restaurant_id', $restaurantId);
            })->when($startDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count(),
            'top_menu_items' => $topMenuItems,
            'current_range' => $dateRange,
            'promo_banners' => PromoBanner::when($restaurantId, function ($query) use ($restaurantId) {
                return $query->where('restaurant_id', $restaurantId);
            })->latest()->get()
        ];

        $restaurant = null;
        if ($user->role !== 'admin' && $restaurantId) {
            $restaurant = Restaurant::find($restaurantId);
        }

        return view('dashboard', compact('dashboardData', 'restaurant'));
    }

    /**
     * List all restaurants for admin approval
     */
    public function listRestaurants()
    {
        $restaurants = \App\Models\Restaurant::with('owner')->orderByDesc('created_at')->get();
        return view('admin.restaurants.index', compact('restaurants'));
    }

    /**
     * Approve or unapprove a restaurant (AJAX or POST)
     */
    public function approveRestaurant(Request $request, $id)
    {
        $restaurant = \App\Models\Restaurant::with('owner')->findOrFail($id);
        $restaurant->is_approved = $request->input('is_approved', true);
        $restaurant->save();
        // Send email to owner on approval/unapproval
        if ($restaurant->owner && $restaurant->owner->email) {
            Mail::to($restaurant->owner->email)->send(new RestaurantApprovedMail($restaurant, $restaurant->is_approved));
        }
        return response()->json(['status' => 'success', 'is_approved' => $restaurant->is_approved]);
    }

    /**
     * Toggle accepts_reservations or accepts_delivery for the owner's restaurant
     */
    public function toggleService(Request $request)
    {
        $user = Auth::user();
        $restaurantId = session('userData')['users']->restaurant_id ?? null;
        if (!$restaurantId) {
            return response()->json(['status' => 'error', 'message' => 'No restaurant found.'], 404);
        }
        $restaurant = Restaurant::find($restaurantId);
        if (!$restaurant) {
            return response()->json(['status' => 'error', 'message' => 'Restaurant not found.'], 404);
        }
        $type = $request->input('type');
        if ($type === 'reservations') {
            $restaurant->accepts_reservations = !$restaurant->accepts_reservations;
            $restaurant->save();
            // Broadcast the service status update
            event(new ServiceStatusUpdated('reservations', $restaurant->accepts_reservations, $restaurant->id));
            return response()->json(['status' => 'success', 'value' => $restaurant->accepts_reservations]);
        } elseif ($type === 'delivery') {
            $restaurant->accepts_delivery = !$restaurant->accepts_delivery;
            $restaurant->save();
            // Broadcast the service status update
            event(new ServiceStatusUpdated('delivery', $restaurant->accepts_delivery, $restaurant->id));
            return response()->json(['status' => 'success', 'value' => $restaurant->accepts_delivery]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid type.'], 400);
        }
    }

    public function getChartData()
    {
        $range = request('range', 'today');
        $data = $this->getDashboardData($range);

        return response()->json([
            'activity_labels' => $data['activity_labels'],
            'order_activity_data' => $data['order_activity_data'],
            'reservation_activity_data' => $data['reservation_activity_data']
        ]);
    }
}
