<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $product=Product::with('categories','images')->where('name', 'LIKE', '%' . request('keyword') . '%')->orderBy('name')->get();
       $category=Category::whereNull('parent_category_id')->with('products.images')->orderBy('name')->get();
        return response()->json(['data'=>$product,'data_category'=>$category]);
    }

    public function store(ProductRequest $request)
    {
        $req=$request->validated();
        $product=Product::query()->create($req);
        toast('Category create success', 'success', 'top-right');
        return response()->json([
            'data'=>$product,
        ]);
    }

    public function show($id)
    {
        $products = Product::with('images')->where('id', $id)->first();
        return response()->json([
            'data'=>$products
        ]);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        Product::query()->where('id',$id)->update($request->validated());
            toast('Update product success', 'success', 'top-right');
            return response()->json([
                'message'=>'Update product success'
            ]);
    }

    public function destroy($id)
    {
        toast('Delete product success', 'success', 'top-right');
        Product::query()->where('id', $id)->delete();
        return response()->json('Delete Product Success');
    }
}
