<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    // List all addresses for the authenticated user
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->get();
        return response()->json(['data' => $addresses]);
    }

    // Store a new address
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'address' => 'required|string',
            'type' => 'required|string|max:50',
            'is_default' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }
        $user = Auth::user();
        // If is_default is set, unset previous default
        if ($request->is_default) {
            $user->addresses()->update(['is_default' => false]);
            // Also update the user's address field
            $user->address = $request->address;
            $user->save();
        }
        $address = $user->addresses()->create($request->only(['title', 'address', 'type', 'is_default']));
        return response()->json(['status' => 'success', 'data' => $address], 201);
    }

    // Update an address
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string',
            'type' => 'sometimes|required|string|max:50',
            'is_default' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }
        if ($request->has('is_default') && $request->is_default) {
            $user->addresses()->update(['is_default' => false]);
            // Also update the user's address field
            if ($request->has('address')) {
                $user->address = $request->address;
                $user->save();
            }
        }
        $address->update($request->only(['title', 'address', 'type', 'is_default']));
        return response()->json(['status' => 'success', 'data' => $address]);
    }

    // Delete an address
    public function destroy($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        $address->delete();
        return response()->json(['status' => 'success', 'message' => 'Address deleted']);
    }
}
