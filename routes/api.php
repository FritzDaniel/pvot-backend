<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Users\UsersController;
use Illuminate\Http\Request;
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
Route::get('/test',function (){
    return response()->json([
        'success' => true,
        'message' => "API test Successfully"
    ]);
});

Route::group([ 'prefix' => 'v1'], function (){
    Route::group(['middleware' => ['guest:api']], function () {
        Route::post(
            'register',
            [AuthController::class, 'register']
        );
        Route::post(
            'login',
            [AuthController::class, 'login']
        );
    });
    Route::group(['middleware' => 'auth:sanctum'], function() {
        # Logout
        Route::post(
            'logout',
            [AuthController::class, 'logout']
        );
        # Profile
        Route::get(
            'profile',
            [UsersController::class, 'profile']
        );
        # Email Verification
        Route::post(
            'email/verificationNotification',
            [AuthController::class, 'sendVerificationEmail']
        );
        Route::get(
            'verify-email/{id}/{hash}',
            [AuthController::class, 'verify']
        )->name('verification.verify');
    });
});
