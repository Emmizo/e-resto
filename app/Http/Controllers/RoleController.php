<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all permissions
        $permissions = Permission::get();

        // Fetch the authenticated user
        $user = Auth::user();

        // Basic query to fetch roles with their details
        $roles = Role::select('id', 'name',  'created_at', 'updated_at')
            ->get()
            ->map(function ($role) {
                // Optional: Add permission information
                $role->permissions = str_replace(['","', '"'], [',', ' '], $role->getPermissionNames());
                return $role;
            });

        // Return the view with both permissions and roles
        return view('manage-roles.index', compact('permissions', 'roles'));
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
    try {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $restaurantId = session('userData')['users']->restaurant_id;

        // Create role with restaurant prefix
        $roleName = "restaurant_{$restaurantId}_{$request->name}";
        $role = Role::create(['name' => $roleName, 'restaurant_id' => $restaurantId]);

        if ($request->permissions) {
            if ($request->Permissions) {
				$permissions = Permission::whereIn('id', $request->Permissions)->get();
				$role->syncPermissions($permissions);
			}

            $role->syncPermissions($permissions);
        }

        return response()->json([
            'status' => 200,
            'msg' => 'Role created successfully.',
            'role' => $role
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'Error creating role: ' . $e->getMessage()
        ], 500);
    }
}
public function delete(Request $request) {

    $role = Role::find($request->role_id);
    $users = User::role($role->name)->get();

    if (count($users) == 0) {
        foreach ($role->permissions as $key => $value) {
            $role->revokePermissionTo($value);
        }
        $role->delete();
        $data['status'] = 'success';
        $data['message'] = 'Role Deleted';
    } else {
        $data['status'] = 'error';
        $data['message'] = 'Role assigned to users';
    }
    return $data;
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
