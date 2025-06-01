<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class managePermissions extends Controller
{
    public function managePermissions(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'restaurant_id' => 'required|exists:restaurants,id',
        'permission_name' => 'required|string',
        'action' => 'required|in:grant,revoke'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 422,
            'errors' => $validator->errors()
        ], 422);
    }

    $user = User::findOrFail($request->user_id);
    $restaurantId = session('userData')['users']->restaurant_id;
    $permissionName = $request->permission_name;

    // Verify the current user has rights to manage permissions
    $currentUser = auth()->user();
    if (!$this->canManagePermissions($currentUser, $restaurantId)) {
        return response()->json([
            'status' => 403,
            'message' => 'Unauthorized to manage permissions'
        ], 403);
    }

    if ($request->action === 'grant') {
        $user->grantRestaurantPermission($restaurantId, $permissionName);
    } else {
        $user->revokeRestaurantPermission($restaurantId, $permissionName);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Permissions updated successfully'
    ]);
}

/**
 * Check if the current user can manage permissions for a restaurant
 */
private function canManagePermissions($user, $restaurantId)
{
    // Either the restaurant owner or an admin with specific rights
    return $user->role === 'admin' ||
           ($user->role === 'restaurant_owner' &&
            Restaurant::where('owner_id', $user->id)
                ->where('id', $restaurantId)
                ->exists());
}
}
