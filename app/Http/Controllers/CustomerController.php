<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function CustomerCreate(Request $request){
        try{
            $user_id = $request->header('id');
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'mobile' => 'required',
            ]);
            Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'user_id' => $user_id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Customer created successfully',
            ]);

        }catch(Exception $e){
            return response()->json([
                'status' => 'failed',
                'message' => 'Something went wrong, please try again later',
            ]);
        }
    }//End Method
}
