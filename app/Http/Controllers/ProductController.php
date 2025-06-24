<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function ProductCreate(Request $request){
        $user_id = $request->header('id');

        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'unit' => 'required',
            'category_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,gif,svg,webp|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'unit' => $request->unit,
            'category_id' => $request->category_id,
            'user_id' => $user_id
        ];

        if($request->hasFile('image')){
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'uploads/'.$filename;
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filePath;
        }

        Product::create($data);

        return response()->json([
            'status' => "success",
            'message' => "Product created successfully",
        ]);
    }//End Method

    public function ProductList(Request $request){
        $user_id = $request->header('id');

        $products = Product::where('user_id',$user_id)->get();
        return $products;
    }//End Method
}
