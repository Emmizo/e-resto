<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function forgot()
    {
       return view('auth.forget-password'); //
    }
/**
     * This function is used to return form via on your email account
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */
    public function viewReset(Request $request)
    {


        $data['email'] = $request->email;
        $data['token'] = $request->token;
        return view('auth.reset-password',$data);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Register a new restaurant owner and their restaurant.
     */
    public function signUp(Request $request)
    {
        // Validate user data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Restaurant validation
            'restaurant_name' => 'required|string|max:255',
            'restaurant_description' => 'required|string',
            'restaurant_address' => 'required|string',
            'restaurant_longitude' => 'nullable|numeric',
            'restaurant_latitude' => 'nullable|numeric',
            'restaurant_phone' => 'required|string|max:20',
            'restaurant_email' => 'required|email|max:255',
            'restaurant_website' => 'nullable|url',
            'restaurant_opening_hours' => 'required|string',
            'restaurant_cuisine_type' => 'required|string',
            'restaurant_price_range' => 'required|string',
            'restaurant_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle profile picture upload
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Handle restaurant image upload
        $restaurantImagePath = null;
        if ($request->hasFile('restaurant_image')) {
            $restaurantImagePath = $request->file('restaurant_image')->store('restaurant_images', 'public');
        }

        // Create the user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'restaurant_owner', // Set default role
            'phone_number' => $request->phone_number,
            'profile_picture' => $profilePicturePath,
            'preferences' => json_encode([]),
        ]);

        // Create the restaurant
        Restaurant::create([
            'name' => $request->restaurant_name,
            'description' => $request->restaurant_description,
            'address' => $request->restaurant_address,
            'longitude' => $request->restaurant_longitude,
            'latitude' => $request->restaurant_latitude,
            'phone_number' => $request->restaurant_phone,
            'email' => $request->restaurant_email,
            'website' => $request->restaurant_website,
            'opening_hours' => $request->restaurant_opening_hours,
            'cuisine_type' => $request->restaurant_cuisine_type,
            'price_range' => $request->restaurant_price_range,
            'image' => $restaurantImagePath,
            'owner_id' => $user->id,
            'is_approved' => false, // Default to not approved
        ]);

        // Auto-login the user
        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Registration successful! Your restaurant will be reviewed by our team.');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
