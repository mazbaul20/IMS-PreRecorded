<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Route::get('/', function () {
//     // return view('welcome');
//     return "Hello World";
// });

Route::get('/test',[HomeController::class, 'index'])->name('home');
Route::get('/',[HomeController::class, 'HomePage'])->name('HomePage');
