<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventsController;

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
Route::get('/forgot-password', [AuthController::class,'forgotPassword'])->name('forgot-password');
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
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::get('/forgot', [AuthController::class, 'forgot'])->name('forgot');
Route::get('/manage-users', [UserController::class, 'index'])->name('manage-users');
Route::get('/manage-events', [EventsController::class, 'index'])->name('manage-events');
