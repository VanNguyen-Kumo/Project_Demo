<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    public function index()
    {
        $cates = Category::whereNull('parent_category_id')
            ->with(['subcategory' => function($category) {
                return $category->withCount(['subcategory','products'])
                    ->with('subcategory.products');
            }])->with('products')
            ->withCount(['subcategory', 'products'])->orderBy('name','asc')
            ->where('name', 'LIKE', '%' . request('keyword') . '%')->get();
        foreach ($cates as $cate){
            if($cate['subcategory_count']>0){
                $count=0;
                foreach ($cate->subcategory as $category){
                    $count +=$category->products_count;
                }
                $cate->products_count+=$count;
            }
        }
        return response()->json([
            'data'=>$cates
        ]);
    }

    public function store(CategoryRequest $request)
    {
        $file=$request->file('image_url');
        $name=$file->getClientOriginalName();
        $filebath=$name;
        Storage::disk('s3')->put('images/'.$filebath,file_get_contents($file));
        $parent_category_id = $request->parent_category_id;
        if ($parent_category_id === 0) {
            $cate = Category::create([
                'name' => $request['name'],
                'image_url' =>'https://project-demo-images.s3.amazonaws.com/images/'.$name,
                'parent_category_id' => 0,
            ]);
        } else {
            $cate = Category::create([
                'name' => $request['name'],
                'image_url' => 'https://project-demo-images.s3.amazonaws.com/images/'.$name,
                'parent_category_id' => $request['parent_category_id']
            ]);
        }
        toast('Category create success', 'success', 'top-right');
        return response()->json([
            'data'=>$cate
        ]);
    }

    public function show($id)
    {
        $cates = Category::with('products','products.images')->where('id', $id)->first();
        return response()->json([
            'data'=>$cates
        ]);
    }
    public function show_data_category($id)
    {
        $cates = Category::with('subcategory','subcategory.products','products.images')->where('id', $id)->first();
        return response()->json([
            'data'=>$cates
        ]);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $file=$request->file('image_url');
        $name=$file->getClientOriginalName();
        $filebath=$name;
        Storage::disk('s3')->put('images/'.$filebath,file_get_contents($file));
        $params = $request->validated();
        $params['image_url'] = 'https://project-demo-images.s3.amazonaws.com/images/'.$name;
        Category::where('id', $id)->update($params);
        toast('Update category success', 'success', 'top-right');
        return response()->json([
            'message' => 'Category update successfully',
            'data' => $params
        ]);
    }
//    public function patch($id){
//        $cate=Category::where('parent_category_id',$id)->first();
//        $cate->parent_category_id=0;
//        $cate->save();
//    }
    public function destroy($id)
    {
        Category::query()->where('id', $id)->delete();
        return response()->json(['data'=>'Delete Category Success']);
    }
}
