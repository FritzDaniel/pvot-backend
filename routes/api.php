<?php

use App\Http\Controllers\Api\Admin\VariantController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Landing\LandingController;
use App\Http\Controllers\Api\Payment\InvoiceController;
use App\Http\Controllers\Api\Users\MembershipController;
use App\Http\Controllers\Api\Users\PaymentController;
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
        # Get Order List
        Route::get(
            'getOrderList',
            [PaymentController::class,'getOrderList']
        );
        Route::get(
            'getTransactionList/{id}',
            [PaymentController::class,'getTransactionList']
        );
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
        # Update Profile
        Route::post(
            'profile/update',
            [UsersController::class,'updateProfile']
        );
        # Update Password
        Route::post(
            'updatePassword',
            [UsersController::class,'updatePassword']
        );
        Route::get(
            'transactionHistory',
            [UsersController::class,'transactionHistory']
        );
        Route::group(['middleware' => ['role:Dropshipper']], function () {
            # Create Shop
            Route::post(
                'cart/store',
                [PaymentController::class, 'beliProduct']
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
        });
    });
    // Route for guest
    Route::group(['middleware' => ['guest:api']], function () {
        # Get Summary Product
        Route::get(
            'cartSummary/{id}',
            [PaymentController::class, 'cartSummary']
        );
        # Get Payment Callback Moota
        Route::post(
            '/webhook/callback/BCA',
            [PaymentController::class, 'getWebhookCallback']
        );
        # Get Payment Callback Moota
        Route::post(
            '/webhook/callback/Mandiri',
            [PaymentController::class, 'getWebhookCallback']
        );
        # Check Payment Moota
        Route::get(
            '/check/payment/{id}',
            [PaymentController::class, 'checkPayment']
        );
        # Store Detail Payment & Create Invoice
        Route::post(
            'detailPembayaran/store',
            [MembershipController::class, 'detailPayment']
        );
        # Xendit
        Route::group([ 'prefix' => 'xendit'], function (){

            Route::post('invoice/callback',
                [InvoiceController::class,'callbackInvoice']
            );

            Route::post('invoice/retrieve/{id}',
                [InvoiceController::class,'getInvoice']
            );

            Route::post('invoice/create',
                [InvoiceController::class,'createInvoice']
            );
        });
        # Verify The Email
        Route::get(
            'verifyEmail/{id}/{hash}',
            [AuthController::class,'verify']
        )->name('verification.verify');
        # Login
        Route::post(
            'register',
            [AuthController::class,'register']
        );
        # Register
        Route::post(
            'login',
            [AuthController::class,'login']
        );
        # Register
        Route::post(
            'forgotPassword',
            [UsersController::class,'forgotPassword']
        );
        # Reset Password
        Route::post(
            'resetPassword',
            [UsersController::class,'changePassword']
        );
        # List Supplier
        Route::get(
            'getSupplier',
            [LandingController::class,'getSupplier']
        );
        # Get Supplier By ID
        Route::get(
            'getSupplier/{id}',
            [LandingController::class,'getSupplierDetail']
        );
        # List Supplier Product
        Route::get(
            'getSupplier/product/{id}',
            [LandingController::class,'getSupplierProduct']
        );
        # List Category
        Route::get(
            'getCategory',
            [LandingController::class,'getCategory']
        );
        # List Design
        Route::get(
            'getDesign',
            [LandingController::class,'getDesign']
        );
        # List SubDesign
        Route::get(
            'getDesign/subDesign/{id}',
            [LandingController::class,'getSubDesign']
        );
        # List Product
        Route::get(
            'getProduct',
            [LandingController::class, 'getProduct']
        );
        # Detail Product
        Route::get(
            'getProduct/{id}',
            [LandingController::class,'getDetailProduct']
        );
        # List Testimoni
        Route::get(
            'getTestimoni',
            [LandingController::class,'getTestimoni']
        );
        # Get Payment Data
        Route::get(
            'payment/retrieve/{id}',
            [PaymentController::class,'getPaymentAndRedirect']
        );
        # List Variant
        Route::get(
            'list/variants',
            [VariantController::class,'variant']
        );
    });
});
