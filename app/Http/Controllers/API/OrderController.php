<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantEmployee;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="API Endpoints for managing orders"
 * )
 */
class OrderController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * List all orders.
     *
     * @OA\Get(
     *     path="/orders",
     *     summary="List all orders",
     *     tags={"Orders"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter orders by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "processing", "completed", "cancelled"})
     *     ),
     *     @OA\Parameter(
     *         name="payment_status",
     *         in="query",
     *         description="Filter orders by payment status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "paid", "failed"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of orders",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Orders retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", format="int64", example=1),
     *                     @OA\Property(property="user_id", type="integer", format="int64", example=1),
     *                     @OA\Property(property="restaurant_id", type="integer", format="int64", example=1),
     *                     @OA\Property(property="total_amount", type="number", format="float", example=99.99),
     *                     @OA\Property(property="status", type="string", example="pending"),
     *                     @OA\Property(property="payment_status", type="string", example="unpaid"),
     *                     @OA\Property(property="delivery_address", type="string", example="123 Main St"),
     *                     @OA\Property(property="special_instructions", type="string", example="No onions please"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(
     *                         property="items",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", format="int64", example=1),
     *                             @OA\Property(property="order_id", type="integer", format="int64", example=1),
     *                             @OA\Property(property="menu_item_id", type="integer", format="int64", example=1),
     *                             @OA\Property(property="quantity", type="integer", example=2),
     *                             @OA\Property(property="price", type="number", format="float", example=9.99),
     *                             @OA\Property(property="created_at", type="string", format="date-time"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time"),
     *                             @OA\Property(
     *                                 property="menu_item",
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", format="int64", example=1),
     *                                 @OA\Property(property="menu_id", type="integer", format="int64", example=1),
     *                                 @OA\Property(property="name", type="string", example="Cheeseburger"),
     *                                 @OA\Property(property="description", type="string", example="Juicy beef patty with cheese"),
     *                                 @OA\Property(property="price", type="number", format="float", example=9.99),
     *                                 @OA\Property(property="category", type="string", example="Main Course"),
     *                                 @OA\Property(property="dietary_info", type="string", example="Contains dairy"),
     *                                 @OA\Property(property="is_available", type="boolean", example=true),
     *                                 @OA\Property(property="image", type="string", example="burgers/cheeseburger.jpg"),
     *                                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                                 @OA\Property(property="updated_at", type="string", format="date-time")
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();

            // Get all orders for the authenticated user
            $query = Order::with(['user', 'restaurant', 'orderItems.menuItem'])
                ->where('user_id', $user->id);

            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by payment status if provided
            if ($request->has('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            // Add date range filters if provided
            if ($request->has('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->has('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            $orders = $query->orderBy('created_at', 'desc')->get();

            if ($orders->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No orders found',
                    'data' => []
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Orders retrieved successfully',
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new order.
     *
     * @OA\Post(
     *     path="/orders",
     *     summary="Create a new order",
     *     tags={"Orders"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "restaurant_id", "delivery_address", "items"},
     *
     *             @OA\Property(property="restaurant_id", type="integer", example=1),
     *             @OA\Property(property="delivery_address", type="string", example="123 Main St, City"),
     *             @OA\Property(property="special_instructions", type="string", example="Please deliver to back door"),
     *             @OA\Property(property="order_type", type="string", enum={"dine_in", "takeaway", "delivery"}, example="dine_in"),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"menu_item_id", "quantity"},
     *                     @OA\Property(property="menu_item_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Order created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", format="int64", example=1),
     *
     *                 @OA\Property(property="restaurant_id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="total_amount", type="number", format="float", example=99.99),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="payment_status", type="string", example="unpaid"),
     *                 @OA\Property(property="delivery_address", type="string", example="123 Main St"),
     *                 @OA\Property(property="special_instructions", type="string", example="No onions please"),
     *                 @OA\Property(property="order_type", type="string", example="dine_in"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", format="int64", example=1),
     *                         @OA\Property(property="order_id", type="integer", format="int64", example=1),
     *                         @OA\Property(property="menu_item_id", type="integer", format="int64", example=1),
     *                         @OA\Property(property="quantity", type="integer", example=2),
     *                         @OA\Property(property="price", type="number", format="float", example=9.99),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time"),
     *                         @OA\Property(
     *                             property="menu_item",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", format="int64", example=1),
     *                             @OA\Property(property="menu_id", type="integer", format="int64", example=1),
     *                             @OA\Property(property="name", type="string", example="Cheeseburger"),
     *                             @OA\Property(property="description", type="string", example="Juicy beef patty with cheese"),
     *                             @OA\Property(property="price", type="number", format="float", example=9.99),
     *                             @OA\Property(property="category", type="string", example="Main Course"),
     *                             @OA\Property(property="dietary_info", type="string", example="Contains dairy"),
     *                             @OA\Property(property="is_available", type="boolean", example=true),
     *                             @OA\Property(property="image", type="string", example="burgers/cheeseburger.jpg"),
     *                             @OA\Property(property="created_at", type="string", format="date-time"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'delivery_address' => 'required|string',
            'special_instructions' => 'nullable|string',
            'order_type' => 'required|string|in:dine_in,takeaway,delivery',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'dietary_info' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Calculate total amount
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                $totalAmount += $menuItem->price * $item['quantity'];
            }

            $user = auth()->user();

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'restaurant_id' => $request->restaurant_id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'pending',
                'delivery_address' => $request->delivery_address,
                'special_instructions' => $request->special_instructions,
                'order_type' => $request->order_type,
            ]);

            // Create order items
            foreach ($request->items as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item['menu_item_id'],
                    'quantity' => $item['quantity'],
                    'dietary_info' => $item['dietary_info'] ?? null,
                    'price' => $menuItem->price
                ]);
            }

            // Get all staff members of the restaurant (manager, chef, waiter)
            $restaurantStaff = RestaurantEmployee::join('users', 'restaurant_employees.user_id', '=', 'users.id')
                ->where('restaurant_employees.restaurant_id', $request->restaurant_id)
                ->whereIn('restaurant_employees.position', ['manager', 'chef', 'waiter'])
                ->whereNotNull('users.fcm_token')
                ->get();

            // Get the restaurant owner
            $restaurant = $order->restaurant;
            $owner = null;
            if ($restaurant && $restaurant->owner_id) {
                $owner = \App\Models\User::where('id', $restaurant->owner_id)
                    ->where('role', 'restaurant_owner')
                    ->whereNotNull('fcm_token')
                    ->first();
            }

            \Log::info('Order FCM: Staff to notify', [
                'restaurant_id' => $request->restaurant_id,
                'staff_ids' => $restaurantStaff->pluck('id')->toArray(),
                'owner_id' => $owner ? $owner->id : null,
                'count' => $restaurantStaff->count() + ($owner ? 1 : 0)
            ]);

            $notifiedUserIds = $restaurantStaff->pluck('user_id')->toArray();
            foreach ($restaurantStaff as $staff) {
                $title = 'New Order Received';
                $body = "New order #{$order->id} has been placed. Total amount: \$" . number_format($totalAmount, 2);
                $data = [
                    'order_id' => $order->id,
                    'type' => 'new_order'
                ];

                $this->firebaseService->sendNotification(
                    $staff->fcm_token,
                    $title,
                    $body,
                    $data
                );

                \Log::info('Order FCM: Sent notification', [
                    'staff_user_id' => $staff->id,
                    'fcm_token' => $staff->fcm_token,
                    'order_id' => $order->id
                ]);
            }

            // Notify owner if not already notified
            if ($owner && !in_array($owner->id, $notifiedUserIds)) {
                $title = 'New Order Received';
                $body = "New order #{$order->id} has been placed. Total amount: \$" . number_format($totalAmount, 2);
                $data = [
                    'order_id' => $order->id,
                    'type' => 'new_order'
                ];
                $this->firebaseService->sendNotification(
                    $owner->fcm_token,
                    $title,
                    $body,
                    $data
                );
                \Log::info('Order FCM: Sent notification to owner', [
                    'owner_id' => $owner->id,
                    'fcm_token' => $owner->fcm_token,
                    'order_id' => $order->id
                ]);
            }

            // Send email to the restaurant
            if ($restaurant && $restaurant->email) {
                try {
                    \Mail::to($restaurant->email)->send(new \App\Mail\NewOrderNotification($order));
                    \Log::info('Order email sent to: ' . $restaurant->email);
                } catch (\Exception $e) {
                    \Log::error('Order email failed: ' . $e->getMessage());
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully',
                'data' => $order->load('orderItems.menuItem')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order details.
     *
     * @OA\Get(
     *     path="/orders/{id}",
     *     summary="Get order details",
     *     tags={"Orders"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Order details retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="user_id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="restaurant_id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="total_amount", type="number", format="float", example=99.99),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="payment_status", type="string", example="unpaid"),
     *                 @OA\Property(property="delivery_address", type="string", example="123 Main St"),
     *                 @OA\Property(property="special_instructions", type="string", example="No onions please"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", format="int64", example=1),
     *                         @OA\Property(property="order_id", type="integer", format="int64", example=1),
     *                         @OA\Property(property="menu_item_id", type="integer", format="int64", example=1),
     *                         @OA\Property(property="quantity", type="integer", example=2),
     *                         @OA\Property(property="price", type="number", format="float", example=9.99),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time"),
     *                         @OA\Property(
     *                             property="menu_item",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", format="int64", example=1),
     *                             @OA\Property(property="menu_id", type="integer", format="int64", example=1),
     *                             @OA\Property(property="name", type="string", example="Cheeseburger"),
     *                             @OA\Property(property="description", type="string", example="Juicy beef patty with cheese"),
     *                             @OA\Property(property="price", type="number", format="float", example=9.99),
     *                             @OA\Property(property="category", type="string", example="Main Course"),
     *                             @OA\Property(property="dietary_info", type="string", example="Contains dairy"),
     *                             @OA\Property(property="is_available", type="boolean", example=true),
     *                             @OA\Property(property="image", type="string", example="burgers/cheeseburger.jpg"),
     *                             @OA\Property(property="created_at", type="string", format="date-time"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            // Validate that the ID exists and is numeric
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|numeric|exists:orders,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $order = Order::with(['user', 'restaurant', 'orderItems.menuItem'])
                ->where('user_id', auth()->user()->id)
                ->find($id);

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order not found for this user'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Order details retrieved successfully',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve order details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order status.
     *
     * @OA\Put(
     *     path="/orders/{id}",
     *     summary="Update order status",
     *     tags={"Orders"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 enum={"pending", "processing", "completed", "cancelled"},
     *                 example="processing"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order status updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Order status updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="user_id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="restaurant_id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="total_amount", type="number", format="float", example=99.99),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="payment_status", type="string", example="unpaid"),
     *                 @OA\Property(property="delivery_address", type="string", example="123 Main St"),
     *                 @OA\Property(property="special_instructions", type="string", example="No onions please"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", format="int64", example=1),
     *                         @OA\Property(property="order_id", type="integer", format="int64", example=1),
     *                         @OA\Property(property="menu_item_id", type="integer", format="int64", example=1),
     *                         @OA\Property(property="quantity", type="integer", example=2),
     *                         @OA\Property(property="price", type="number", format="float", example=9.99),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time"),
     *                         @OA\Property(
     *                             property="menu_item",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", format="int64", example=1),
     *                             @OA\Property(property="menu_id", type="integer", format="int64", example=1),
     *                             @OA\Property(property="name", type="string", example="Cheeseburger"),
     *                             @OA\Property(property="description", type="string", example="Juicy beef patty with cheese"),
     *                             @OA\Property(property="price", type="number", format="float", example=9.99),
     *                             @OA\Property(property="category", type="string", example="Main Course"),
     *                             @OA\Property(property="dietary_info", type="string", example="Contains dairy"),
     *                             @OA\Property(property="is_available", type="boolean", example=true),
     *                             @OA\Property(property="image", type="string", example="burgers/cheeseburger.jpg"),
     *                             @OA\Property(property="created_at", type="string", format="date-time"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // First check if the order exists
        $order = Order::where('user_id', auth()->user()->id)->find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        // Then validate the status
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        if ($oldStatus !== $order->status && $order->user && $order->user->email) {
            try {
                \Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order));
            } catch (\Exception $e) {
                \Log::error('Failed to send order status update email: ' . $e->getMessage());
            }
            // Send FCM and persistent notification
            if ($order->user->fcm_token) {
                $title = 'Order Status Updated';
                $body = "Your order #{$order->id} status has been updated to: " . ucfirst($order->status);
                $data = [
                    'order_id' => $order->id,
                    'restaurant_id' => $order->restaurant_id,
                    'status' => $order->status,
                    'type' => 'order_status',
                ];
                $this->firebaseService->sendNotification(
                    $order->user->fcm_token,
                    $title,
                    $body,
                    $data,
                    $order->user_id
                );
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order status updated successfully',
            'data' => $order->load('orderItems.menuItem')
        ]);
    }

    /**
     * Delete an order.
     *
     * @OA\Delete(
     *     path="/orders/{id}",
     *     summary="Delete an order",
     *     tags={"Orders"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Order deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $order = Order::where('user_id', auth()->user()->id)
                ->find($id);

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order ID not found'
                ], 404);
            }

            $order->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Order deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
