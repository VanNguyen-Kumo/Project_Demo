<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class Product extends Model
{
    use HasFactory;
    public $timestamps=false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected  $table= 'products';
    protected $fillable = [
        'name','price','promotion_price','description','quantity','category_id','is_active',
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
    public function images(){
        return $this->hasMany(ProductImage::class);
    }
    public function orders(){
        return $this->belongsToMany(Order::class,'order_details','product_id','order_id');
    }
    public function categories(){
        return $this->belongsTo(Category::class,'category_id');
    }

}
