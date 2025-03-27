<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Validator;

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
                'menus.is_active as menu_is_active',
            )
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

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed. Please check your input.',
            ], 422);
        }
        $menu= Menu::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => 1,
            'restaurant_id' => session('userData')['users']->restaurant_id,
        ]);
        if ($menu) {
            return response()->json([
                'status' => 200,
                'message' => 'Menu created successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to create menu.',
            ], 500);
        }
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
}
