<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\TwoFASetupMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\HasApiTokens;
use OpenApi\Annotations as OA;
use OTPHP\TOTP;
use PragmaRX\Google2FA\Google2FA;
use Mail;
use Socialite;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for user authentication"
 * )
 */

/**
 * @OA\Schema(
 *     schema="User",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="role", type="string", example="customer"),
 *     @OA\Property(property="restaurant_id", type="integer", format="int64", example=1, nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *     schema="Error",
 *     @OA\Property(property="status", type="string", example="error"),
 *     @OA\Property(property="message", type="string", example="Invalid credentials"),
 *     @OA\Property(
 *         property="errors",
 *         type="array",
 *         @OA\Items(type="string")
 *     )
 * )
 */
class AuthController extends Controller
{
    // Google login methods
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Register a new user.
     *
     * @OA\Post(
     *     path="/signup",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation", "role"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
     *             @OA\Property(property="role", type="string", enum={"customer", "restaurant_owner"}, example="customer"),
     *             @OA\Property(property="phone_number", type="string", example="+1234567890"),
     *             @OA\Property(property="address", type="string", example="123 Main St")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user",
     *                     ref="#/components/schemas/User"
     *                 ),
     *                 @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function signUp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20|regex:/^[0-9+\-() ]+$/',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            \Log::error('Signup validation failed', ['errors' => $validator->errors()->all()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'Client',
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'fcm_token' => $request->fcm_token,  // Add FCM token
                'status' => 1  // Set default status to active
            ]);

            // Create access token
            $token = $user->createToken('auth_token')->accessToken;

            DB::commit();

            \Log::info('User registered successfully', ['user_id' => $user->id]);

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'profile_picture' => $user->profile_picture,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'has_2fa_enabled' => $user->has_2fa_enabled ?? 0,
                    'status' => $user->status,
                    'fcm_token' => $user->fcm_token ?? null
                ],
                'token' => $token,
                'success' => true
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to register user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to register user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user and create token.
     *
     * @OA\Post(
     *     path="/login",
     *     summary="Login user and create token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="againtest2020@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Kwizera23")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", format="int64", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                     @OA\Property(property="phone", type="string", example="+1234567890"),
     *                     @OA\Property(property="address", type="string", example="123 Main St"),
     *                     @OA\Property(property="role", type="string", example="customer"),
     *                     @OA\Property(property="restaurant_id", type="integer", format="int64", example=1, nullable=true),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Invalid credentials"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
                'fcm_token' => 'nullable|string'  // Add validation for FCM token
            ]);

            if ($validator->fails()) {
                \Log::error('Login validation failed', ['errors' => $validator->errors()->all()]);
                return response()->json(['errors' => $validator->errors()->all()], 422);
            }

            \Log::info('Login attempt', ['email' => $request->email]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                \Log::warning('Login failed: User not found', ['email' => $request->email]);
                return response()->json([
                    'message' => 'User does not exist',
                    'success' => false
                ], 404);
            }

            if ($user->status != 1) {
                \Log::warning('Login failed: User inactive', ['email' => $request->email]);
                return response()->json([
                    'message' => 'You are not allowed to access',
                    'success' => false
                ], 401);
            }

            if (!Hash::check($request->password, $user->password)) {
                \Log::warning('Login failed: Password mismatch', ['email' => $request->email]);
                return response()->json([
                    'message' => 'Password mismatch',
                    'success' => false
                ], 401);
            }

            try {
                // Revoke any existing tokens
                $user->tokens()->delete();

                // Create new token with specific scopes
                $token = $user->createToken('RestoFinder Personal Access Client')->accessToken;

                // Update FCM token if provided
                if ($request->has('fcm_token')) {
                    $user->fcm_token = $request->fcm_token;
                    $user->save();
                }

                \Log::info('Token created successfully', [
                    'user_id' => $user->id,
                    'token_type' => 'Bearer'
                ]);

                if ($user->has_2fa_enabled != 1) {
                    // Check if 2FA is enabled
                    $google2fa = new Google2FA();
                    $secretKey = $google2fa->generateSecretKey();

                    // Save the secret to the user
                    $user->google2fa_secret = $secretKey;

                    // Get the current valid OTP
                    $currentOtp = $google2fa->getCurrentOtp($secretKey);
                    Mail::to($user->email)->send(new TwoFASetupMail($currentOtp));

                    $user->save();
                }

                $userData = [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'profile_picture' => $user->profile_picture,
                    'phone_number' => $user->phone_number,
                    'email' => $user->email,
                    'has_2fa_enabled' => $user->has_2fa_enabled,
                    'status' => $user->status,
                    'fcm_token' => $user->fcm_token  // Include FCM token in response
                ];

                // Only include 2FA details if needed
                if ($user->has_2fa_enabled != 1) {
                    $userData['google2fa_secret'] = $currentOtp;
                }

                \Log::info('Login successful', ['user_id' => $user->id]);
                return response()->json([
                    'user' => $userData,
                    'token' => $token,
                    'success' => true
                ], 200);
            } catch (\Exception $e) {
                \Log::error('Token creation failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                // Check if it's a key-related error
                if (str_contains($e->getMessage(), 'key')) {
                    return response()->json([
                        'message' => 'Authentication system error. Please contact support.',
                        'success' => false,
                        'error' => 'Key configuration error'
                    ], 500);
                }

                return response()->json([
                    'message' => 'Token creation failed: ' . $e->getMessage(),
                    'success' => false
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Login failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'email' => $request->email ?? 'no email provided'
            ]);
            return response()->json([
                'message' => 'Login failed: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Get authenticated user.
     *
     * @OA\Get(
     *     path="/getUserInfo",
     *     summary="Get authenticated user",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", format="int64", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                 @OA\Property(property="phone", type="string", example="+1234567890"),
     *                 @OA\Property(property="address", type="string", example="123 Main St"),
     *                 @OA\Property(property="role", type="string", example="customer"),
     *                 @OA\Property(property="restaurant_id", type="integer", format="int64", example=1, nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function getUsers()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'message' => 'User not authenticated',
                    'success' => false
                ], 401);
            }

            $domain = config('app.url');
            $response = [
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name . ' ' . $user->last_name,
                    'favorite_name' => $user->last_name,
                    'email' => $user->email,
                    'google2fa_secret' => $user->google2fa_secret,
                    'has_2fa_enabled' => $user->has_2fa_enabled,
                    'profile_picture' => $user->profile_picture ? $domain . '/' . $user->profile_picture : null,
                    'status' => $user->status,
                ],
                'base_url' => $domain,
                'success' => true,
                'status' => 200,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Get user info failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to get user information',
                'success' => false
            ], 500);
        }
    }

    /**
     * Login with sociale login.
     */
    public function handleGoogleCallback(Request $request)
    {
        $provider = $request->input('provider');
        $accessToken = $request->input('access_token');

        Log::info('Social login attempt', [
            'provider' => $provider,
            'token_length' => strlen($accessToken ?? '')
        ]);

        if (!in_array($provider, ['google', 'github'])) {
            return response()->json(['message' => 'Invalid provider'], 400);
        }

        try {
            // Let Socialite handle the token validation
            $socialUser = Socialite::driver($provider)->userFromToken($accessToken);

            // Get user data
            $email = $socialUser->getEmail();
            $name = $socialUser->getName();
            $providerId = $socialUser->getId();

            // Get profile image URL
            $avatarUrl = $socialUser->getAvatar();
            $profileImagePath = null;

            // Download and store profile image if available
            if ($avatarUrl) {
                $profileImagePath = $this->saveProfileImage($avatarUrl, $email, $provider);
            }

            // Extract first and last name from full name
            $nameParts = explode(' ', $name);
            $firstName = $nameParts[0] ?? '';
            $lastName = count($nameParts) > 1 ? end($nameParts) : '';

            // Create or update user with all required fields
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'provider' => $provider,
                    'provider_id' => $providerId,
                    'profile_picture' => $profileImagePath,  // Save profile image path
                    'password' => bcrypt(\Illuminate\Support\Str::random(16)),  // Random password for social users
                    // If you have other required fields, add them here
                ]
            );

            // Create token
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;

            // Return response in the same format as normal login
            $response = [
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'google2fa_secret' => $user->google2fa_secret,
                    'has_2fa_enabled' => $user->has_2fa_enabled,
                    'status' => $user->status ?? 1,
                ],
                'token' => $token,
                'success' => true,
                'status' => 200,
            ];

            return response()->json([$response]);
        } catch (\Exception $e) {
            Log::error('Social login error: ' . $e->getMessage());
            $response = ['message' => 'Social login failed: ' . $e->getMessage(), 'success' => false, 'status' => 401];
            return response([$response, 401]);
        }
    }

    // 2fa

    public function generate2FASecret()
    {
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $google2fa = new Google2FA();
        $secretKey = $google2fa->generateSecretKey();

        // Save the secret to the user
        $user->google2fa_secret = $secretKey;
        $user->has_2fa_enabled = true;
        // DEBUG: Get the current valid OTP (remove in production!)
        $currentOtp = $google2fa->getCurrentOtp($secretKey);
        Mail::to($user->email)->send(new TwoFASetupMail($currentOtp));
        $user->save();

        return response()->json([
            'secret' => $secretKey,
            'qr_code' => 'otpauth://totp/MyApp?secret=' . $secretKey . '&issuer=MyApp',
            'current_otp' => $currentOtp,
        ]);
    }

    // verify that the 2fa code is correct
    public function verify2FA(Request $request)
    {
        // return $request->otp;
        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:6'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Check if user has 2FA secret set
        if (!$user->google2fa_secret) {
            return response()->json(['error' => '2FA not set up for this user'], 400);
        }

        $google2fa = new Google2FA();
        $isValid = $google2fa->verifyKey($user->google2fa_secret, $request->otp);
        $user->has_2fa_enabled = true;
        $user->save();
        if ($isValid) {
            return response()->json(['message' => '2FA verified successfully'], 200);
        }

        return response()->json(['error' => 'Invalid OTP'], 401);
    }

    public function disable2FA()
    {
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Check if 2FA is even enabled
        if (!$user->google2fa_secret) {
            return response()->json(['error' => '2FA is not enabled for this user'], 400);
        }

        // Disable 2FA by clearing the secret
        $user->google2fa_secret = null;
        $user->has_2fa_enabled = false;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => '2FA has been disabled successfully'
        ]);
    }

    /**
     * Logout user (Revoke the token).
     *
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="Logout user (Revoke the token)",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged out"),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return response()->json([
                'message' => 'Successfully logged out',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Logout failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Logout failed',
                'success' => false
            ], 500);
        }
    }

    public function storeFcmToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'fcm_token' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            $user->fcm_token = $request->fcm_token;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'FCM token stored successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to store FCM token', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to store FCM token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update only the authenticated user's profile picture.
     *
     * @OA\Post(
     *     path="/user/profile-picture",
     *     summary="Update profile picture",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"profile_picture"},
     *                 @OA\Property(
     *                     property="profile_picture",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile picture updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Profile picture updated successfully"),
     *             @OA\Property(property="profile_picture", type="string", example="/storage/users_pic/abc.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function updateProfilePicture(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        }

        // Store the uploaded file in public/users_picture
        $file = $request->file('profile_picture');
        $filename = uniqid('profile_') . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('users_picture');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $file->move($destinationPath, $filename);
        $relativePath = 'users_picture/' . $filename;
        $fullUrl = rtrim(config('app.url'), '/') . '/' . $relativePath;

        // Optionally, delete the old picture if needed
        // if ($user->profile_picture) {
        //     @unlink(public_path($user->profile_picture));
        // }

        $user->profile_picture = $fullUrl;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile picture updated successfully',
            'profile_picture' => $fullUrl
        ]);
    }
}
