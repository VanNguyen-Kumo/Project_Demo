<?php

namespace App\Imports;

use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function check(UpdateProductRequest $request){

    }
    public function model(array $row)
    {
        $name=Category::query()->where('name',$row['category'])->first();
        if(!$name){
            $cate=new Category();
            $cate->name=$row['category'];
            $cate->save();
            return new Product([
                'name'=>$row['name'],
                'price'=>$row['price'],
                'quantity'=>$row['quantity'],
                'description'=>$row['description'],
                'category_id'=>$cate['id'],
            ]);
        }else{
            return new Product([
                'name'=>$row['name'],
                'price'=>$row['price'],
                'quantity'=>$row['quantity'],
                'description'=>$row['description'],
                'category_id'=>$name['id'],
            ]);
        }
    }
}
