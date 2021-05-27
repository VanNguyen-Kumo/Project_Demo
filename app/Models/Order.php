<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class Order extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected  $table= 'Orders';
    protected $fillable = [
       'total_price','total_quantity','delivery_address','delivery_date','phone','status'
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
    public function users(){
        return $this->belongsToMany(User::class,'user_id');
    }
    public static function getOrder(){
        $records=DB::table('order')->select('total_price', 'total_quantity','delivery_date','status')->get()->toArray();
        return $records;
    }
}
