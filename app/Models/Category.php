<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Category extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected  $table= 'categories';
    protected $fillable = [
        'name','image_url','is_active','parent_category_id',
    ];
    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = (string)Uuid::generate();
        });
    }
    public static function generateUuid()
    {
        return Uuid::generate();
    }
    public function subcategory(){
        return $this->hasMany(Category::class,'parent_category_id');
    }
    public function products(){
        return $this->hasMany(Product::class);
    }
}
