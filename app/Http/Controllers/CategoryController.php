<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
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
        // return response()->json([
        //     'status' => "success",
        //     'message' => "Category created successfully",
        // ]);
        $data = ['message' => 'Category created successfully', 'status' => true, 'error' => ''];
        return redirect('/CategoryPage')->with($data);
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

    public function CategoryUpdate(Request $request){
        $user_id = $request->header('id');

        Category::where('id',$request->input('id'))->where('user_id',$user_id)->update([
            'name'=> $request->name,
        ]);
        // return response()->json([
        //     'status' => "success",
        //     'message' => "Category updated successfully",
        // ]);
        $data = ['message' => 'Category updated successfully', 'status' => true, 'error' => ''];
        return redirect('/CategoryPage')->with($data);
    }//End method

    public function CategoryDelete(Request $request,$id){
        $user_id = $request->header('id');

        Category::where('id',$id)->where('user_id',$user_id)->delete();
        // return response()->json([
        //     'status' => "success",
        //     'message' => "Category deleted successfully",
        // ]);
        $data = ['message' => 'Category deleted successfully', 'status' => true, 'error' => ''];
        return redirect('/CategoryPage')->with($data);
    }//End method

    public function CategoryPage(Request $request){
        $user_id = $request->header('id');

        $categories = Category::where('user_id',$user_id)->latest()->get();
        return Inertia::render('CategoryPage',['categories' => $categories]);
    }//End method

    public function CategorySavePage(Request $request){
        $category_id = $request->query('id');

        $user_id = $request->header('id');

        $category = Category::where('id',$category_id)->where('user_id',$user_id)->first();
        return Inertia::render('CategorySavePage',['category' => $category]);
    }//End method

}
