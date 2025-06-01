<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FavoriteMenuItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * List all cuisines (API endpoint)
     */
    public function listCuisines()
    {
        $cuisines = \App\Models\Cuisine::all();
        return response()->json([
            'status' => 'success',
            'data' => $cuisines
        ]);
    }

    /**
     * Add a menu item to user's favorites
     */
    public function favoriteMenuItem(Request $request)
    {
        $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
        ]);
        $user = Auth::user();
        $favorite = FavoriteMenuItem::firstOrCreate([
            'user_id' => $user->id,
            'menu_item_id' => $request->menu_item_id,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Menu item added to favorites',
            'data' => $favorite
        ], 201);
    }

    /**
     * Remove a menu item from user's favorites
     */
    public function unfavoriteMenuItem(Request $request)
    {
        $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
        ]);
        $user = Auth::user();
        $deleted = FavoriteMenuItem::where('user_id', $user->id)
            ->where('menu_item_id', $request->menu_item_id)
            ->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Menu item removed from favorites',
            'deleted' => $deleted > 0
        ]);
    }

    /**
     * List all favorite menu items for the authenticated user
     */
    public function listFavoriteMenuItems(Request $request)
    {
        $user = Auth::user();
        $favorites = FavoriteMenuItem::with(['menuItem.menu.restaurant'])
            ->where('user_id', $user->id)
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $favorites
        ]);
    }
}
