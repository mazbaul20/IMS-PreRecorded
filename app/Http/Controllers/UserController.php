<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    public function SendOTPCode(Request $request){
        $email = $request->input('email');
        $otp = rand(1000, 9999); // Generate a random 4-digit OTP
        $count = User::where('email',$email)->count();

        if($count == 1){
            Mail::to($email)->send(new OTPMail($otp));
            User::where('email', $email)->update(['otp' => $otp]);
            return response()->json([
                'status' => 'success',
                'message' => "4 Digit {$otp} Code has been sent to your email!",
            ],200);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => "unauthorized",
            ]);
        }

    }//End method

    public function VerifyOTP(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');

        $count = User::where('email', $email)->where('otp', $otp)->count();

        if($count==1){
            User::where('email', $email)->update(['otp'=> '0']);
            $token = JWTToken::CreateTokenForSetPassword($email);

            return response()->json([
                'status' => 'success',
                'message' => 'OTP verification successful',
            ],200)->cookie('token', $token, 60*24*30);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'OTP verification failed',
            ],200);
        }
    }//End method

    public function ResetPassword(Request $request){
        try{
            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email', $email)->update(['password' => $password]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset successful',
            ],200);
        }catch(Exception $e){
            return response()->json([
                'status' => 'failed',
                'message' => 'Something went wrong, please try again later',
            ], 200);
        }
    }//End method

    public function UserUpdate(Request $request){
        $user_email = $request->header('email');
        $new_email = $request->input('email');

        $user = User::where('email', $user_email)->first();

        $user->update([
            'name' => $request->input('name'),
            'email' => $new_email,
            'mobile' => $request->input('mobile'),
        ]);

        if($user_email !== $new_email){
            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully. you have been logged out due to email change',
            ])->cookie('token', '', -1);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
        ]);
    }//End method
}
