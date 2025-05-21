<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::join('restaurants', 'menus.restaurant_id', '=', 'restaurants.id')
            ->select(
                'menus.id',
                'menus.name as menu_name',
                'menus.description as menu_description',
                'menus.is_active',
            )
            ->where('menus.restaurant_id', session('userData')['users']->restaurant_id)
            ->get();
        return view('manage-menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'menu_items' => 'required|array',
            'menu_items.*.name' => 'required|string|max:255',
            // 'menu_items.*.description' => 'required|string|max:255',
            'menu_items.*.price' => 'required|numeric|min:0',
            'menu_items.*.category' => 'required|string|max:255',
            'menu_items.*.dietary_info' => 'nullable|string|max:255',
            'menu_items.*.image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',  // Each item MUST have an image
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed. Please check your input.',
            ], 422);
        }

        // Create the menu (without an image)
        $menu = Menu::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => 1,
            'restaurant_id' => session('userData')['users']->restaurant_id,
        ]);

        // Save each menu item with its own image
        foreach ($request->menu_items as $item) {
            $imagePath = $this->handleMenuItemImage($item['image']);  // Process image

            MenuItem::create([
                'menu_id' => $menu->id,
                'name' => $item['name'] ?? '',
                'description' => $item['description'] ?? '',
                'price' => $item['price'],
                'image' => config('app.url') . '/' . $imagePath ?? '',  // Store the image path
                'category' => $item['category'] ?? '',
                'dietary_info' => $item['dietary_info'] ?? '',
                'is_available' => 1,
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Menu and items created successfully.',
        ]);
    }

    /**
     * Handles uploading an image for a menu item.
     * @param \Illuminate\Http\UploadedFile $imageFile
     * @return string|null Path to the stored image or null if upload fails
     */
    private function handleMenuItemImage($imageFile)
    {
        $directory = public_path('menu_item_images');

        // Create directory if it doesn't exist
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Generate a unique filename
        $imageName = time() . '-' . Str::random(10) . '.' . $imageFile->getClientOriginalExtension();

        // Move the uploaded file
        $imageFile->move($directory, $imageName);

        return 'menu_item_images/' . $imageName;  // Relative path for DB storage
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $menu = Menu::with('menuItems')->find($id);
        if (!$menu) {
            return response()->json([
                'status' => 404,
                'message' => 'Menu not found'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'menu' => [
                'id' => $menu->id,
                'name' => $menu->name,
                'description' => $menu->description,
                'is_active' => $menu->is_active,
                'menu_items' => $menu->menuItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,
                        'price' => $item->price,
                        // 'menu_id' => $menu->id,
                        'category' => $item->category,
                        'dietary_info' => $item->dietary_info,
                        'is_available' => $item->is_available,
                        'image' => $item->image
                    ];
                })
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            // 'is_active' => 'boolean',
            'menu_items' => 'array',
            'menu_items.*.name' => 'required|string|max:255',
            'menu_items.*.price' => 'required|numeric|min:0',
            'menu_items.*.category' => 'required|string|max:255',
            'menu_items.*.dietary_info' => 'nullable|string|max:255',
            'menu_items.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed. Please check your input.',
            ], 422);
        }

        // Update the menu
        $menu->update([
            'name' => $request->name,
            'description' => $request->description,
            // 'is_active' => $request->is_active ?? $menu->is_active,
        ]);

        // Handle menu items
        if ($request->has('menu_items')) {
            // Delete existing menu items that are not in the update
            $existingItemIds = collect($request->menu_items)->pluck('id')->filter();
            $menu->menuItems()->whereNotIn('id', $existingItemIds)->delete();

            // Update or create menu items
            foreach ($request->menu_items as $index => $itemData) {
                $item = null;

                // If item has an ID, find it
                if (!empty($itemData['id'])) {
                    $item = MenuItem::find($itemData['id']);
                }

                // Prepare item data
                $itemAttributes = [
                    'name' => $itemData['name'],
                    'description' => $itemData['description'] ?? '',
                    'price' => $itemData['price'],
                    'menu_id' => $request->id,
                    'category' => $itemData['category'] ?? '',
                    'dietary_info' => $itemData['dietary_info'] ?? '',
                    'is_available' => $itemData['is_available'] ?? 1,
                ];

                // Handle image upload
                if (isset($itemData['image']) && $itemData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $imagePath = $this->handleMenuItemImage($itemData['image']);
                    $itemAttributes['image'] = $imagePath;
                } elseif (isset($itemData['existing_image'])) {
                    $itemAttributes['image'] = $itemData['existing_image'];
                }

                if ($item) {
                    // Update existing item
                    $item->update($itemAttributes);
                } else {
                    // Create new item
                    /*  if (!$menu->id) {
                         \Log::error('Menu ID is null when creating menu item', ['menu' => $menu, 'itemAttributes' => $itemAttributes]);
                         throw new \Exception('Menu ID is missing when creating a new menu item!');
                     } */

                    $itemAttributes['menu_id'] = $request->id;
                    MenuItem::create($itemAttributes);
                }
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Menu updated successfully'
        ]);
    }

    /**
     * Update menu status.
     */
    public function updateStatus(Request $request, Menu $menu)
    {
        $menu->update(['is_active' => $request->is_active]);
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Toggle the availability status of a menu item.
     */
    public function toggleStatus(Request $request, $id)
    {
        $menuItem = \App\Models\MenuItem::findOrFail($id);
        $menuItem->is_available = $request->input('is_available');
        $menuItem->save();
        return response()->json(['status' => 200]);
    }
}
