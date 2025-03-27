<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Validator;
use Str;
use App\Models\MenuItem;
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
            )->where('menus.restaurant_id', session('userData')['users']->restaurant_id)
            ->get();
        return view('manage-menu.index',compact('menus'));
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
            'menu_items.*.image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Each item MUST have an image
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
            $imagePath = $this->handleMenuItemImage($item['image']); // Process image

            MenuItem::create([
                'menu_id' => $menu->id,
                'name' => $item['name']??'',
                'description' => $item['description']??'',
                'price' => $item['price'],
                'image' => $imagePath??'', // Store the image path
                'category' => $item['category']??'',
                'dietary_info' => $item['dietary_info']??'',
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

    return 'menu_item_images/' . $imageName; // Relative path for DB storage
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request,  Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $menu->update($validated);

        return response()->json(['success' => true]);
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
}
