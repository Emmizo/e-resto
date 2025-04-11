<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Get the restaurant ID from the authenticated user
            $restaurantId = auth()->user()->restaurant_id;

            if (!$restaurantId) {
                return redirect()->back()->with('error', 'No restaurant associated with your account.');
            }

            $orders = Order::with(['user', 'restaurant', 'orderItems.menuItem'])
                ->where('restaurant_id', $restaurantId)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('manage-orders.index', compact('orders'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving orders: ' . $e->getMessage());
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
            'total_amount' => 'required|numeric|min:0'
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
                'restaurant_id' => session('userData')['users']->restaurant_id,
                'total_amount' => $request->total_amount,
                'status' => 'pending',
                'payment_status' => 'pending',
                'delivery_address' => $request->delivery_address,
                'special_instructions' => $request->special_instructions,
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

            DB::commit();

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
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed. Please check your input.'
            ], 422);
        }

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json([
            'status' => 200,
            'message' => 'Order status updated successfully'
        ]);
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
}
