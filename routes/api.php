<?php
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::post('signup', [AuthController::class, 'signUp']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('getUserInfo', [AuthController::class, 'getUsers']);

    Route::post('auth/{google}/callback', [AuthController::class, 'handleGoogleCallback']);
    Route::post('deleteTask', [TaskController::class, 'deleteTask']);
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
});
// TOTP Routes
Route::group(['namespace' => 'Api', 'prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::post('2fa/generate', [AuthController::class, 'generate2FASecret']);
    Route::post('2fa/verify', [AuthController::class, 'verify2FA']);
    Route::post('/disable-2fa', [AuthController::class, 'disable2FA']);
    Route::post('logout', [AuthController::class, 'logout']);
});
