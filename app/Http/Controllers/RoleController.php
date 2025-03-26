<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
            // return $request->Permissions;
			$role = Role::create(['name' => $request->name]);
			if ($request->Permissions) {
				$permissions = Permission::whereIn('id', $request->Permissions)->get();

				$role->syncPermissions($permissions);
			}
		} catch (\Exception $e) {
			$error['name'] = [$e->getMessage()];
			throw \Illuminate\Validation\ValidationException::withMessages($error);
		}
		return response()->json([
            'status' => 200,
            'msg' => 'Permission assigned to role.',
        ], 200);
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
