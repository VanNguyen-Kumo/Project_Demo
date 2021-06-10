<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Imports\ProductsImport;
use App\Imports\UsersImport;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Excel;

class ProductController extends Controller
{
    public function index()
    {
        if (\request()->file('sort') === null) {
            $product = Product::with('categories', 'images')->where('name', 'LIKE', '%' . request('keyword') . '%')->orderBy('name')->get();
        } elseif (\request()->file('sort') === 'asc') {
            $product = Product::with('categories', 'images')->where('name', 'LIKE', '%' . request('keyword') . '%')->orderBy('price')->get();
        } elseif (\request()->file('sort') === 'desc') {
            $product = Product::with('categories', 'images')->where('name', 'LIKE', '%' . request('keyword') . '%')->orderBy('price', 'desc')->get();
        } elseif (\request()->file('sort') === 'created_at') {
            $product = Product::with('categories', 'images')->where('name', 'LIKE', '%' . request('keyword') . '%')->orderBy('created_at')->get();
        }
        foreach ($product as $products) {
            if ($products->categories->parent_category_id !== null) {
                $cate = Category::query()->select('name')->where('id', $products->categories->parent_category_id)->first();
                $products->categories->categories = $cate->name;
            }
        }
        return response()->json(['data' => $product]);
    }

    public function data_category()
    {
        $category = Category::whereNull('parent_category_id')->with(['subcategory.products.images', 'products.images'])->orderBy('name')->get();
        return response()->json(['data_category' => $category]);
    }

    public function show_data_category($id)
    {
        $cates = Category::with(['subcategory.products.images', 'products.images'])->where('id', $id)->first();
        return response()->json([
            'data' => $cates
        ]);
    }

    public function sub_category()
    {
        $category = Category::whereNotNull('parent_category_id')->with('products.images')->orderBy('name')->get();
        return response()->json(['sub_category' => $category]);
    }

    public function store(ProductRequest $request)
    {
        $req = $request->validated();
        $product = Product::query()->create($req);
        return response()->json([
            'data' => $product,
            'message' => 'Create product success'
        ]);
    }

    public function show($id)
    {
        $products = Product::with('categories', 'images')->where('id', $id)->get();

        foreach ($products as $product) {
            if ($product->categories->parent_category_id !== null) {
                $cate = Category::query()->select('name')->where('id', $product->categories->parent_category_id)->first();
                $product->categories->categories = $cate->name;
            }
        }
        return response()->json([
            'data' => $products
        ]);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        Product::query()->where('id', $id)->update($request->validated());
        return response()->json([
            'message' => 'Update product success'
        ]);
    }

    public function destroy($id)
    {
        Product::query()->where('id', $id)->delete();
        return response()->json('Delete Product Success');
    }

    public function importCSV(Request $request)
    {
        $path = $request->file('csv')->getRealPath();
        Excel::import(new ProductsImport(), $path);
        return response()->json(['message' => 'Import Product Success']);
    }
}
