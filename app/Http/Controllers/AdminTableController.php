<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class AdminTableController extends Controller
{
    public function index()
    {
        // If multi-restaurant, you can filter by restaurant_id
        $tables = Table::with('restaurant')->where('restaurant_id', session('userData')['users']->restaurant_id)->get();
        return view('admin.tables.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'table_number' => 'required|string|max:50',
        ]);
        $validated['status'] = 'available';
        Table::create($validated);
        return redirect()->route('admin.tables.index')->with('success', 'Table added successfully.');
    }

    public function update(Request $request, $id)
    {
        $table = Table::findOrFail($id);
        $validated = $request->validate([
            'table_number' => 'required|string|max:50',
            'status' => 'required|string',
        ]);
        $table->update($validated);
        return redirect()->route('admin.tables.index')->with('success', 'Table updated successfully.');
    }

    public function toggleStatus($id)
    {
        $table = Table::findOrFail($id);
        $table->status = $table->status === 'available' ? 'occupied' : 'available';
        $table->save();
        return redirect()->route('admin.tables.index')->with('success', 'Table status updated.');
    }
}
