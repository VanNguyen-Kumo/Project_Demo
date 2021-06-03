<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class ProductImage extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';
    protected  $table= 'product_images';
    protected $fillable = [
        'image_url','product_id',
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
    public function products(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
