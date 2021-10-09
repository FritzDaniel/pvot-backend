<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Landing\LandingController;
use App\Http\Controllers\Api\Payment\InvoiceController;
use App\Http\Controllers\Api\Users\MembershipController;
use App\Http\Controllers\Api\Users\ShopsController;
use App\Http\Controllers\Api\Users\UsersController;
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

Route::group([ 'prefix' => 'v1'], function () {

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
        # Profile
        Route::get(
            'profile',
            [UsersController::class, 'profile']
        );
        # Get Balance
        Route::get(
            'wallet',
            [UsersController::class, 'getWallet']
        );
        # Get User Detail
        Route::get(
            'userDetail',
            [MembershipController::class, 'getUserDetail']
        );
        # Create User Detail
        Route::post(
            'userDetail/store',
            [MembershipController::class, 'storeUserDetail']
        );
        # Create Shop
        Route::get(
            'shop/list',
            [ShopsController::class, 'myShop']
        );
        # Create Shop
        Route::post(
            'shop/store',
            [ShopsController::class, 'shopCreate']
        );
        # Xendit
        Route::group([ 'prefix' => 'xendit'], function (){

            Route::post('invoice/retrieve/{id}',
                [InvoiceController::class,'getInvoice']
            );

            Route::post('invoice/create',
                [InvoiceController::class,'createInvoice']
            );
        });
        # Admin
        Route::group([ 'prefix' => 'admin'], function (){

            # Create Kategori
            Route::post(
                'storeCategory',
                [CategoryController::class, 'storeCategory']
            );
        });
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
        # List Supplier
        Route::get(
            'getSupplier',
            [LandingController::class, 'getSupplier']
        );
        # List Category
        Route::get(
            'getCategory',
            [LandingController::class, 'getCategory']
        );
        # List Design
        Route::get(
            'getDesign',
            [LandingController::class, 'getDesign']
        );
        # List Product
        Route::get(
            'getProduct',
            [LandingController::class, 'getProduct']
        );

        # Xendit
        Route::group([ 'prefix' => 'xendit'], function (){

            Route::post('invoice/callback',
                [InvoiceController::class,'callbackInvoice']
            );
        });
    });
});
