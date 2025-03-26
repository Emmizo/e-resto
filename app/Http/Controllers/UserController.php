<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;
use Spatie\Permission\Models\Permission;
use App\Models\RestaurantEmployee;
use Illuminate\Support\Facades\Validator;
use Str;
use Hash;
use App\Events\NewUserCreatedEvent;

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
            $join->on('restaurants.id', '=', 'restaurant_employees.restaurant_id')
                 ->orOn('restaurants.owner_id', '=', 'users.id'); // Include owner in employee list
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
            // Always include the current user
            $query->where('users.id', auth()->user()->id)
                  ->orWhere(function ($q) {
                      if (auth()->user()->role === 'restaurant_owner') {
                          // Restaurant owners should see their employees
                          $q->where('restaurants.owner_id', auth()->user()->id);
                      } elseif (auth()->user()->role === 'restaurant_employee') {
                          // Employees should see their workmates in the same restaurant
                          $q->whereIn('restaurant_employees.restaurant_id', function ($subquery) {
                              $subquery->select('restaurant_id')
                                  ->from('restaurant_employees')
                                  ->where('user_id', auth()->user()->id);
                          })
                          ->where('users.id', '!=', auth()->user()->id);
                      } elseif (auth()->user()->role === 'admin') {
                          // Admins should see all users associated with restaurants
                          $q->whereNotNull('restaurants.owner_id');
                      }
                  });
        })
        ->distinct()
        ->get();

    return view('manage-users.index', compact( 'users', 'permissions'));
        //
    }

    /**
     * create employeee for for joining the specified resource.
     */
    public function createEmployee(Request $request){


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
            'role' => "restaurant_employee",
            'phone_number' => $request->phone_number,
            'profile_picture' => $profilePicturePath,
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

        event(new NewUserCreatedEvent($user));

        return response()->json([
            'status' => 200,
            'msg' => 'Employee created successfully.',
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
        $action = $request->input('action'); // 'grant' or 'revoke'

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
}
