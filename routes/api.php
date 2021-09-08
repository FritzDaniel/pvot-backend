<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Users\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Test Route
Route::get('/test',function (){
    return response()->json([
        'success' => true,
        'message' => "API test Successfully"
    ]);
});

Route::group([ 'prefix' => 'v1'], function (){
    // Routes for verified
    Route::group(['middleware' => ['auth:sanctum','verified']], function() {
        # Profile
        Route::get(
            'profile',
            [UsersController::class, 'profile']
        );
    });
    // Routes for send Email verification
    Route::group(['middleware' => 'auth:sanctum'], function() {
        # Email Verification
        Route::post(
            'email/verificationNotification',
            [AuthController::class, 'sendVerificationEmail']
        );
        # Logout
        Route::post(
            'logout',
            [AuthController::class, 'logout']
        );
    });
    // Route for guest
    Route::group(['middleware' => ['guest:api']], function () {
        # Verify The Email
        Route::get(
            'verifyEmail/{id}/{hash}',
            [AuthController::class, 'verify']
        )->name('verification.verify');
        # Login
        Route::post(
            'register',
            [AuthController::class, 'register']
        );
        # Register
        Route::post(
            'login',
            [AuthController::class, 'login']
        );
        # Register
        Route::post(
            'forgotPassword',
            [UsersController::class, 'forgotPassword']
        );
        # Reset Password
        Route::post(
            'resetPassword',
            [UsersController::class, 'changePassword']
        );
    });
});
