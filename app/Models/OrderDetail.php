<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class OrderDetail extends Model
{
    use HasFactory;
    public $timestamps=false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected  $table= 'order_details';
    protected $fillable = [
        'quantity','price','order_id','product_id','image_url',
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
    public function orders(){
        return $this->belongsTo(Order::class,'order_id');
    }
}
