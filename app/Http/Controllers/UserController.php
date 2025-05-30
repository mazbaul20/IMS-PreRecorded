<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\JWTToken;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function UserRegistration(Request $request){
        // dd($request->all());
        try{
            $request->validate([
                'name' => 'required',
                'mobile' => 'nullable',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);

            $user = User::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            return response()->json([
                'status' => true,
                'message' => "User registration successful",
                'data' => $user,
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => "User registration failed",
            ]);
        }
    }//End method

    public function UserLogin(Request $request){
        $count = User::where('email',$request->input('email'))->where('password',$request->input('password'))->select('id')->first();

        if($count !== null){
            //user Login -> JWT Token issue
            $token = JWTToken::CreateToken($request->input('email'), $count->id);

            return response()->json([
                'status' => 'success',
                'message' => "User login successful",
                'token' => $token,
            ],200)->cookie('token', $token, time()+60); // Set token cookie for 7 days
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => "User login failed",
            ],200);
        }
    }//End method

    public function UserLogout(){
        return response()->json([
            'status' => 'success',
            'message' => "User logout successful",
        ],200)->cookie('token', '', -1);
    }//End method

    public function DashboardPage(Request $request){
        $user = $request->header('email');
        return response()->json([
            'status' => 'success',
            'message' => "User login successful",
            'user' => $user,
        ],200);
    }//End method
}
