<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
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

    public function ProductById(Request $request){
        $user_id = $request->header('id');
        return Product::where('id',$request->input('id'))->where('user_id',$user_id)->first();
    }//End Method

    public function ProductUpdate(Request $request){
        $user_id = $request->header('id');

        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'unit' => 'required',
            'category_id' => 'required',
        ]);

        $product = Product::where('user_id',$user_id)->findOrFail($request->input('id'));
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->unit = $request->input('unit');
        $product->category_id = $request->input('category_id');

        if($request->hasFile('image')){
            if($product->image && file_exists(public_path($product->image))){
                unlink(public_path($product->image));
            }
            $request->validate([
                'image' => 'nullable|image|mimes:jpeg,png,gif,svg,webp|max:2048',
            ]);

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'uploads/'.$filename;
            $file->move(public_path('uploads'), $filename);
            $product->image = $filePath;
        }

        $product->save();

        return response()->json([
            'status' => "success",
            'message' => "Product updated successfully",
        ]);
    }//End Method

    public function ProductDelete(Request $request,$id){
        try{
            $user_id = $request->header('id');

            $product = Product::where('user_id',$user_id)->findOrFail($id);

            if($product->image && file_exists(public_path($product->image))){
                unlink(public_path($product->image));
            }

            $product->delete();
            
            return response()->json([
                'status' => "success",
                'message' => "Product deleted successfully",
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => "failed",
                'message' => 'Something went wrong, please try again later',
            ]);
        }
    }//End Method
}
