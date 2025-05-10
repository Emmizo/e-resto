<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PromoBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PromoBannerController extends Controller
{
    // List all promo banners for a restaurant
    public function index(Request $request)
    {
        $restaurantId = $request->query('restaurant_id');
        $query = PromoBanner::query();
        if ($restaurantId) {
            $query->where('restaurant_id', $restaurantId);
        }
        $banners = $query->orderByDesc('created_at')->get();
        return response()->json(['data' => $banners]);
    }

    // List all promo banners for a restaurant, with some restaurant details
    public function listWithRestaurant(Request $request)
    {
        $restaurantId = $request->query('restaurant_id');
        $query = PromoBanner::with(['restaurant:id,name,description,address,longitude,latitude,phone_number,email,website,opening_hours,cuisine_id,price_range,image,owner_id,is_approved,status']);
        if ($restaurantId) {
            $query->where('restaurant_id', $restaurantId);
        }
        $banners = $query->orderByDesc('created_at')->get();
        return response()->json(['data' => $banners]);
    }

    // Store a new promo banner
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['restaurant_id', 'title', 'description', 'start_date', 'end_date', 'is_active']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('promo_banners', 'public');
            $data['image_path'] = $path;
        }

        $banner = PromoBanner::create($data);
        return response()->json(['data' => $banner], 201);
    }

    // Show a single promo banner
    public function show($id)
    {
        $banner = PromoBanner::findOrFail($id);
        return response()->json(['data' => $banner]);
    }

    // Update a promo banner
    public function update(Request $request, $id)
    {
        $banner = PromoBanner::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $data = $request->only(['title', 'description', 'start_date', 'end_date', 'is_active']);
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $path = $request->file('image')->store('promo_banners', 'public');
            $data['image_path'] = $path;
        }
        $banner->update($data);
        return response()->json(['data' => $banner]);
    }

    // Delete a promo banner
    public function destroy($id)
    {
        $banner = PromoBanner::findOrFail($id);
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }
        $banner->delete();
        return response()->json(['message' => 'Promo banner deleted']);
    }
}
