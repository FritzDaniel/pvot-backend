<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DesignController;
use App\Http\Controllers\Api\Admin\LogsController;
use App\Http\Controllers\Api\Admin\TestimoniController;
use App\Http\Controllers\Api\Admin\TokoController;
use App\Http\Controllers\Api\Admin\VariantController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Landing\LandingController;
use App\Http\Controllers\Api\Payment\InvoiceController;
use App\Http\Controllers\Api\Suppliers\SupplierController;
use App\Http\Controllers\Api\Users\MembershipController;
use App\Http\Controllers\Api\Users\PaymentController;
use App\Http\Controllers\Api\Users\ShopsController;
use App\Http\Controllers\Api\Users\UsersController;
use App\Http\Controllers\Api\Admin\SupplierController as AdminSupplierController;
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
        # Admin
        Route::group(['middleware' => ['role:Superadmin']], function () {
            Route::group(['prefix' => 'admin'], function (){
                # List Variant
                Route::get(
                    'variants',
                    [VariantController::class,'variant']
                );
                # Create Variant
                Route::post(
                    'variants/store',
                    [VariantController::class,'variantStore']
                );
                # Update Variant
                Route::post(
                    'variants/update/{id}',
                    [VariantController::class,'variantUpdate']
                );
                # Delete Variant
                Route::get(
                    'variants/delete/{id}',
                    [VariantController::class,'variantDelete']
                );
                # List Withdraw
                Route::get(
                    'withdraw',
                    [AdminSupplierController::class,'listWithdraw']
                );
                # Change Status Withdraw
                Route::post(
                    'withdraw/{id}',
                    [AdminSupplierController::class,'changeStatusWithdraw']
                );
                # List Toko
                Route::get(
                    'toko',
                    [TokoController::class,'listToko']
                );
                # Detail Toko
                Route::get(
                    'detailToko/{id}',
                    [TokoController::class,'getDetailToko']
                );
                # Change Status Toko
                Route::post(
                    'changeStatusToko/{id}',
                    [TokoController::class,'changeStatus']
                );
                # Create Supplier
                Route::post(
                    'storeSupplier',
                    [AdminSupplierController::class,'storeSupplier']
                );
                # Update Supplier
                Route::post(
                    'updateSupplier',
                    [AdminSupplierController::class,'updateSupplier']
                );
                # Create Kategori
                Route::post(
                    'storeCategory',
                    [CategoryController::class, 'storeCategory']
                );
                # Update Kategori
                Route::post(
                    'updateCategory/{id}',
                    [CategoryController::class, 'updateCategory']
                );
                # Delete Kategori
                Route::get(
                    'deleteCategory/{id}',
                    [CategoryController::class, 'deleteCategory']
                );
                # Testimoni
                Route::post(
                    'storeTestimoni',
                    [TestimoniController::class, 'storeTestimoni']
                );
                # Get Log List
                Route::get(
                    'logs',
                    [LogsController::class,'logs']
                );
                # Design
                Route::get(
                    'design',
                    [DesignController::class,'listDesign']
                );
                Route::get(
                    'design/subDesign',
                    [DesignController::class,'listSubDesign']
                );
                Route::post(
                    'design/store',
                    [DesignController::class,'storeDesign']
                );
                Route::post(
                    'design/subDesign/store',
                    [DesignController::class,'storeSubDesign']
                );
            });
        });
        # Supplier
        Route::group(['middleware' => ['role:Supplier']], function () {
            Route::group([ 'prefix' => 'supplier'], function (){
                # Request Withdraw
                Route::post(
                    'withdraw/store',
                    [SupplierController::class, 'requestWithdraw']
                );
                # Get Balance
                Route::get(
                    'wallet',
                    [UsersController::class, 'getWallet']
                );
                # My Product
                Route::get(
                    'myProduct',
                    [SupplierController::class, 'myProduct']
                );
                # Detail Product
                Route::get(
                    'myProduct/{id}',
                    [SupplierController::class,'detailProduct']
                );
                # Create Product
                Route::post(
                    'storeProduct',
                    [SupplierController::class, 'storeProduct']
                );
                # Update Product
                Route::post(
                    'updateProduct/{id}',
                    [SupplierController::class, 'updateProduct']
                );
                # Delete Product
                Route::get(
                    'deleteProduct/{id}',
                    [SupplierController::class, 'deleteProduct']
                );
                # List Transaction
                Route::get(
                    'listTransaction',
                    [SupplierController::class, 'listTransaction']
                );
                # List Transaction
                Route::get(
                    'listWithdraw',
                    [SupplierController::class, 'listWithdraw']
                );
            });
        });
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
            '/webhook/callback',
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
