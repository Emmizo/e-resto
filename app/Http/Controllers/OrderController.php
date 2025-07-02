<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Mail\NewOrderNotification;
use App\Mail\OrderStatusUpdated;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::with(['user'])
                ->select('orders.*', 'users.first_name', 'users.last_name')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->where('orders.restaurant_id', session('userData')['users']->restaurant_id)
                ->orderBy('orders.created_at', 'desc')
                ->get();

            return view('manage-orders.index', compact('orders'));
        } catch (\Exception $e) {
            \Log::error('Error fetching orders: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error fetching orders. Please try again.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'customer')->get();
        $menuItems = MenuItem::whereHas('menu', function ($query) {
            $query->where('restaurant_id', session('userData')['users']->restaurant_id);
        })->get();

        return view('manage-orders.create', compact('users', 'menuItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'delivery_address' => 'required|string',
            'special_instructions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'dietary_info' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed. Please check your input.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create the order
            $order = Order::create([
                'user_id' => $request->user_id,
                'restaurant_id' => $request->restaurant_id,
                'total_amount' => $request->total_amount,
                'status' => 'pending',
                'payment_status' => 'pending',
                'delivery_address' => $request->delivery_address,
                'special_instructions' => $request->special_instructions,
                'dietary_info' => $request->dietary_info ?? null,
            ]);

            // Create order items
            foreach ($request->items as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item['menu_item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $menuItem->price,
                ]);
            }

            // Broadcast the order created event
            event(new OrderCreated($order));

            DB::commit();

            $restaurant = $order->restaurant;
            if ($restaurant && $restaurant->email) {
                \Mail::to($restaurant->email)->send(new NewOrderNotification($order));
            }

            return response()->json([
                'status' => 200,
                'message' => 'Order created successfully',
                'data' => $order->load('orderItems.menuItem')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['user', 'restaurant', 'orderItems.menuItem'])
            ->findOrFail($id);

        return view('manage-orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $request->validate([
                'status' => 'required|in:pending,processing,completed,cancelled'
            ]);

            $oldStatus = $order->status;
            $order->status = $request->status;
            $order->save();

            // Send notification to user if status changed
            if ($oldStatus !== $request->status) {
                $user = $order->user;
                if ($user && $user->fcm_token) {
                    $title = 'Order Status Updated';
                    $body = "Your order #{$order->id} status has been updated to: " . ucfirst($request->status);
                    $data = [
                        'order_id' => $order->id,
                        'status' => $request->status
                    ];

                    // $this->firebaseService->sendNotification(
                    //     $user->fcm_token,
                    //     $title,
                    //     $body,
                    //     $data
                    // );
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Order status updated successfully',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            \Log::error('Order status update error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update order status'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Order deleted successfully'
        ]);
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        $user = $order->user;
        $fcmToken = $user->fcm_token;
        if ($fcmToken) {
            FcmService::send(
                $fcmToken,
                'Order Update',
                "Your order at {$order->restaurant->name} is now {$order->status}!",
                [
                    'type' => 'order_status',
                    'order_id' => (string) $order->id,
                    'status' => $order->status,
                ]
            );
        }

        // Send notifications if status changed
        if ($oldStatus !== $order->status) {
            // Send email notification
            if ($user && $user->email) {
                try {
                    Mail::to($user->email)->send(new OrderStatusUpdated($order));
                } catch (\Exception $e) {
                    \Log::error('Failed to send order status update email: ' . $e->getMessage());
                }
            }

            // Send Firebase push notification
            if ($user && $user->fcm_token) {
                $title = 'Order Status Updated';
                $body = "Your order #{$order->id} status has been updated to: " . ucfirst($order->status);
                $data = [
                    'order_id' => $order->id,
                    'status' => $order->status
                ];

                // $this->firebaseService->sendNotification(
                //     $user->fcm_token,
                //     $title,
                //     $body,
                //     $data
                // );
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Order status updated successfully',
            'data' => $order
        ]);
    }
}
