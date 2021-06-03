<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class OrderDetail extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected  $table= 'order_details';
    protected $fillable = [
<<<<<<< HEAD
        'quantity','price','order_id','product_id'
=======
        'quantity','price','order_id','product_id','image_url',
>>>>>>> #6-User
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
