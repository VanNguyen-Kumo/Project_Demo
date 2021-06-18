<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Webpatser\Uuid\Uuid;

class Category extends Model
{
 
    use HasFactory;
    public $timestamps=false;
    public $incrementing = false;
   protected $keyType = 'string';

    protected  $table= 'categories';
    protected $fillable = [
        'name','image_url','is_active','parent_category_id'
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
        return $this->hasMany(__CLASS__,'parent_category_id')->with('subcategory');
    }
    public function products(){
        return $this->hasMany(Product::class);
    }
    public function categories(){
        return $this->belongsTo(Category::class);
    }
}
