<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DesignController;
use App\Http\Controllers\Admin\TestimoniController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\Suppliers\VariantController as SupplierVariant;
use App\Http\Controllers\Admin\SupplierController as AdminSupplier;
use App\Http\Controllers\Suppliers\ProductController;
use App\Http\Controllers\Suppliers\WithdrawController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Suppliers\DashboardController as SupplierDashboard;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/verifikasi-sukses', function () {
    return view('verification-success');
})->name('verify-success');

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['namespace' => 'Admin','middleware' => 'auth'], function()
{
    # Dashboard page route
    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');

    # Dropshipper
    Route::get('/admin/dropshipper', [DashboardController::class, 'dropshipper'])->name('admin.dropshipper');
    Route::get('/admin/dropshipper/{id}', [DashboardController::class, 'dropshipperDetail'])->name('admin.dropshipper.details');
    Route::get('/admin/dropshipper/transactions/{id}', [DashboardController::class, 'dropshipperTransaction'])->name('admin.dropshipper.transactions');
    Route::get('/admin/dropshipper/password/{id}', [DashboardController::class, 'dropshipperPassword'])->name('admin.dropshipper.password');
    Route::post('/admin/dropshipper/password/update/{id}', [DashboardController::class, 'passwordUpdate'])->name('admin.dropshipper.password.update');

    # Toko
    Route::get('/admin/toko', [DashboardController::class, 'toko'])->name('admin.toko');
    # Supplier
    Route::get('/admin/supplier', [DashboardController::class, 'supplier'])->name('admin.supplier');
    Route::get('/admin/supplier/create', [AdminSupplier::class, 'create'])->name('admin.supplier.create');
    Route::get('/admin/supplier/edit/{id}', [AdminSupplier::class, 'edit'])->name('admin.supplier.edit');
    Route::post('/admin/supplier/store', [AdminSupplier::class, 'store'])->name('admin.supplier.store');
    Route::post('/admin/supplier/update/{id}', [AdminSupplier::class, 'update'])->name('admin.supplier.update');
    Route::get('/admin/supplier/delete/{id}', [AdminSupplier::class, 'delete'])->name('admin.supplier.delete');
    # Withdraws
    Route::get('/admin/withdraw', [DashboardController::class, 'withdraw'])->name('admin.withdraw');
    Route::get('/admin/withdraw/processed/{id}', [AdminSupplier::class, 'processed'])->name('admin.withdraw.processed');
    Route::get('/admin/withdraw/upload/{id}', [AdminSupplier::class, 'upload'])->name('admin.withdraw.upload');
    Route::post('/admin/withdraw/upload/buktiTransfer/{id}', [AdminSupplier::class, 'storeTransferReceipt'])->name('admin.withdraw.uploadBukti');
    # Category
    Route::get('/admin/category', [DashboardController::class, 'category'])->name('admin.category');
    Route::get('/admin/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
    Route::get('/admin/category/edit/{id}', [CategoryController::class, 'edit'])->name('admin.category.edit');
    Route::post('/admin/category/store', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::post('/admin/category/update/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::get('/admin/category/delete/{id}', [CategoryController::class, 'delete'])->name('admin.category.delete');
    # Design
    Route::get('/admin/design', [DashboardController::class, 'design'])->name('admin.design');
    Route::get('/admin/design/create', [DesignController::class, 'createDesign'])->name('admin.design.create');
    Route::get('/admin/design/edit/{id}', [DesignController::class, 'editDesign'])->name('admin.design.edit');
    Route::post('/admin/design/store', [DesignController::class, 'storeDesign'])->name('admin.design.store');
    Route::post('/admin/design/update/{id}', [DesignController::class, 'updateDesign'])->name('admin.design.update');
    Route::get('/admin/design/delete/{id}', [DesignController::class, 'delete'])->name('admin.design.delete');
    # Sub Design
    Route::get('/admin/subDesign/{id}', [DesignController::class, 'subDesign'])->name('admin.subDesign');
    Route::get('/admin/subDesign/create/{id}', [DesignController::class, 'createSubDesign'])->name('admin.subDesign.create');
    Route::get('/admin/subDesign/edit/{id}', [DesignController::class, 'editSubDesign'])->name('admin.subDesign.edit');
    Route::post('/admin/subDesign/store/{id}', [DesignController::class, 'storeSubDesign'])->name('admin.subDesign.store');
    Route::post('/admin/subDesign/update/{id}', [DesignController::class, 'updateSubDesign'])->name('admin.subDesign.update');
    Route::get('/admin/subDesign/delete/{id}', [DesignController::class, 'deleteSubDesign'])->name('admin.subDesign.delete');
    # Testimony
    Route::get('/admin/testimony', [DashboardController::class, 'testimony'])->name('admin.testimony');
    Route::get('/admin/testimony/create', [TestimoniController::class, 'create'])->name('admin.testimony.create');
    Route::get('/admin/testimony/edit/{id}', [TestimoniController::class, 'edit'])->name('admin.testimony.edit');
    Route::post('/admin/testimony/store', [TestimoniController::class, 'store'])->name('admin.testimony.store');
    Route::post('/admin/testimony/update/{id}', [TestimoniController::class, 'update'])->name('admin.testimony.update');
    Route::get('/admin/testimony/delete/{id}', [TestimoniController::class, 'delete'])->name('admin.testimony.delete');
    # Variant
    Route::get('/admin/variant', [DashboardController::class, 'variant'])->name('admin.variant');
    Route::get('/admin/variant/create', [VariantController::class, 'variantCreate'])->name('admin.variant.create');
    Route::get('/admin/variant/edit/{id}', [VariantController::class, 'edit'])->name('admin.variant.edit');
    Route::post('/admin/variant/store', [VariantController::class, 'variantStore'])->name('admin.variant.store');
    Route::post('/admin/variant/update/{id}', [VariantController::class, 'variantUpdate'])->name('admin.variant.update');
    Route::get('/admin/variant/delete/{id}', [VariantController::class, 'variantDelete'])->name('admin.variant.delete');
    # Logs
    Route::get('/admin/logs', [DashboardController::class, 'logs'])->name('admin.logs');
    # Mutation
    Route::get('/admin/mutation', [DashboardController::class, 'mutation'])->name('admin.mutation');
    # Settings
    Route::get('/admin/settings', [DashboardController::class, 'settings'])->name('admin.settings');
});

Route::group(['namespace' => 'Suppliers','middleware' => 'auth'], function()
{
    # Dashboard page route
    Route::get('/supplier/dashboard', [SupplierDashboard::class,'dashboard'])->name('supplier.dashboard');

    # Product
    Route::get('/supplier/product',[SupplierDashboard::class,'product'])->name('supplier.product');
    Route::get('/supplier/product/create',[ProductController::class,'create'])->name('supplier.product.create');
    Route::post('/supplier/product/store',[ProductController::class,'store'])->name('supplier.product.store');
    Route::get('/supplier/product/edit/{id}',[ProductController::class,'edit'])->name('supplier.product.edit');
    Route::post('/supplier/product/update/{id}',[ProductController::class,'update'])->name('supplier.product.update');
    Route::get('/supplier/product/active/{id}',[ProductController::class,'active'])->name('supplier.product.active');
    Route::get('/supplier/product/deactivate/{id}',[ProductController::class,'deactivate'])->name('supplier.product.deactivate');

    # Variant
    Route::get('/supplier/variant/{id}',[SupplierVariant::class,'index'])->name('supplier.variant');
    Route::post('/supplier/variant/store/{id}',[SupplierVariant::class,'store'])->name('supplier.variant.store');
    Route::get('/supplier/variant/delete/{id}',[SupplierVariant::class,'delete'])->name('supplier.variant.delete');

    # Order
    Route::get('/supplier/orders',[SupplierDashboard::class,'orders'])->name('supplier.orders');

    # Transaction History
    Route::get('/supplier/history',[SupplierDashboard::class,'transactionHistory'])->name('supplier.history');

    # Withdraw
    Route::get('/supplier/withdraw',[SupplierDashboard::class,'withdraw'])->name('supplier.withdraw');
    Route::get('/supplier/withdraw/create',[WithdrawController::class,'create'])->name('supplier.withdraw.create');
    Route::post('/supplier/withdraw/store',[WithdrawController::class,'store'])->name('supplier.withdraw.store');
    # No Rek
    Route::post('/supplier/account/store',[WithdrawController::class,'storeNoRek'])->name('supplier.account.store');
    Route::get('/supplier/account/delete/{id}',[WithdrawController::class,'deleteNoRek'])->name('supplier.account.delete');
    # Profile
    Route::get('/supplier/profile',[SupplierDashboard::class,'profile'])->name('supplier.profile');
    Route::post('/supplier/profile/update',[SupplierDashboard::class,'updateProfile'])->name('supplier.profile.update');

});

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/home', function () {
    if(Auth::check())
    {
        if(Auth::user()->isAdmin()) {
            return redirect('admin/dashboard');
        } else if(Auth::user()->isSupplier()) {
            return redirect('supplier/dashboard');
        } else {
            return redirect('https://pvotdigital.com');
        }
    } else {
        return redirect('/login');
    }
});
