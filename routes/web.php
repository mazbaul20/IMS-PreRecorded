<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

// Route::get('/', function () {
//     // return view('welcome');
//     return "Hello World";
// });

Route::get('/test',[HomeController::class, 'index'])->name('home');
Route::get('/',[HomeController::class, 'HomePage'])->name('HomePage');


//User all routes
Route::post('/user-registration',[UserController::class, 'UserRegistration'])->name('UserRegistration');
