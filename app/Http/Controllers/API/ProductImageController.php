<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImageRequest;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{

    public function index()
    {
        $product_image=ProductImage::with('products')->get();
        return response()->json(['data'=>$product_image]);
    }
    public function store(ProductImageRequest $request)
    {
        $file=$request->file('image_url');
        $name=$file->getClientOriginalName();
        $filebath=$name;
        Storage::disk('s3')->put('images/'.$filebath,file_get_contents($file));
        $product_image = ProductImage::create([
            'image_url' =>'https://project-demo-images.s3.amazonaws.com/images/'.$name,
            'product_id' => $request->product_id,
        ]);
        return response()->json([
            'data'=>$product_image,
            'message'=>'Create images success'
        ]);
    }
    public function show($id)
    {
        $product_image=ProductImage::query()->where('id', $id)->first();
        return response()->json([
            'data'=>$product_image
        ]);
    }

    public function update(ProductImageRequest $request, $id)
    {
        $file=$request->file('image_url');
        $name=$file->getClientOriginalName();
        $filebath=$name;
        Storage::disk('s3')->put('images/'.$filebath,file_get_contents($file));
        $params = $request->validated();
        $params['image_url'] = 'https://project-demo-images.s3.amazonaws.com/images/'.$name;
        ProductImage::where('id', $id)->update($params);
        return response()->json([
            'data'=>$params,
            'message'=>'Update images success'
        ]);
    }

    public function destroy($id)
    {
        ProductImage::query()->where('id', $id)->delete();
        return response()->json('Delete Images Success');
    }
}
