<?php

namespace App\Http\Controllers;

use App\Events\NewUserCreatedEvent;
use App\Models\Restaurant;
use App\Models\RestaurantEmployee;
use App\Models\RestaurantPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Hash;
use Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::get();
        $users = User::leftJoin('restaurant_employees', function ($join) {
            $join->on('restaurant_employees.user_id', '=', 'users.id');
        })
            ->leftJoin('restaurants', function ($join) {
                $join
                    ->on('restaurants.id', '=', 'restaurant_employees.restaurant_id')
                    ->orOn('restaurants.owner_id', '=', 'users.id');  // Include owner in employee list
            })
            ->select(
                'users.*',
                'restaurants.name as restaurant_name',
                'restaurants.address as restaurant_address',
                'restaurants.phone_number as restaurant_phone',
                'restaurants.email as restaurant_email',
                'restaurants.website',
                'restaurants.image as restaurant_logo',
                'restaurants.is_approved',
                'restaurants.id as restaurant_id',
                'restaurant_employees.position as employee_role',
                \DB::raw('CASE WHEN users.id = restaurants.owner_id THEN "Owner" ELSE "Employee" END as is_owner')
            )
            ->where(function ($query) {
                $query
                    ->where('users.id', auth()->user()->id)
                    ->orWhere(function ($q) {
                        if (auth()->user()->role === 'restaurant_owner') {
                            // Restaurant owners should see their employees
                            $q->where('restaurants.owner_id', auth()->user()->id);
                        } elseif (auth()->user()->role === 'manager') {
                            // Employees should see their workmates in the same restaurant
                            $q
                                ->whereIn('restaurant_employees.restaurant_id', function ($subquery) {
                                    $subquery
                                        ->select('restaurant_id')
                                        ->from('restaurant_employees')
                                        ->where('user_id', auth()->user()->id);
                                })
                                ->where('users.id', '!=', auth()->user()->id);
                        } elseif (auth()->user()->role === 'admin') {
                            // Admins should see all users associated with restaurants
                            $q->where('users.role', 'restaurant_owner');
                        }
                    });
            })
            ->distinct()
            ->get();

        return view('manage-users.index', compact('users', 'permissions'));
        //
    }

    /**
     * create employeee for for joining the specified resource.
     */
    public function createEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'permissions' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed. Please check your input.',
            ], 422);
        }

        $restaurantId = session('userData')['users']->restaurant_id;

        $password = Str::random(8);
        $encryptpassword = Hash::make($password);

        // Profile picture handling (keep your existing logic)
        $profilePicturePath = $this->handleProfilePicture($request);

        // Create the user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $encryptpassword,
            'plain_password' => $password,
            'role' => $request->position,
            'phone_number' => $request->phone_number,
            'profile_picture' => $profilePicturePath ? config('app.url') . '/' . $profilePicturePath : null,
            'preferences' => json_encode([]),
        ]);

        // Create restaurant employee with specific permissions
        $restaurantEmployee = RestaurantEmployee::create([
            'restaurant_id' => $restaurantId,
            'user_id' => $user->id,
            'position' => $request->position,
            'permissions' => json_encode($request->permissions ?? []),
            'is_active' => $request->is_active,
        ]);
        // Grant specific permissions
        if ($request->permissions) {
            foreach ($request->permissions as $permission) {
                RestaurantPermission::create([
                    'user_id' => $user->id,
                    'restaurant_id' => $restaurantId,
                    'permission_name' => $permission,
                    'granted' => true
                ]);
            }
        }
        event(new NewUserCreatedEvent($user, $password));

        return response()->json([
            'status' => 200,
            'msg' => 'Employee created successfully.',
        ], 200);
    }

    public function updateEmployee(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'permissions' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed. Please check your input.',
            ], 422);
        }

        $restaurantId = session('userData')['users']->restaurant_id;

        // Find the user
        $user = User::findOrFail($id);

        // Profile picture handling (keep your existing logic)
        $profilePicturePath = $this->handleProfilePicture($request);

        // Update user details
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => $request->position,
            'phone_number' => $request->phone_number,
            'profile_picture' => config('app.url') . '/' . $profilePicturePath,
        ]);

        // Update restaurant employee
        $restaurantEmployee = RestaurantEmployee::where('user_id', $id)
            ->where('restaurant_id', $restaurantId)
            ->first();

        if ($restaurantEmployee) {
            $restaurantEmployee->update([
                'position' => $request->position,
                'permissions' => json_encode($request->permissions ?? []),
                'is_active' => $request->is_active,
            ]);
        }

        // Update permissions: delete and re-create
        RestaurantPermission::where('user_id', $user->id)
            ->where('restaurant_id', $restaurantId)
            ->delete();
        if ($request->permissions) {
            foreach ($request->permissions as $permission) {
                RestaurantPermission::create([
                    'user_id' => $user->id,
                    'restaurant_id' => $restaurantId,
                    'permission_name' => $permission,
                    'granted' => true
                ]);
            }
            // Grant specific permissions using Spatie package
            $user->syncPermissions($request->permissions);
        } else {
            // If no permissions, remove all
            $user->syncPermissions([]);
        }

        return response()->json([
            'status' => 200,
            'msg' => 'Employee updated successfully.',
        ], 200);
    }

    /**
     * Handle profile picture upload
     */
    private function handleProfilePicture($request)
    {
        if ($request->profile_picture) {
            $directory = public_path() . '/users_pic';
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
            $imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('profile_picture')->getClientOriginalName());
            $request->file('profile_picture')->move($directory, $imageName);
            return 'users_pic/' . $imageName;
        }
        return null;
    }

    /**
     * Check if a user has a specific restaurant permission
     */
    public function checkPermission(Request $request)
    {
        $user = auth()->user();
        $restaurantId = $request->input('restaurant_id');
        $permissionName = $request->input('permission');

        $hasPermission = $user->hasRestaurantPermission($restaurantId, $permissionName);

        return response()->json([
            'has_permission' => $hasPermission
        ]);
    }

    /**
     * Manage restaurant-specific permissions
     */
    public function managePermissions(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));
        $restaurantId = $request->input('restaurant_id');
        $permissionName = $request->input('permission');
        $action = $request->input('action');  // 'grant' or 'revoke'

        if ($action === 'grant') {
            $user->assignRestaurantPermission($restaurantId, $permissionName);
        } else {
            $user->removeRestaurantPermission($restaurantId, $permissionName);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Permissions updated successfully'
        ]);
    }

    public function editProfile()
    {
        $user = \Auth::user();
        $id = $user->id;
        $info = User::find($id);
        return view('manage-users.updateProfile', compact('info'));
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
            'phone_number' => 'required|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed. Please check your input.',
            ], 422);
        }
        $profilePicturePath = $this->handleProfilePicture($request);
        $user = User::find($request->id);
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'profile_picture' => $profilePicturePath ? config('app.url') . '/' . $profilePicturePath : null,
        ]);
        return redirect()->route('manage-users');
        // Profile picture handling
    }

    public function activateAccount(Request $request, $userId)
    {
        \Log::info('activateAccount called', ['userId' => $userId, 'status' => $request->status, 'request' => $request->all()]);
        $user = User::find($userId);
        if ($user) {
            $user->status = $request->status;
            $user->save();
            \Log::info('User status updated', ['userId' => $userId, 'new_status' => $user->status]);
            return response()->json(['status' => 200]);
        } else {
            \Log::warning('User not found for status update', ['userId' => $userId]);
            return response()->json(['status' => 404]);
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'position' => 'required|string',
            'permissions' => 'nullable|array',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed. Please check your input.',
            ], 422);
        }
        $restaurantIds = Restaurant::where('owner_id', $user->id)->pluck('id')->first();
        return $restaurantIds;
        // Determine restaurant ID based on permission
        if (auth()->user()->role === 'admin') {
            $restaurantId = $restaurantIds->id;
        } else {
            $restaurantId = session('userData')['users']->restaurant_id;
        }
        // Handle profile picture
        $profilePicturePath = $this->handleProfilePicture($request);
        if ($profilePicturePath) {
            $user->profile_picture = $profilePicturePath;
        }

        // Update user details
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'role' => $request->position,
            'profile_picture' => $profilePicturePath ? config('app.url') . '/' . $profilePicturePath : null,
        ]);

        // Update restaurant employee details if exists
        $restaurantEmployee = RestaurantEmployee::where('user_id', $user->id)->first();
        if ($restaurantEmployee) {
            $restaurantEmployee->update([
                'position' => $request->position,
                'permissions' => json_encode($request->permissions ?? []),
                'is_active' => $request->is_active,
            ]);
        }

        // Update permissions: delete and re-create
        RestaurantPermission::where('user_id', $user->id)
            ->where('restaurant_id', $restaurantId)
            ->delete();
        if ($request->permissions) {
            foreach ($request->permissions as $permission) {
                RestaurantPermission::create([
                    'user_id' => $user->id,
                    'restaurant_id' => $restaurantId,
                    'permission_name' => $permission,
                    'granted' => true
                ]);
            }
            // Grant specific permissions using Spatie package
            $user->syncPermissions($request->permissions);
        } else {
            // If no permissions, remove all
            $user->syncPermissions([]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'User updated successfully',
            'redirect' => route('manage-users')
        ]);
    }

    public function search(Request $request)
    {
        $query = User::query();

        // Apply role filter
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }

        // Apply status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Apply restaurant filter (admin only)
        if (auth()->user()->role === 'admin' && $request->has('restaurant') && !empty($request->restaurant)) {
            $query->whereHas('restaurant', function ($q) use ($request) {
                $q->where('id', $request->restaurant);
            });
        }

        // Apply search term
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q
                    ->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('phone_number', 'like', "%{$searchTerm}%")
                    ->orWhere('address', 'like', "%{$searchTerm}%")
                    ->orWhere('role', 'like', "%{$searchTerm}%");
            });
        }

        // Get paginated results
        $users = $query
            ->with('restaurant')
            ->select(['id', 'name', 'email', 'phone_number', 'role', 'status', 'created_at'])
            ->paginate($request->length);

        // Format the response
        $data = $users->items()->map(function ($user) {
            // Determine if the toggle should be enabled for this user
            $currentUser = auth()->user();
            $currentUserRole = $currentUser->role;
            $targetUserRole = $user->role;
            $canToggle = false;
            if ($currentUser->id !== $user->id) {
                if ($currentUserRole === 'admin' && $targetUserRole === 'restaurant_owner') {
                    $canToggle = true;
                } elseif ($currentUserRole === 'restaurant_owner' && $user->id !== $currentUser->id) {
                    $canToggle = true;
                } elseif ($currentUserRole === 'manager' && $targetUserRole !== 'restaurant_owner') {
                    $canToggle = true;
                }
            }
            return [
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'restaurant_name' => $user->restaurant ? $user->restaurant->name : '-',
                'restaurant_phone' => $user->restaurant ? $user->restaurant->phone_number : '-',
                'restaurant_email' => $user->restaurant ? $user->restaurant->email : '-',
                'restaurant_address' => $user->restaurant ? $user->restaurant->address : '-',
                'role' => $user->restaurant_position ?? Str::title(str_replace('_', ' ', $user->role)),
                'created_at' => $user->created_at->format('Y-m-d'),
                'website' => $user->restaurant ? $user->restaurant->website : '',
                'request' => '',  // You can add request actions if needed
                'status' => view('manage-users.partials.status-toggle', compact('user', 'canToggle'))->render(),
                'action' => view('manage-users.partials.action-buttons', ['user' => $user])->render(),
            ];
        });

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $users->total(),
            'recordsFiltered' => $users->total(),
            'data' => $data
        ]);
    }

    public function edit(User $user)
    {
        $restaurantEmployee = \App\Models\RestaurantEmployee::where('user_id', $user->id)->first();
        $permissions = $restaurantEmployee ? json_decode($restaurantEmployee->permissions, true) : [];
        return response()->json([
            'status' => 200,
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'role' => $user->role,
                'status' => $user->status,
                'permissions' => $permissions,
            ]
        ]);
    }

    /**
     * Set the user's timezone from the browser
     */
    public function setTimezone(Request $request)
    {
        $request->validate(['timezone' => 'required|string']);
        session(['user_timezone' => $request->timezone]);
        if (auth()->check()) {
            $user = auth()->user();
            $user->timezone = $request->timezone;
            $user->save();
        }
        return response()->json(['status' => 'success']);
    }
}
