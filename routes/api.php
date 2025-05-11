<?php
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes (No Authentication Required)
Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    // Authentication Routes
    Route::post('signup', [AuthController::class, 'signUp']);
    Route::post('login', [AuthController::class, 'login']);

    // Social Login Routes
    Route::post('auth/{google}/callback', [AuthController::class, 'handleGoogleCallback']);
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    // Public API endpoint for listing cuisines
    Route::get('/cuisines', [\App\Http\Controllers\API\MenuController::class, 'listCuisines']);
    // Public API endpoint for listing promo banners with restaurant details
    Route::get('/promo-banners-with-restaurant', [\App\Http\Controllers\API\PromoBannerController::class, 'listWithRestaurant']);
});

// Protected Routes (Authentication Required)
Route::group(['namespace' => 'Api', 'prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::get('getUserInfo', [AuthController::class, 'getUsers']);
    // 2FA Routes
    Route::post('2fa/generate', [AuthController::class, 'generate2FASecret']);
    Route::post('2fa/verify', [AuthController::class, 'verify2FA']);
    Route::post('/disable-2fa', [AuthController::class, 'disable2FA']);
    Route::post('logout', [AuthController::class, 'logout']);

    // Profile picture update
    Route::post('user/profile-picture', [AuthController::class, 'updateProfilePicture']);

    // Change password
    Route::post('user/change-password', [AuthController::class, 'changePassword']);

    // Restaurant routes
    Route::get('/restaurants', [App\Http\Controllers\Api\RestaurantController::class, 'index']);

    // Order routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

    // Reservation routes
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
    Route::post('/reservations/{id}/cancel', [\App\Http\Controllers\API\ReservationController::class, 'cancel'])->name('api.reservations.cancel');

    // Review routes
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews/user', [ReviewController::class, 'userReviews']);

    // Favorite menu item routes
    Route::post('/menu-items/favorite', [\App\Http\Controllers\API\MenuController::class, 'favoriteMenuItem']);
    Route::post('/menu-items/unfavorite', [\App\Http\Controllers\API\MenuController::class, 'unfavoriteMenuItem']);
    Route::get('/menu-items/favorites', [\App\Http\Controllers\API\MenuController::class, 'listFavoriteMenuItems']);

    // Favorite restaurant routes
    Route::post('/restaurants/favorite', [\App\Http\Controllers\Api\RestaurantController::class, 'favoriteRestaurant']);
    Route::post('/restaurants/unfavorite', [\App\Http\Controllers\Api\RestaurantController::class, 'unfavoriteRestaurant']);
    Route::get('/restaurants/favorites', [\App\Http\Controllers\Api\RestaurantController::class, 'listFavoriteRestaurants']);

    // Final stats summary
    Route::get('/final-stats', [\App\Http\Controllers\API\StatsController::class, 'finalStats']);

    // Update user profile
    Route::put('/user/profile', [\App\Http\Controllers\API\AuthController::class, 'updateProfile']);

    // Promo Banner routes
    Route::apiResource('/promo-banners', \App\Http\Controllers\API\PromoBannerController::class);
});

Route::get('/restaurants/{restaurant}/reviews', [ReviewController::class, 'restaurantReviews']);

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "api" middleware group. Make something great!
 * |
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication Routes
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

// Protected Routes
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/user', [AuthenticationController::class, 'user']);
});

// Order Management Routes
Route::middleware('auth:api')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
});
