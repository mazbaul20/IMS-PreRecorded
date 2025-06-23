<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function CategoryCreate(Request $request){
        // dd($request->all());
        $user_id = $request->header('id');

        Category::create([
            'name'=> $request->name,
            'user_id' => $user_id,
        ]);
        return response()->json([
            'status' => "success",
            'message' => "Category created successfully",
        ]);
    }//End method

    public function CategoryList(Request $request){
        $user_id = $request->header('id');

        $categories = Category::where('user_id',$user_id)->get();
        return $categories;
    }//End method

    public function CategoryById(Request $request){
        $user_id = $request->header('id');

        $category = Category::where('id',$request->input('id'))->where('user_id',$user_id)->first();
        return $category;
    }//End method

}
