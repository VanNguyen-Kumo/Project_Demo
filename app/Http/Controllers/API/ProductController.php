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
        $category=Category::whereNull('parent_category_id')->with('products.images')->orderBy('name')->get();
        if(\request()->file('sort')===null){
            $product=Product::with('categories','images')->where('name', 'LIKE', '%' . request('keyword') . '%')->orderBy('name')->get();
        }elseif (\request()->file('sort')==='asc'){
            $product=Product::with('categories','images')->where('name', 'LIKE', '%' . request('keyword') . '%')->orderBy('price')->get();
        }elseif (\request()->file('sort')==='desc'){
            $product=Product::with('categories','images')->where('name', 'LIKE', '%' . request('keyword') . '%')->orderBy('price','desc')->get();
        }elseif(\request()->file('sort')==='created_at'){
            $product=Product::with('categories','images')->where('name', 'LIKE', '%' . request('keyword') . '%')->orderBy('created_at')->get();
        }

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
    public function importCSV()
    {
        $path = request()->file('csv')->getRealPath();
        Excel::import(new ProductsImport(),$path);
        toast('Import CSV success','success','top-right');
        return response()->json(['message'=>'Import Product Success']);
    }
}
