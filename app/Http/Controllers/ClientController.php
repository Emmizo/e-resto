<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated as OrderCreatedEvent;
use App\Events\ReservationCreated as ReservationCreatedEvent;
use App\Models\FavoriteRestaurant;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PromoBanner;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Cuisine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function restaurants(Request $request)
    {
        $user = Auth::user();
        $preferences = is_array($user->preferences) ? $user->preferences : [];
        $cuisineFilter = $request->get('cuisine');
        $search = $request->get('search');
        $sort = $request->get('sort', 'recommended');

        $query = Restaurant::with([
            'cuisine',
            'reviews',
            'menus.menuItems' => fn($q) => $q->where('is_available', true),
        ])
        ->where('is_approved', true)
        ->where('status', true);

        if ($cuisineFilter) {
            $query->where('cuisine_id', $cuisineFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        $restaurants = $query->get()->map(function ($r) use ($user, $preferences) {
            $avgRating  = round($r->reviews->avg('rating') ?? 0, 1);
            $isFavorite = FavoriteRestaurant::where('user_id', $user->id)
                ->where('restaurant_id', $r->id)->exists();

            // Preference score: count menu items whose category matches user preferences
            $prefScore = 0;
            if (!empty($preferences)) {
                foreach ($r->menus as $menu) {
                    foreach ($menu->menuItems as $item) {
                        foreach ($preferences as $pref) {
                            if (stripos($item->category ?? '', $pref) !== false ||
                                stripos($item->name ?? '', $pref) !== false) {
                                $prefScore++;
                            }
                        }
                    }
                }
            }

            $r->avg_rating  = $avgRating;
            $r->is_favorite = $isFavorite;
            $r->pref_score  = $prefScore;
            $r->review_count = $r->reviews->count();
            $r->menu_items_count = $r->menus->flatMap->menuItems->count();
            return $r;
        });

        // Sort
        if ($sort === 'rating') {
            $restaurants = $restaurants->sortByDesc('avg_rating');
        } elseif ($sort === 'nearby' && $request->has('lat') && $request->has('lng')) {
            $lat = (float) $request->lat;
            $lng = (float) $request->lng;
            $restaurants = $restaurants->sortBy(function ($r) use ($lat, $lng) {
                if (!$r->latitude || !$r->longitude) return 99999;
                $dlat = deg2rad($r->latitude - $lat);
                $dlng = deg2rad($r->longitude - $lng);
                $a = sin($dlat/2)**2 + cos(deg2rad($lat)) * cos(deg2rad($r->latitude)) * sin($dlng/2)**2;
                return 6371 * 2 * asin(sqrt($a));
            })->values();
        } else {
            // recommended: pref_score desc, then rating desc
            $restaurants = $restaurants->sortByDesc('pref_score')
                ->sortByDesc('avg_rating')
                ->values();
        }

        $cuisines = Cuisine::orderBy('name')->get();

        // Show all active banners for approved restaurants, regardless of date expiry
        // so restaurant owners don't lose visibility when they forget to update dates.
        // Banners are sorted: nearby restaurants first (if location known), then newest.
        $banners = PromoBanner::with('restaurant')
            ->where('is_active', true)
            ->whereHas('restaurant', fn($q) => $q->where('is_approved', true)->where('status', true))
            ->latest()
            ->get();

        // Sort banners by proximity if user provided location
        if ($request->has('lat') && $request->has('lng')) {
            $lat = (float) $request->lat;
            $lng = (float) $request->lng;
            $banners = $banners->sortBy(function ($banner) use ($lat, $lng) {
                $r = $banner->restaurant;
                if (!$r || !$r->latitude || !$r->longitude) return 99999;
                $dlat = deg2rad($r->latitude - $lat);
                $dlng = deg2rad($r->longitude - $lng);
                $a = sin($dlat/2)**2 + cos(deg2rad($lat)) * cos(deg2rad($r->latitude)) * sin($dlng/2)**2;
                return 6371 * 2 * asin(sqrt($a));
            })->values();
        }

        return view('client.restaurants', compact('restaurants', 'cuisines', 'sort', 'cuisineFilter', 'search', 'banners'));
    }

    public function restaurant(int $id)
    {
        $user = Auth::user();

        $restaurant = Restaurant::with([
            'cuisine',
            'reviews.user',
            'menus' => fn($q) => $q->where('is_active', true)->with([
                'menuItems' => fn($q) => $q->where('is_available', true)->orderBy('category')
            ]),
        ])
        ->where('is_approved', true)
        ->findOrFail($id);

        $avgRating  = round($restaurant->reviews->avg('rating') ?? 0, 1);
        $isFavorite = FavoriteRestaurant::where('user_id', $user->id)
            ->where('restaurant_id', $id)->exists();

        $allItems = $restaurant->menus->flatMap->menuItems;
        $categories = $allItems->pluck('category')->unique()->filter()->values();

        return view('client.restaurant', compact('restaurant', 'avgRating', 'isFavorite', 'categories', 'allItems'));
    }

    public function placeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id'   => 'required|exists:restaurants,id',
            'order_type'      => 'required|in:dine_in,takeaway,delivery',
            'delivery_address'=> 'required_if:order_type,delivery|nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'special_instructions' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $total = 0;
            $menuItems = [];
            foreach ($request->items as $item) {
                $menuItem = \App\Models\MenuItem::findOrFail($item['menu_item_id']);
                // Validate stock if tracking is enabled
                if ($menuItem->track_inventory && $menuItem->stock_quantity !== null
                    && $menuItem->stock_quantity < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => 422,
                        'message' => "Sorry, only {$menuItem->stock_quantity} of \"{$menuItem->name}\" left in stock.",
                    ], 422);
                }
                $total += $menuItem->price * $item['quantity'];
                $menuItems[$item['menu_item_id']] = $menuItem;
            }

            $order = Order::create([
                'user_id'              => Auth::id(),
                'restaurant_id'        => $request->restaurant_id,
                'total_amount'         => $total,
                'status'               => 'pending',
                'payment_status'       => 'pending',
                'delivery_address'     => $request->delivery_address ?? 'N/A',
                'order_type'           => $request->order_type,
                'special_instructions' => $request->special_instructions,
                'scheduled_time'       => $request->scheduled_time ?: null,
            ]);

            foreach ($request->items as $item) {
                $menuItem = $menuItems[$item['menu_item_id']];
                OrderItem::create([
                    'order_id'     => $order->id,
                    'menu_item_id' => $item['menu_item_id'],
                    'quantity'     => $item['quantity'],
                    'price'        => $menuItem->price * $item['quantity'],
                ]);
                // Deduct inventory
                $menuItem->deductStock($item['quantity']);
            }

            DB::commit();

            // Fire real-time broadcast + store DB notification for restaurant owner
            $order->load(['restaurant.owner', 'user', 'orderItems.menuItem']);
            event(new OrderCreatedEvent($order));

            if ($order->restaurant && $order->restaurant->owner_id) {
                Notification::create([
                    'user_id'       => $order->restaurant->owner_id,
                    'restaurant_id' => $order->restaurant_id,
                    'title'         => 'New Order #' . $order->id,
                    'body'          => ($order->user->first_name ?? 'A customer') . ' placed a ' . $order->order_type . ' order — RWF ' . number_format($order->total_amount, 0) . ($order->scheduled_time ? ' (pickup: ' . \Carbon\Carbon::parse($order->scheduled_time)->format('d M H:i') . ')' : ''),
                    'data'          => ['type' => 'order', 'order_id' => $order->id],
                    'is_read'       => false,
                ]);
            }

            return response()->json([
                'status'  => 201,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Failed to place order: ' . $e->getMessage()], 500);
        }
    }

    public function makeReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id'       => 'required|exists:restaurants,id',
            'reservation_time'    => 'required|date|after:now',
            'number_of_people'    => 'required|integer|min:1|max:50',
            'phone_number'        => 'required|string',
            'special_requests'    => 'nullable|string',
            'preorder_items'      => 'nullable|array',
            'preorder_items.*.menu_item_id' => 'required|exists:menu_items,id',
            'preorder_items.*.quantity'     => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->errors()], 422);
        }

        // Build special_requests combining user notes + pre-order summary
        $specialRequests = $request->special_requests ?? '';
        $preorderItems   = $request->preorder_items ?? [];
        if (!empty($preorderItems)) {
            $preorderLines = [];
            foreach ($preorderItems as $pi) {
                $menuItem = \App\Models\MenuItem::find($pi['menu_item_id']);
                if ($menuItem) {
                    $preorderLines[] = $pi['quantity'] . '× ' . $menuItem->name;
                }
            }
            if ($preorderLines) {
                $preorderNote = 'Pre-ordered food: ' . implode(', ', $preorderLines);
                $specialRequests = $specialRequests
                    ? $specialRequests . "\n" . $preorderNote
                    : $preorderNote;
            }
        }

        $reservation = Reservation::create([
            'user_id'          => Auth::id(),
            'restaurant_id'    => $request->restaurant_id,
            'reservation_time' => $request->reservation_time,
            'number_of_people' => $request->number_of_people,
            'phone_number'     => $request->phone_number,
            'special_requests' => $specialRequests ?: null,
            'status'           => 'pending',
        ]);

        // Fire real-time broadcast + store DB notification for restaurant owner
        $reservation->load(['restaurant.owner', 'user']);
        event(new ReservationCreatedEvent($reservation));

        if ($reservation->restaurant && $reservation->restaurant->owner_id) {
            Notification::create([
                'user_id'       => $reservation->restaurant->owner_id,
                'restaurant_id' => $reservation->restaurant_id,
                'title'         => 'New Reservation',
                'body'          => ($reservation->user->first_name ?? 'A customer') . ' reserved a table for ' . $reservation->number_of_people . ' on ' . \Carbon\Carbon::parse($reservation->reservation_time)->format('d M, H:i'),
                'data'          => ['type' => 'reservation', 'reservation_id' => $reservation->id],
                'is_read'       => false,
            ]);
        }

        return response()->json([
            'status'  => 201,
            'message' => 'Reservation made successfully!',
            'reservation_id' => $reservation->id,
        ], 201);
    }

    public function myOrders()
    {
        $orders = Order::with(['restaurant', 'orderItems.menuItem'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('client.my-orders', compact('orders'));
    }

    public function myReservations()
    {
        $reservations = Reservation::with('restaurant')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('client.my-reservations', compact('reservations'));
    }

    public function toggleFavorite(Request $request)
    {
        $restaurantId = $request->restaurant_id;
        $existing = FavoriteRestaurant::where('user_id', Auth::id())
            ->where('restaurant_id', $restaurantId)->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 200, 'favorited' => false]);
        }

        FavoriteRestaurant::create(['user_id' => Auth::id(), 'restaurant_id' => $restaurantId]);
        return response()->json(['status' => 200, 'favorited' => true]);
    }
}
