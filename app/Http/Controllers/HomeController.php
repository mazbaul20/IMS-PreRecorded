<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return Inertia::render('HomePage');
    }//End Method
    public function HomePage(){
        return Inertia::render('Home');
    }
}
