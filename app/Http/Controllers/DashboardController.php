<?php

namespace App\Http\Controllers;

use App\Events\ServiceStatusUpdated;
use App\Mail\RestaurantApprovedMail;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\PromoBanner;
use App\Models\Restaurant;
use App\Models\Review;
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
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        // For non-admin users, use restaurant_id. Use -1 (matches nothing) if not set,
        // so queries never fall through to showing all-restaurant data.
        $restaurantId = $isAdmin ? null : (session('userData')['users']->restaurant_id ?? -1);

        $dateRange = $request->get('range', 'year');
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
            case 'year':
                $startDate = Carbon::now()->subYear()->startOfMonth();
                break;
            case '2_years':
                $startDate = Carbon::now()->subYears(2)->startOfMonth();
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

                $orderQuery = Order::whereBetween('created_at', [$hour, $hour->copy()->addHour()]);
                if ($restaurantId) {
                    $orderQuery->where('restaurant_id', $restaurantId);
                }
                $orderActivityData->push($orderQuery->count());

                $reservationQuery = \App\Models\Reservation::whereBetween('created_at', [$hour, $hour->copy()->addHour()]);
                if ($restaurantId) {
                    $reservationQuery->where('restaurant_id', $restaurantId);
                }
                $reservationActivityData->push($reservationQuery->count());
            }
        } elseif ($dateRange === 'year' || $dateRange === '2_years') {
            // Show monthly data for year/2_years ranges
            $currentMonth = $startDate->copy()->startOfMonth();
            $lastMonth = $endDate->copy()->startOfMonth();

            while ($currentMonth <= $lastMonth) {
                $monthStart = $currentMonth->copy()->startOfMonth();
                $monthEnd = $currentMonth->copy()->endOfMonth();
                $activityLabels->push($currentMonth->format('M Y'));

                $orderQuery = Order::whereBetween('created_at', [$monthStart, $monthEnd]);
                if ($restaurantId) {
                    $orderQuery->where('restaurant_id', $restaurantId);
                }
                $orderActivityData->push($orderQuery->count());

                $reservationQuery = \App\Models\Reservation::whereBetween('created_at', [$monthStart, $monthEnd]);
                if ($restaurantId) {
                    $reservationQuery->where('restaurant_id', $restaurantId);
                }
                $reservationActivityData->push($reservationQuery->count());

                $currentMonth->addMonth();
            }
        } else {
            // For week/month, show daily data
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                $activityLabels->push($currentDate->format('M d'));

                $orderQuery = Order::whereDate('created_at', $currentDate);
                if ($restaurantId) {
                    $orderQuery->where('restaurant_id', $restaurantId);
                }
                $orderActivityData->push($orderQuery->count());

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
            'dine_in'  => $orderTypesQuery->clone()->where('order_type', 'dine_in')->count(),
            'takeaway' => $orderTypesQuery->clone()->where('order_type', 'takeaway')->count(),
            'delivery' => $orderTypesQuery->clone()->where('order_type', 'delivery')->count(),
        ];

        $orderTypeRevenue = [
            'dine_in'  => (float) $orderTypesQuery->clone()->where('order_type', 'dine_in')->sum('total_amount'),
            'takeaway' => (float) $orderTypesQuery->clone()->where('order_type', 'takeaway')->sum('total_amount'),
            'delivery' => (float) $orderTypesQuery->clone()->where('order_type', 'delivery')->sum('total_amount'),
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
            ->select('menu_items.*', 'menus.restaurant_id as menu_restaurant_id')
            ->selectRaw('COALESCE(COUNT(order_items.id), 0) as total_orders')
            ->selectRaw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as total_revenue')
            ->leftJoin('order_items', 'menu_items.id', '=', 'order_items.menu_item_id')
            ->leftJoin('orders', function ($join) use ($startDate, $endDate) {
                $join->on('order_items.order_id', '=', 'orders.id');
                if ($startDate) {
                    $join->whereBetween('orders.created_at', [$startDate, $endDate]);
                }
            })
            ->groupBy('menu_items.id', 'menu_items.name', 'menu_items.category', 'menu_items.price', 'menu_items.description', 'menu_items.image', 'menus.restaurant_id', 'menu_items.created_at', 'menu_items.updated_at')
            ->orderByDesc('total_orders')
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
            $topRestaurants = Restaurant::select('restaurants.id', 'restaurants.name', 'restaurants.cuisine_id')
                ->leftJoin('reviews', 'restaurants.id', '=', 'reviews.restaurant_id')
                ->selectRaw('AVG(reviews.rating) as rating')
                ->selectRaw('COUNT(reviews.id) as review_count')
                ->groupBy('restaurants.id', 'restaurants.name', 'restaurants.cuisine_id')
                ->having('review_count', '>', 0)
                ->orderBy('rating', 'desc')
                ->limit(4)
                ->with(['cuisine', 'reviews.user'])
                ->get();
        } else {
            $topRestaurants = Restaurant::select('restaurants.id', 'restaurants.name', 'restaurants.cuisine_id')
                ->leftJoin('reviews', 'restaurants.id', '=', 'reviews.restaurant_id')
                ->selectRaw('AVG(reviews.rating) as rating')
                ->selectRaw('COUNT(reviews.id) as review_count')
                ->where('restaurants.id', $restaurantId)
                ->groupBy('restaurants.id', 'restaurants.name', 'restaurants.cuisine_id')
                ->with(['cuisine', 'reviews.user'])
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
            'order_type_revenue' => array_values($orderTypeRevenue),
            'order_types_raw' => $orderTypes,
            'order_type_revenue_raw' => $orderTypeRevenue,
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
            'promo_banners' => $user->role === 'admin' ? collect() : PromoBanner::where('restaurant_id', $restaurantId)->latest()->get(),
            'reservation_status_counts' => [
                'pending' => \App\Models\Reservation::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))->where('status', 'pending')->count(),
                'confirmed' => \App\Models\Reservation::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))->where('status', 'confirmed')->count(),
                'cancelled' => \App\Models\Reservation::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))->where('status', 'cancelled')->count(),
                'completed' => \App\Models\Reservation::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))->where('status', 'completed')->count(),
            ],
            'revenue_by_day' => (function() use ($restaurantId) {
                $rows = Order::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
                    ->whereBetween('created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
                    ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
                    ->groupByRaw('DATE(created_at)')
                    ->pluck('revenue', 'date');
                return collect(range(6, 0))->map(fn($d) => (float)($rows[Carbon::now()->subDays($d)->toDateString()] ?? 0))->values()->toArray();
            })(),
            'orders_by_day' => (function() use ($restaurantId) {
                $rows = Order::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
                    ->whereBetween('created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as cnt')
                    ->groupByRaw('DATE(created_at)')
                    ->pluck('cnt', 'date');
                return collect(range(6, 0))->map(fn($d) => (int)($rows[Carbon::now()->subDays($d)->toDateString()] ?? 0))->values()->toArray();
            })(),
            'active_menu_items' => MenuItem::join('menus', 'menu_items.menu_id', '=', 'menus.id')
                ->when($restaurantId, fn($q) => $q->where('menus.restaurant_id', $restaurantId))
                ->where('menu_items.is_available', true)
                ->count(),
            'avg_rating' => $restaurantId
                ? (float) Review::where('restaurant_id', $restaurantId)->avg('rating')
                : (float) Review::avg('rating'),
            'restaurant_stats' => $user->role === 'admin' ? (function() use ($startDate, $endDate) {
                return Restaurant::select('restaurants.id', 'restaurants.name')
                    ->selectRaw('COALESCE(SUM(orders.total_amount), 0) as total_revenue')
                    ->selectRaw('COUNT(orders.id) as total_orders')
                    ->leftJoin('orders', function($join) use ($startDate, $endDate) {
                        $join->on('restaurants.id', '=', 'orders.restaurant_id');
                        if ($startDate) {
                            $join->whereBetween('orders.created_at', [$startDate, $endDate]);
                        }
                    })
                    ->where('restaurants.is_approved', true)
                    ->groupBy('restaurants.id', 'restaurants.name')
                    ->orderByDesc('total_revenue')
                    ->limit(10)
                    ->get()
                    ->map(fn($r) => [
                        'name' => $r->name,
                        'revenue' => (float) $r->total_revenue,
                        'orders' => (int) $r->total_orders,
                    ]);
            })() : collect(),
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
        if (!$restaurantId || $restaurantId < 1) {
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
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $restaurantId = $isAdmin ? null : (session('userData')['users']->restaurant_id ?? -1);

        $range = request('range', 'year');
        $endDate = Carbon::now();

        switch ($range) {
            case 'today':   $startDate = Carbon::today(); break;
            case 'week':    $startDate = Carbon::now()->subWeek(); break;
            case 'month':   $startDate = Carbon::now()->subMonth(); break;
            case 'year':    $startDate = Carbon::now()->subYear()->startOfMonth(); break;
            case '2_years': $startDate = Carbon::now()->subYears(2)->startOfMonth(); break;
            default:        $startDate = Carbon::now()->subYear()->startOfMonth();
        }

        $orderActivityData = collect([]);
        $reservationActivityData = collect([]);
        $activityLabels = collect([]);

        if ($range === 'today') {
            for ($i = 0; $i < 24; $i++) {
                $hour = Carbon::now()->startOfDay()->addHours($i);
                $activityLabels->push($hour->format('H:00'));
                $orderActivityData->push(Order::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
                    ->whereBetween('created_at', [$hour, $hour->copy()->addHour()])->count());
                $reservationActivityData->push(\App\Models\Reservation::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
                    ->whereBetween('created_at', [$hour, $hour->copy()->addHour()])->count());
            }
        } elseif ($range === 'year' || $range === '2_years') {
            $current = $startDate->copy()->startOfMonth();
            while ($current <= $endDate->copy()->startOfMonth()) {
                $activityLabels->push($current->format('M Y'));
                $orderActivityData->push(Order::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
                    ->whereBetween('created_at', [$current->copy()->startOfMonth(), $current->copy()->endOfMonth()])->count());
                $reservationActivityData->push(\App\Models\Reservation::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
                    ->whereBetween('created_at', [$current->copy()->startOfMonth(), $current->copy()->endOfMonth()])->count());
                $current->addMonth();
            }
        } else {
            $current = $startDate->copy();
            while ($current <= $endDate) {
                $activityLabels->push($current->format('M d'));
                $orderActivityData->push(Order::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
                    ->whereDate('created_at', $current)->count());
                $reservationActivityData->push(\App\Models\Reservation::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
                    ->whereDate('created_at', $current)->count());
                $current->addDay();
            }
        }

        return response()->json([
            'activity_labels' => $activityLabels->toArray(),
            'order_activity_data' => $orderActivityData->toArray(),
            'reservation_activity_data' => $reservationActivityData->toArray(),
        ]);
    }
}
