<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Table;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     * Optionally filter by restaurant_id.
     */
    public function index(Request $request)
    {
        $query = Table::query();
        if ($request->has('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }
        return response()->json($query->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'table_number' => 'required|string|max:50',
            'status' => 'nullable|string',
        ]);
        $table = Table::create($validated);
        return response()->json($table, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $table = Table::findOrFail($id);
        return response()->json($table);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $table = Table::findOrFail($id);
        $validated = $request->validate([
            'table_number' => 'sometimes|required|string|max:50',
            'status' => 'nullable|string',
        ]);
        $table->update($validated);
        return response()->json($table);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $table = Table::findOrFail($id);
        $table->delete();
        return response()->json(['message' => 'Table deleted']);
    }

    /**
     * List available tables for a restaurant.
     */
    public function available(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);
        $tables = Table::where('restaurant_id', $request->restaurant_id)
            ->where('status', 'available')
            ->get();
        return response()->json($tables);
    }
}
