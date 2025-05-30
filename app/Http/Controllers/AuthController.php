<?php

namespace App\Http\Controllers;

use App\Events\NewUserCreatedEvent;
use App\Events\ResetPasswordEvent;
use App\Events\WelcomeEmailEvent;
use App\Models\Restaurant;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use Exception;
use Password;
use Str;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cuisines = \App\Models\Cuisine::all();
        return view('auth.login', compact('cuisines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function forgot()
    {
        return view('auth.forget-password');  //
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
        return view('auth.reset-password', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

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
            'phone_number' => 'required|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fcm_token' => 'nullable|string|min:152|max:200',  // Add FCM token validation
            // Restaurant validation
            'restaurant_name' => 'required|string|max:255',
            'restaurant_description' => 'required|string',
            'restaurant_address' => 'required|string',
            'restaurant_longitude' => 'nullable|numeric',
            'restaurant_latitude' => 'nullable|numeric',
            'restaurant_phone_number' => 'required|string|max:20',
            'restaurant_email' => 'required|email|max:255',
            'restaurant_website' => 'nullable|url',
            'restaurant_opening_hours' => 'nullable|string',
            'restaurant_cuisine_id' => 'required|exists:cuisines,id',
            'restaurant_price_range' => 'required|string',
            'restaurant_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed. Please check your input.',
            ], 422);
        }

        $password = Str::random(8);
        $encryptpassword = Hash::make($password);
        $profilePicturePath = $this->handleProfilePicture($request);
        $restaurantImagePath = $this->handleRestaurantImage($request);

        // Create the user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $encryptpassword,
            'role' => 'restaurant_owner',
            'phone_number' => $request->phone_number,
            'profile_picture' => $profilePicturePath,
            'preferences' => json_encode([]),
            'fcm_token' => $request->fcm_token,  // Add FCM token
        ]);

        // Create the restaurant
        Restaurant::create([
            'name' => $request->restaurant_name,
            'description' => $request->restaurant_description,
            'address' => $request->restaurant_address,
            'longitude' => $request->restaurant_longitude,
            'latitude' => $request->restaurant_latitude,
            'phone_number' => $request->restaurant_phone_number,
            'email' => $request->restaurant_email,
            'website' => $request->restaurant_website,
            'opening_hours' => $request->restaurant_opening_hours,
            'cuisine_type' => '',  // Deprecated, keep for now
            'cuisine_id' => $request->restaurant_cuisine_id,
            'price_range' => $request->restaurant_price_range,
            'image' => config('app.url') . '/' . $restaurantImagePath,
            'owner_id' => $user->id,
            'is_approved' => false,
        ]);

        event(new NewUserCreatedEvent($user, $password));
        auth()->login($user);

        return response()->json([
            'msg' => 'success',
            'status' => 201,
            'user' => [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'fcm_token' => $user->fcm_token
            ]
        ], 201);
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
     * Handle restaurant image upload
     */
    private function handleRestaurantImage($request)
    {
        if ($request->restaurant_image) {
            $directory = public_path() . '/restaurant_pic';
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
            $imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('restaurant_image')->getClientOriginalName());
            $request->file('restaurant_image')->move($directory, $imageName);
            return 'restaurant_pic/' . $imageName;
        }
        return null;
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
                'fcm_token' => 'nullable|string|min:152|max:200'  // Add FCM token validation
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'msg' => 'Validation failed',
                    'status' => 422,
                    'errors' => $validator->errors()
                ], 422);
            }

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                if ($user->status == 1 || $user->role != 'client') {
                    // Update FCM token if provided
                    if ($request->has('fcm_token')) {
                        $user->fcm_token = $request->fcm_token;
                        $user->save();
                    }

                    return response()->json([
                        'msg' => 'success',
                        'status' => 201,
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->first_name . ' ' . $user->last_name,
                            'email' => $user->email,
                            'fcm_token' => $user->fcm_token
                        ]
                    ], 201);
                } else {
                    return response()->json([
                        'msg' => 'Your account is deactivated',
                        'status' => 401
                    ], 401);
                }
            } else {
                return response()->json([
                    'msg' => 'Wrong credentials',
                    'status' => 401
                ], 401);
            }
        } catch (Exception $e) {
            \Log::error('Login failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'msg' => 'An error occurred during login',
                'status' => 500
            ], 500);
        }
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
     * This function is used to return form via on your email account
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */
    public function Reset(Request $request)
    {
        $data['email'] = $request->email;
        $data['token'] = $request->token;
        $data['users'] = User::where('id', $request->id)->get();
        return view('auth.reset', $data);
    }

    /**
     * This function is used to store password
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */
    public function storePassword(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'token' => 'required',
                'email' => 'required|email',
                'password' => ['required', 'confirmed'],
            ]);
            if ($validator->fails()) {
                return back()->withErrors($validator);
            }
            // Here we will attempt to reset the user's password. If it is successful we
            // will update the password on an actual user model and persist it to the
            // database. Otherwise we will parse the error and return the response.
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        // 'account_verified'=>1,
                        'password' => \Hash::make($request->password),
                        'remember_token' => \Str::random(60),
                    ])->save();
                    event(new PasswordReset($user));
                }
            );

            if ($status == Password::PASSWORD_RESET) {
                $request->session()->flash('success', 'Please login again with updated password');
                return redirect()->route('login');
            }
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        } catch (Exception $e) {
            return back()->withErrors(['errors' => 'Something went wrong ' . $e->getMessage()]);
        }
    }

    /**
     * This function is used to store password
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */
    public function storePasswordReset(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email'
            ]);
            if ($validator->fails()) {
                return redirect(route('forgot-password'))
                    ->withErrors($validator)
                    ->withInput();
            }
            $email = $request->email;
            $user = User::where('email', $email)->first();
            if ($user == null) {
                $request->session()->flash('error', 'Email is not exist in database');
                return redirect(route('forgot-password'));
            }
            event(new ResetPasswordEvent($email));
            $request->session()->flash('link_sent', 'Reset link is successfully sent');
            return response()->json(['status' => 200, 'message' => 'Other cert Item add', 'data' => $user]);
        } catch (Exception $e) {
        }
    }

    public function welcomeEmail(Request $request)
    {
        $users = User::leftJoin('restaurants', function ($join) {
            $join->on('users.id', '=', 'restaurants.owner_id');
        })
            ->leftJoin('restaurant_employees', function ($join) {
                $join
                    ->on('restaurant_employees.restaurant_id', '=', 'restaurants.id')
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
            ->where('users.email', $request->email)
            ->first();
        event(new WelcomeEmailEvent($users));
        return response()->json(['msg' => 'success reset link sent', 'status' => 201], 201);
    }

    /**
     * This function is used to send link on email
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */
    public function store(Request $request)
    {
        try {
            $request->email;
            $validator = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,  // Validation error status code
                    'errors' => $validator->errors(),  // Validation errors
                    'message' => 'Validation failed. Please check your input.',  // Optional message
                ], 422);
            }
            $email = $request->email;
            $user = User::where('email', $email)->first();
            if ($user == null) {
                return response()->json([
                    'status' => 422,  // Validation error status code
                    'errors' => $validator->errors(),  // Validation errors
                    'message' => 'Validation failed. Please check your input.',  // Optional message
                ], 422);
            }
            event(new ResetPasswordEvent($email));
            return response()->json(['msg' => 'success reset link sent', 'status' => 201], 201);
        } catch (Exception $e) {
        }
        //
    }

    /**
     * Show the change password form.
     */
    public function viewChangePassword()
    {
        return view('auth.change-password');
    }

    /**
     * Handle the change password form submission.
     */
    public function storeNewPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);

        $user = auth()->user();

        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = \Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully!');
    }
}
