<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;
use Spatie\Permission\Models\Permission;
use App\Models\RestaurantEmployee;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::get();
        $users = User::leftJoin('restaurants', function($join) {
            $join->on('users.id', '=', 'restaurants.owner_id');
        })
        ->leftJoin('restaurant_employees', function($join) {
            $join->on('restaurant_employees.restaurant_id', '=', 'restaurants.id')
                 ->on('restaurant_employees.user_id', '=', 'users.id');
        })
        ->select(
            'users.*',
            'restaurants.name as restaurant_name',
            'restaurants.address as restaurant_address',
            'restaurants.phone_number as restaurant_phone',
            'restaurants.email as restaurant_email',
            'restaurants.website',
            'restaurant_employees.position as employee_role',
            \DB::raw('CASE WHEN users.id = restaurants.owner_id THEN "Owner" ELSE "" END as is_owner')
        )
        ->when(auth()->user()->role !== 'admin', function ($query) {
            $query->where(function ($q) {
                if (auth()->user()->role === 'restaurant_owner') {
                    // Display only employees of the owner's restaurant
                    $q->where('restaurants.owner_id', auth()->user()->id);
                } else {
                    // Employees should only see their own data
                    $q->where('restaurant_employees.user_id', auth()->user()->id);
                }
            });
        })
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
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422, // Validation error status code
                'errors' => $validator->errors(), // Validation errors
                'message' => 'Validation failed. Please check your input.', // Optional message
            ], 422);
        }
        $restaurantId = view()->shared('data') ;

        return  $restaurantId->restaurant_id;
        $password = Str::random(8);
        // Handle profile picture upload
        $encryptpassword = Hash::make($password);
        if($request->profile_picture) {
            $directory = public_path().'/users_pic';
            if (!is_dir($directory)) {
                mkdir($directory);
                chmod($directory, 0777);
            }
            $imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('profile_picture')->getClientOriginalName());
            $request->file('profile_picture')->move($directory, $imageName);
            $profilePicturePath  = 'users_pic/'.$imageName;
        }
         // Create the user
         $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $encryptpassword,
            'plain_password' => $password,
            'role' => 'restaurant_owner', // Set default role
            'phone_number' => $request->phone_number,
            'profile_picture' => $profilePicturePath,
            'preferences' => json_encode([]),
        ]);
        RestaurantEmployee::create([
            'restaurant_id' => $restaurantId,
            'user_id' => $user->id,
            'position' => $request->position,
            'permissions' => json_encode([$request->permissions]),
            'is_active' => $request->is_active,
        ]);
        event(new NewUserCreatedEvent($user));
        return response()->json([
            'status' => 200, // Success status code
            'message' => 'Employee created successfully.', // Optional message
        ]);


    }

}
