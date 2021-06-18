<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory;
    public $timestamps=false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected  $table= 'admins';
    protected $fillable = [
        'username',
        'password',
    ];
    protected $hidden = [
        'password',
    ];
    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
          // $model->id = self::generateUuid();
            $model->id = (string)Uuid::generate();
        });
    }
    public static function generateUuid()
    {
        return Uuid::generate();
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
