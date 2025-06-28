<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Middleware\TokenVerificationMiddleware;

// Route::get('/', function () {
//     // return view('welcome');
//     return "Hello World";
// });

Route::get('/test',[HomeController::class, 'index'])->name('home');
Route::get('/',[HomeController::class, 'HomePage'])->name('HomePage');


//User all routes
Route::post('/user-registration',[UserController::class, 'UserRegistration'])->name('UserRegistration');
Route::post('/user-login',[UserController::class, 'UserLogin'])->name('user.login');
Route::get('/user-logout',[UserController::class, 'UserLogout'])->name('user.logout');

Route::get('/DashboardPage',[UserController::class, 'DashboardPage'])->middleware([TokenVerificationMiddleware::class])->name('dashboard.page');

Route::post('/send-otp',[UserController::class, 'SendOTPCode'])->name('SendOTPCode');
Route::post('/verify-otp',[UserController::class, 'VerifyOTP'])->name('VerifyOTP');
Route::post('/reset-password',[UserController::class, 'ResetPassword'])->middleware([TokenVerificationMiddleware::class]);

Route::middleware(TokenVerificationMiddleware::class)->group(function () {
    // Category all routes
    Route::controller(CategoryController::class)->group(function (){
        Route::post('/category-create', 'CategoryCreate')->name('category.create');
        Route::get('/list-category', 'CategoryList')->name('category.list');
        Route::post('/category-by-id', 'CategoryById')->name('category.by.id');
        Route::post('/update-category', 'CategoryUpdate')->name('category.update');
        Route::get('/delete-category/{id}', 'CategoryDelete')->name('category.delete');
    });

    // Product all routes
    Route::controller(ProductController::class)->group(function (){
        Route::post('/create-product', 'ProductCreate')->name('product.create');
        Route::get('/list-product', 'ProductList')->name('product.list');
        Route::post('/product-by-id', 'ProductById')->name('product.by.id');
        Route::post('/update-product', 'ProductUpdate')->name('product.update');
        Route::get('/delete-product/{id}', 'ProductDelete')->name('product.delete');
    });

    // Product all routes
    Route::controller(CustomerController::class)->group(function (){
        Route::post('/create-customer', 'CustomerCreate')->name('customer.create');

    });

});

