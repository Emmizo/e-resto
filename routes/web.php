<?php

use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\AdminTermsAndConditionsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TermsAndConditionsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/firebase-config', [FirebaseController::class, 'getConfig']);
/* Logout */
Route::get('/logout', function () {
    \Auth::logout();
    return redirect(route('login'));
})->name('logout');

Route::group(['middleware' => ['auth', 'nocache'], 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
});
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::any('/login', [AuthController::class, 'login'])->name('admin-login-post');
Route::post('/signup', [AuthController::class, 'signUp'])->name('signup');
Route::get('/forgot-password', [AuthController::class, 'forgot'])->name('forgot-password');
Route::post('/welcome', [AuthController::class, 'welcomeEmail'])->name('welcome-post');
Route::post('/forgot-password', [AuthController::class, 'store'])->name('forgot-password-post');
Route::get('/reset-password/{token}', [AuthController::class, 'viewReset'])->name('reset-password');
Route::get('/reset/{id}', [AuthController::class, 'Reset'])->name('reset');
Route::post('/reset-password', [AuthController::class, 'storePassword'])->name('reset-password-post');
Route::post('/reset', [AuthController::class, 'storePasswordReset'])->name('reset-post');
Route::get('/change-password', [AuthController::class, 'viewChangePassword'])->name('change-password');
Route::post('/change-password', [AuthController::class, 'storeNewPassword'])->name('change-password-post');

// Profile module
Route::group(['prefix' => '/users', 'middleware' => ['auth', 'nocache'], 'namespace' => 'App\Http\Controllers'], function () {
    Route::post('update-profile', 'UserController@updateProfile')->name('manage-update-profile');
    Route::get('edit-profile', 'UserController@editProfile')->name('manage-edit-profile');
});
// , 'restaurant.permission:mange-users'
Route::group([
    'middleware' => ['auth', 'nocache', 'restaurant.permission:User_Management'],
], function () {
    // User Management Routes
    Route::post('/create-employee', [UserController::class, 'createEmployee'])->name('create-employee');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('/manage-users', [UserController::class, 'index'])->name('manage-users');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
});

Route::group(['middleware' => ['auth', 'nocache', 'restaurant.permission:Role_Management']], function () {
    // Role Management Routes
    Route::post('/create-role', [RoleController::class, 'store'])->name('create-role');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles');
});

Route::group(['middleware' => ['auth', 'nocache', 'restaurant.permission:Menu_Management']], function () {
    Route::get('/manage-menu', [MenuController::class, 'index'])->name('manage-menu');

    Route::post('/menu-store', [MenuController::class, 'store'])->name('menu-store');
    Route::get('/menu/{id}/edit', [MenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{id}', [MenuController::class, 'update'])->name('menu.update');
    Route::patch('/menu/{id}/status', [MenuController::class, 'updateStatus'])->name('menu.status');
    Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');
    Route::get('menu/{menuId}/edit', [MenuController::class, 'getMenuItems']);
    Route::patch('user/{userId}/status', [UserController::class, 'activateAccount']);
    Route::patch('/menu-items/{id}/toggle-status', [App\Http\Controllers\MenuController::class, 'toggleStatus'])->name('menu-items.toggle-status');
});

// Order Management Routes
Route::group([
    'middleware' => ['auth', 'nocache'],
], function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::put('/orders/{order}/status-update', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// Reservation Management Routes
Route::middleware(['auth', 'nocache', 'restaurant.permission:Reservation_Management'])->group(function () {
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
});

Route::post('/api/store-fcm-token', [App\Http\Controllers\API\AuthController::class, 'storeFcmToken'])->name('store.fcm.token');
Route::post('/save-fcm-token', [FirebaseController::class, 'saveFcmToken']);

Route::get('/terms-and-conditions', [TermsAndConditionsController::class, 'show'])->name('terms.show');

Route::middleware(['auth', 'nocache'])->group(function () {
    Route::get('/admin/terms', [AdminTermsAndConditionsController::class, 'index'])->name('admin.terms.index');
    Route::get('/admin/terms/create', [AdminTermsAndConditionsController::class, 'create'])->name('admin.terms.create');
    Route::post('/admin/terms', [AdminTermsAndConditionsController::class, 'store'])->name('admin.terms.store');
    Route::get('/admin/terms/{id}/edit', [AdminTermsAndConditionsController::class, 'edit'])->name('admin.terms.edit');
    Route::put('/admin/terms/{id}', [AdminTermsAndConditionsController::class, 'update'])->name('admin.terms.update');
    Route::get('/admin/restaurants', [\App\Http\Controllers\DashboardController::class, 'listRestaurants'])->name('admin.restaurants.index');
    Route::post('/admin/restaurants/{id}/approve', [\App\Http\Controllers\DashboardController::class, 'approveRestaurant'])->name('admin.restaurants.approve');
    Route::post('/dashboard/toggle-service', [\App\Http\Controllers\DashboardController::class, 'toggleService'])->name('dashboard.toggle-service');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('promo-banners', \App\Http\Controllers\PromoBannerController::class);
});

Route::middleware('auth')->get('/notifications', [NotificationController::class, 'all'])->name('notifications.all');
Route::middleware('auth')->get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
Route::middleware('auth')->post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

Route::post('/set-timezone', [App\Http\Controllers\UserController::class, 'setTimezone'])->name('set-timezone');

Route::get('/dashboard/chart-data', [App\Http\Controllers\DashboardController::class, 'getChartData'])
    ->name('dashboard.chart-data')
    ->middleware(['auth']);

Route::get('/admin/tables', [App\Http\Controllers\AdminTableController::class, 'index'])->name('admin.tables.index');
Route::post('/admin/tables', [App\Http\Controllers\AdminTableController::class, 'store'])->name('admin.tables.store');
Route::patch('/admin/tables/{id}', [App\Http\Controllers\AdminTableController::class, 'update'])->name('admin.tables.update');
Route::post('/admin/tables/{id}/toggle-status', [App\Http\Controllers\AdminTableController::class, 'toggleStatus'])->name('admin.tables.toggle-status');

Route::get('/test-push', function () {
    app(\App\Services\PusherBeamsService::class)->notifyInterests(
        ['user-' . auth()->id()],
        [
            'title' => 'Test Notification',
            'body' => 'This is a test push notification from Pusher Beams!',
            'icon' => '/icon.png', // Optional: update path if you have a custom icon
            'deep_link' => url('/dashboard')
        ]
    );
    return 'Notification sent!';
})->middleware('auth');
