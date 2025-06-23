<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
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

});

