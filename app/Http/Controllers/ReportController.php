<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private function getRestaurantId(Request $request): ?int
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return $request->input('restaurant_id') ? (int) $request->input('restaurant_id') : null;
        }
        // Use -1 (matches nothing) when restaurant_id is missing — prevents
        // ->when(null, ...) from silently skipping the filter and returning all-restaurant data.
        return session('userData')['users']->restaurant_id ?? -1;
    }

    private function getDateRange(Request $request): array
    {
        $range = $request->input('range', 'month');
        $from = $request->input('from');
        $to = $request->input('to');

        if ($from && $to) {
            return [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()];
        }

        $end = Carbon::now()->endOfDay();
        switch ($range) {
            case 'today':
                return [Carbon::today()->startOfDay(), $end];
            case 'week':
                return [Carbon::now()->startOfWeek(), $end];
            case 'month':
                return [Carbon::now()->startOfMonth(), $end];
            case 'year':
                return [Carbon::now()->startOfYear(), $end];
            default:
                return [Carbon::now()->subDays(29)->startOfDay(), $end];
        }
    }

    private function getPreviousPeriodDates(array $current): array
    {
        [$start, $end] = $current;
        $length = $start->diffInSeconds($end);
        return [
            $start->copy()->subSeconds($length + 1),
            $start->copy()->subSecond(),
        ];
    }

    public function index()
    {
        return view('reports.index');
    }

    public function ordersReport(Request $request)
    {
        $restaurantId = $this->getRestaurantId($request);
        [$start, $end] = $this->getDateRange($request);

        $baseQuery = function () use ($restaurantId, $start, $end) {
            return Order::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
                ->whereBetween('created_at', [$start, $end]);
        };

        $revenueByDay = Order::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get()
            ->map(fn($r) => ['date' => $r->date, 'revenue' => (float) $r->revenue, 'count' => (int) $r->count]);

        $statusCounts = $baseQuery()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $typeCounts = $baseQuery()
            ->selectRaw('order_type, COUNT(*) as count')
            ->groupBy('order_type')
            ->pluck('count', 'order_type');

        $paymentStatus = $baseQuery()
            ->selectRaw('payment_status, SUM(total_amount) as revenue, COUNT(*) as count')
            ->groupBy('payment_status')
            ->get()
            ->map(fn($r) => ['status' => $r->payment_status, 'revenue' => (float) $r->revenue, 'count' => (int) $r->count]);

        $totalOrders = $baseQuery()->count();
        $totalRevenue = $baseQuery()->sum('total_amount');
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $topItems = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->when($restaurantId, fn($q) => $q->where('orders.restaurant_id', $restaurantId))
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw('menu_items.name, SUM(order_items.quantity) as qty_sold, SUM(order_items.price * order_items.quantity) as revenue')
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderByDesc('qty_sold')
            ->limit(5)
            ->get()
            ->map(fn($r) => ['name' => $r->name, 'qty_sold' => (int) $r->qty_sold, 'revenue' => (float) $r->revenue]);

        return response()->json([
            'revenue_by_day' => $revenueByDay,
            'status_counts' => $statusCounts,
            'type_counts' => $typeCounts,
            'payment_status' => $paymentStatus,
            'total_orders' => $totalOrders,
            'total_revenue' => (float) $totalRevenue,
            'avg_order_value' => round($avgOrderValue, 2),
            'top_items' => $topItems,
        ]);
    }

    public function reservationsReport(Request $request)
    {
        $restaurantId = $this->getRestaurantId($request);
        [$start, $end] = $this->getDateRange($request);

        $base = fn() => Reservation::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->whereBetween('created_at', [$start, $end]);

        $byDay = $base()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get()
            ->map(fn($r) => ['date' => $r->date, 'count' => (int) $r->count]);

        $statusCounts = $base()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $partySizes = $base()
            ->selectRaw("
                SUM(CASE WHEN number_of_people <= 2 THEN 1 ELSE 0 END) as small,
                SUM(CASE WHEN number_of_people BETWEEN 3 AND 5 THEN 1 ELSE 0 END) as medium,
                SUM(CASE WHEN number_of_people >= 6 THEN 1 ELSE 0 END) as large
            ")
            ->first();

        $peakHours = $base()
            ->selectRaw('HOUR(reservation_time) as hour, COUNT(*) as count')
            ->groupByRaw('HOUR(reservation_time)')
            ->orderBy('hour')
            ->get()
            ->map(fn($r) => ['hour' => (int) $r->hour, 'count' => (int) $r->count]);

        $hoursArray = array_fill(0, 24, 0);
        foreach ($peakHours as $h) {
            $hoursArray[$h['hour']] = $h['count'];
        }

        return response()->json([
            'by_day' => $byDay,
            'status_counts' => $statusCounts,
            'party_sizes' => [
                '1-2' => (int) ($partySizes->small ?? 0),
                '3-5' => (int) ($partySizes->medium ?? 0),
                '6+' => (int) ($partySizes->large ?? 0),
            ],
            'peak_hours' => $hoursArray,
            'total' => $base()->count(),
        ]);
    }

    public function menuReport(Request $request)
    {
        $restaurantId = $this->getRestaurantId($request);
        [$start, $end] = $this->getDateRange($request);

        $top10 = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->when($restaurantId, fn($q) => $q->where('orders.restaurant_id', $restaurantId))
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw('menu_items.name, menu_items.price as unit_price, menu_items.category,
                SUM(order_items.quantity) as qty_sold,
                SUM(order_items.price * order_items.quantity) as revenue')
            ->groupBy('menu_items.id', 'menu_items.name', 'menu_items.price', 'menu_items.category')
            ->orderByDesc('qty_sold')
            ->limit(10)
            ->get()
            ->map(fn($r) => [
                'name' => $r->name,
                'category' => $r->category,
                'unit_price' => (float) $r->unit_price,
                'qty_sold' => (int) $r->qty_sold,
                'revenue' => (float) $r->revenue,
                'avg_price' => $r->qty_sold > 0 ? round($r->revenue / $r->qty_sold, 2) : 0,
            ]);

        $categoryBreakdown = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->when($restaurantId, fn($q) => $q->where('orders.restaurant_id', $restaurantId))
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw('menu_items.category, SUM(order_items.price * order_items.quantity) as revenue')
            ->groupBy('menu_items.category')
            ->orderByDesc('revenue')
            ->get()
            ->map(fn($r) => ['category' => $r->category ?? 'Uncategorized', 'revenue' => (float) $r->revenue]);

        return response()->json([
            'top_items' => $top10,
            'category_breakdown' => $categoryBreakdown,
        ]);
    }

    public function customersReport(Request $request)
    {
        $restaurantId = $this->getRestaurantId($request);
        [$start, $end] = $this->getDateRange($request);

        $newCustomers = User::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get()
            ->map(fn($r) => ['date' => $r->date, 'count' => (int) $r->count]);

        $orderersInPeriod = Order::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->whereBetween('created_at', [$start, $end])
            ->select('user_id')
            ->groupBy('user_id')
            ->get()
            ->pluck('user_id');

        $repeatCustomers = 0;
        $newOrderCustomers = 0;
        foreach ($orderersInPeriod as $userId) {
            $previousOrderCount = Order::where('user_id', $userId)
                ->when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
                ->where('created_at', '<', $start)
                ->count();
            if ($previousOrderCount > 0) {
                $repeatCustomers++;
            } else {
                $newOrderCustomers++;
            }
        }

        return response()->json([
            'new_customers_by_day' => $newCustomers,
            'repeat_vs_new' => [
                'repeat' => $repeatCustomers,
                'new' => $newOrderCustomers,
            ],
            'total_new_registrations' => $newCustomers->sum('count'),
        ]);
    }

    public function restaurantsReport(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        [$start, $end] = $this->getDateRange($request);

        $stats = \App\Models\Restaurant::select('restaurants.id', 'restaurants.name')
            ->selectRaw('COALESCE(SUM(orders.total_amount), 0) as total_revenue')
            ->selectRaw('COUNT(orders.id) as total_orders')
            ->selectRaw('COALESCE(AVG(reviews.rating), 0) as avg_rating')
            ->leftJoin('orders', function($join) use ($start, $end) {
                $join->on('restaurants.id', '=', 'orders.restaurant_id')
                     ->whereBetween('orders.created_at', [$start, $end]);
            })
            ->leftJoin('reviews', 'restaurants.id', '=', 'reviews.restaurant_id')
            ->where('restaurants.is_approved', true)
            ->groupBy('restaurants.id', 'restaurants.name')
            ->orderByDesc('total_revenue')
            ->get()
            ->map(fn($r) => [
                'name' => $r->name,
                'revenue' => (float) $r->total_revenue,
                'orders' => (int) $r->total_orders,
                'avg_rating' => round((float) $r->avg_rating, 1),
            ]);

        return response()->json(['stats' => $stats]);
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'orders');
        $restaurantId = $this->getRestaurantId($request);
        [$start, $end] = $this->getDateRange($request);

        $filename = "{$type}_report_" . $start->format('Y-m-d') . '_to_' . $end->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($type, $restaurantId, $start, $end) {
            $handle = fopen('php://output', 'w');

            if ($type === 'orders') {
                fputcsv($handle, ['Order ID', 'Date', 'Type', 'Status', 'Payment Status', 'Amount (RWF)']);
                Order::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
                    ->whereBetween('created_at', [$start, $end])
                    ->orderBy('created_at')
                    ->chunk(500, function ($orders) use ($handle) {
                        foreach ($orders as $order) {
                            fputcsv($handle, [
                                $order->id,
                                $order->created_at->format('Y-m-d H:i:s'),
                                $order->order_type,
                                $order->status,
                                $order->payment_status,
                                number_format($order->total_amount, 2),
                            ]);
                        }
                    });
            } elseif ($type === 'reservations') {
                fputcsv($handle, ['ID', 'Date', 'Reservation Time', 'Guests', 'Status', 'Phone']);
                Reservation::when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
                    ->whereBetween('created_at', [$start, $end])
                    ->orderBy('created_at')
                    ->chunk(500, function ($reservations) use ($handle) {
                        foreach ($reservations as $r) {
                            fputcsv($handle, [
                                $r->id,
                                $r->created_at->format('Y-m-d H:i:s'),
                                $r->reservation_time,
                                $r->number_of_people,
                                $r->status,
                                $r->phone_number,
                            ]);
                        }
                    });
            } elseif ($type === 'menu') {
                fputcsv($handle, ['Item Name', 'Category', 'Unit Price (RWF)', 'Qty Sold', 'Revenue (RWF)']);
                OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
                    ->when($restaurantId, fn($q) => $q->where('orders.restaurant_id', $restaurantId))
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->selectRaw('menu_items.name, menu_items.price, menu_items.category,
                        SUM(order_items.quantity) as qty_sold,
                        SUM(order_items.price * order_items.quantity) as revenue')
                    ->groupBy('menu_items.id', 'menu_items.name', 'menu_items.price', 'menu_items.category')
                    ->orderByDesc('qty_sold')
                    ->chunk(500, function ($items) use ($handle) {
                        foreach ($items as $item) {
                            fputcsv($handle, [
                                $item->name,
                                $item->category,
                                number_format($item->price, 2),
                                $item->qty_sold,
                                number_format($item->revenue, 2),
                            ]);
                        }
                    });
            } elseif ($type === 'customers') {
                fputcsv($handle, ['Date', 'New Registrations']);
                User::whereBetween('created_at', [$start, $end])
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                    ->groupByRaw('DATE(created_at)')
                    ->orderBy('date')
                    ->get()
                    ->each(function ($r) use ($handle) {
                        fputcsv($handle, [
                            $r->date,
                            $r->count,
                        ]);
                    });
            } elseif ($type === 'restaurants' && Auth::user()->role === 'admin') {
                fputcsv($handle, ['Restaurant', 'Orders', 'Revenue (RWF)', 'Avg Rating']);
                \App\Models\Restaurant::select('restaurants.id', 'restaurants.name')
                    ->selectRaw('COALESCE(SUM(orders.total_amount), 0) as total_revenue')
                    ->selectRaw('COUNT(orders.id) as total_orders')
                    ->selectRaw('COALESCE(AVG(reviews.rating), 0) as avg_rating')
                    ->leftJoin('orders', function($join) use ($start, $end) {
                        $join->on('restaurants.id', '=', 'orders.restaurant_id')
                             ->whereBetween('orders.created_at', [$start, $end]);
                    })
                    ->leftJoin('reviews', 'restaurants.id', '=', 'reviews.restaurant_id')
                    ->where('restaurants.is_approved', true)
                    ->groupBy('restaurants.id', 'restaurants.name')
                    ->orderByDesc('total_revenue')
                    ->get()
                    ->each(function ($r) use ($handle) {
                        fputcsv($handle, [
                            $r->name,
                            $r->total_orders,
                            number_format($r->total_revenue, 0),
                            round($r->avg_rating, 1),
                        ]);
                    });
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
