<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Inertia\Inertia;
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

            // return response()->json([
            //     'status' => true,
            //     'message' => "User registration successful",
            //     'data' => $user,
            // ]);

            $data = ['message' => 'User registration successful', 'status' => true, 'error' => ''];
            return redirect('/login')->with($data);
        }catch(Exception $e){
            // return response()->json([
            //     'status' => false,
            //     'message' => "User registration failed",
            // ]);

            $data = ['message' => 'User registration failed', 'status' => false, 'error' => ''];
            return redirect('/registration')->with($data);
        }
    }//End method

    public function UserLogin(Request $request){
        $count = User::where('email',$request->input('email'))->where('password',$request->input('password'))->select('id')->first();

        if($count !== null){
            //user Login -> JWT Token issue
            // $token = JWTToken::CreateToken($request->input('email'), $count->id);
            $email = $request->input('email');
            $user_id = $count->id;

            // return response()->json([
            //     'status' => 'success',
            //     'message' => "User login successful",
            //     'token' => $token,
            // ],200)->cookie('token', $token, time()+60); // Set token cookie for 7 days

            $request->session()->put('email', $email);
            $request->session()->put('user_id', $user_id);

            $data = ['message' => 'User login successful', 'status' => true, 'error' => ''];

            return redirect('/DashboardPage')->with($data);
        }else{
            // return response()->json([
            //     'status' => 'failed',
            //     'message' => "User login failed",
            // ],200);
            $data = ['message' => 'User login failed', 'status' => false, 'error' => ''];

            return redirect('/login')->with($data);
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
        // return response()->json([
        //     'status' => 'success',
        //     'message' => "User login successful",
        //     'user' => $user,
        // ],200);

        return Inertia::render('DashboardPage',['user' => $user]);
    }//End method

    public function SendOTPCode(Request $request){
        $email = $request->input('email');
        $otp = rand(1000, 9999); // Generate a random 4-digit OTP
        $count = User::where('email',$email)->count();

        if($count == 1){
            Mail::to($email)->send(new OTPMail($otp));
            User::where('email', $email)->update(['otp' => $otp]);

            $request->session()->put('email', $email);

            // return response()->json([
            //     'status' => 'success',
            //     'message' => "4 Digit {$otp} Code has been sent to your email!",
            // ],200);

            $data = ['message' => '4 Digit '.$otp.' Code has been sent to your email!', 'status' => true, 'error' => ''];
            return redirect('/verify-otp')->with($data);
        }else{
            // return response()->json([
            //     'status' => 'failed',
            //     'message' => "unauthorized",
            // ]);

            $data = ['message' => 'unauthorized', 'status' => false, 'error' => ''];
            return redirect('/registration')->with($data);
        }

    }//End method

    public function VerifyOTP(Request $request){
        // $email = $request->input('email');
        $email = $request->session()->get('email','default');
        $otp = $request->input('otp');

        $count = User::where('email', $email)->where('otp', $otp)->count();

        if($count==1){
            User::where('email', $email)->update(['otp'=> '0']);
            // $token = JWTToken::CreateTokenForSetPassword($email);

            $request->session()->put('otp_verify', 'yes');

            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'OTP verification successful',
            // ],200)->cookie('token', $token, 60*24*30);

            $data = ['message' => 'OTP verification successful', 'status' => true, 'error' => ''];
            return redirect('/reset-password')->with($data);
        }else{
            // return response()->json([
            //     'status' => 'failed',
            //     'message' => 'OTP verification failed',
            // ],200);

            $data = ['message' => 'OTP verification failed', 'status' => false, 'error' => ''];
            return redirect('/login')->with($data);
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

    public function LoginPage(){
        return Inertia::render('LoginPage');
    }//End method

    public function RegistrationPage(){
        return Inertia::render('RegistrationPage');
    }//End method

    public function SendOTPPage(){
        return Inertia::render('SendOTPPage');
    }//End method

    public function VerifyOTPPage(){
        return Inertia::render('VerifyOTPPage');
    }//End method
}
