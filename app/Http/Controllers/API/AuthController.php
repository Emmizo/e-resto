<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\TwoFASetupMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\HasApiTokens;
use OTPHP\TOTP;
use PragmaRX\Google2FA\Google2FA;
use Mail;
use Socialite;

class AuthController extends Controller
{
    // Google login methods
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * client sign up.
     */
    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();
        // Generate Passport token
        $token = $user->createToken('AuthToken')->accessToken;
        event(new NewUserCreatedEvent($user));
        // Auto-login the user
        auth()->login($user);
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Login function.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $Authorized = User::where('email', $request->email)->where('status', 1)->first();
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $response = ['message' => 'User does not exist', 'status' => 404, 'success' => false];
            return response([$response, 404]);
        } else {
            if ($Authorized) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                    if ($user->has_2fa_enabled != 1) {
                        // Check if 2FA is enabled
                        $google2fa = new Google2FA();
                        $secretKey = $google2fa->generateSecretKey();

                        // Save the secret to the user
                        $user->google2fa_secret = $secretKey;
                        // $user->has_2fa_enabled = true;
                        // DEBUG: Get the current valid OTP (remove in production!)
                        $currentOtp = $google2fa->getCurrentOtp($secretKey);
                        Mail::to($user->email)->send(new TwoFASetupMail($currentOtp));

                        $user->save();
                        $response = [
                            'user' => [
                                'id' => $user->id,
                                'first_name' => $user->first_name,
                                'last_name' => $user->last_name,
                                'email' => $user->email,
                                'google2fa_secret' => $currentOtp,
                                'has_2fa_enabled' => $user->has_2fa_enabled,
                                'status' => $user->status,
                            ],
                            'token' => $token,
                            'success' => true,
                            'status' => 200,
                        ];
                    } else {
                        $response = [
                            'user' => [
                                'id' => $user->id,
                                'first_name' => $user->first_name,
                                'last_name' => $user->last_name,
                                'email' => $user->email,
                                'google2fa_secret' => $user->google2fa_secret,
                                'has_2fa_enabled' => $user->has_2fa_enabled,
                                'status' => $user->status,
                            ],
                            'token' => $token,
                            'success' => true,
                            'status' => 200,
                        ];
                    }
                    return response()->json([$response]);
                } else {
                    $response = ['message' => 'Password mismatch', 'success' => false, 'status' => 401];
                    return response([$response]);
                }
            } else {
                $response = ['message' => 'Your not allowed to access', 'success' => false, 'status' => 401];
                return response([$response, 401]);
            }
        }
        //
    }

    /**
     * Display a user info.
     */
    public function getUsers()
    {
        $domain = config('app.url');
        $user = User::where('id', auth()->user()->id)->first();
        $response = [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name . ' ' . $user->last_name,
                'fovorite_name' => $user->last_name,
                'email' => $user->email,
                'google2fa_secret' => $user->google2fa_secret,
                'has_2fa_enabled' => $user->has_2fa_enabled,
                'profile_picture' => $user->profile_picture == null ? '' : $domain . '/' . $user->profile_picture,
                'status' => $user->status,
            ],
            'base_url' => $domain,
            'success' => true,
            'status' => 200,
        ];
        return response()->json([$response]);
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
}
