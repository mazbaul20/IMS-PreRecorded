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

    public function CustomerList(Request $request){
        $user_id = $request->header('id');
        return Customer::where('user_id',$user_id)->get();
    }//End Method

    public function CustomerById(Request $request){
        $user_id = $request->header('id');
        return Customer::where('id',$request->input('id'))->where('user_id',$user_id)->first();
    }//End Method

    public function CustomerUpdate(Request $request){
        $user_id = $request->header('id');
         $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
        ]);

        Customer::where('id',$request->input('id'))->where('user_id',$user_id)->update([
            'name'=> $request->input('name'),
            'email'=> $request->input('email'),
            'mobile'=> $request->input('mobile'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer updated successfully',
        ]);

    }//End Method

    public function CustomerDelete(Request $request,$id){
        $user_id = $request->header('id');
        Customer::where('id',$id)->where('user_id',$user_id)->delete();
        return response()->json([
            'status' => "success",
            'message' => "Customer deleted successfully",
        ]);
    }//End Method
}
