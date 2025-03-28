<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\DashboardController;

/* Logout */
Route::get('/logout', function () {
    \Auth::logout();
    return redirect(route('login'));
})->name('logout');

Route::group([ 'middleware' => ['auth','nocache'],'namespace' => 'App\Http\Controllers'], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
});
Route::get('/', [AuthController::class,'index'])->name('login');
Route::any('/login', [AuthController::class,'login'])->name('admin-login-post');
Route::post('/signup', [AuthController::class,'signUp'])->name('signup');
Route::get('/forgot-password', [AuthController::class,'forgot'])->name('forgot-password');
Route::post('/welcome', [AuthController::class,'welcomeEmail'])->name('welcome-post');
Route::post('/forgot-password', [AuthController::class,'store'])->name('forgot-password-post');
Route::get('/reset-password/{token}', [AuthController::class,'viewReset'])->name('reset-password');
Route::get('/reset/{id}', [AuthController::class,'Reset'])->name('reset');
Route::post('/reset-password', [AuthController::class,'storePassword'])->name('reset-password-post');
Route::post('/reset', [AuthController::class,'storePasswordReset'])->name('reset-post');
Route::get('/change-password', [AuthController::class,'viewChangePassword'])->name('change-password');
Route::post('/change-password', [AuthController::class,'storeNewPassword'])->name('change-password-post');

#Profile module
Route::group(['prefix' => '/users', 'middleware' => ['auth','nocache'],'namespace' => 'App\Http\Controllers'], function () {
    Route::post('update-profile', 'UserController@updateProfile')->name('manage-update-profile');
    Route::get('edit-profile', 'UserController@editProfile')->name('manage-edit-profile');

});
// , 'restaurant.permission:mange-users'
Route::group([
    'middleware' => ['auth', 'nocache', 'restaurant.permission:User_Management'],
], function () {
    // User Management Routes
    Route::post('/create-employee', [UserController::class, 'createEmployee'])->name('create-employee');

    Route::get('/manage-users', [UserController::class, 'index'])->name('manage-users');


});

Route::group([ 'middleware' => ['auth','nocache', 'restaurant.permission:Role_Management']], function () {
// Role Management Routes
Route::post('/create-role', [RoleController::class, 'store'])->name('create-role');

Route::get('/roles', [RoleController::class, 'index'])->name('roles');
});

Route::group([ 'middleware' => ['auth','nocache', 'restaurant.permission:Menu_Management']], function () {
Route::get('/manage-menu', [MenuController::class, 'index'])->name('manage-menu');

Route::post('/menu-store', [MenuController::class, 'store'])->name('menu-store');
Route::get('/menus/{menu}/edit',[MenuController::class, 'edit']);
    Route::put('/menus/{menu}', [MenuController::class,'update']);
    Route::patch('/menus/{menu}/status', [MenuController::class,'updateStatus']);
    Route::delete('/menus/{menu}',[MenuController::class,'destroy']);
});
