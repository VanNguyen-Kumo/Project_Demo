<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminLoginControler;
use App\Http\Controllers\API\AdminRegisterController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProductImageController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('admin/login', [API\AdminLoginControler::class,'login']);
Route::post('home/user/login', [API\UserController::class,'login']);

Route::group([
    'prefix'=>'admin',
    'middleware' => 'auth',
    'namespace' => 'API'
], function () {
    Route::post('logout', [AdminLoginControler::class,'logout']);
    Route::get('index',[AdminController::class,'index']);
    Route::get('show/{id}',[AdminController::class,'show']);
    Route::post('store', [AdminController::class,'store']);
    Route::put('update/{id}',[AdminController::class,'update']);
    Route::delete('destroy/{id}', [AdminController::class,'destroy']);

});

Route::group([
    'prefix'=>'admin/user',
    'namespace' => 'API'
], function () {
    Route::get('index',[UserController::class,'index']);
    Route::delete('destroy/{id}', [UserController::class,'destroy']);

});

Route::group([
    'prefix'=>'admin/category',
    'namespace' => 'API',
    'middleware' => 'auth',
], function () {

    Route::delete('destroy/{id}', [CategoryController::class,'destroy']);
    Route::get('show/{id}',[CategoryController::class,'show']);
    Route::post('store', [CategoryController::class,'store']);
    Route::put('update/{id}',[CategoryController::class,'update']);
});

Route::group([
    'prefix'=>'admin/product',
    'namespace' => 'API',
    'middleware' => 'auth',
], function () {
    Route::post('import_csv', [ProductController::class,'importCSV']);
    Route::delete('destroy/{id}', [ProductController::class,'destroy']);
    Route::get('show/{id}',[ProductController::class,'show']);
    Route::post('store', [ProductController::class,'store']);
    Route::put('update/{id}',[ProductController::class,'update']);
});

Route::group([
    'prefix'=>'admin/product/images',
    'namespace' => 'API',
    'middleware' => 'auth',
], function () {
    Route::get('index',[ProductImageController::class,'index']);
    Route::delete('destroy/{id}', [ProductImageController::class,'destroy']);
    Route::get('show/{id}',[ProductImageController::class,'show']);
    Route::post('store', [ProductImageController::class,'store']);
    Route::put('update/{id}',[ProductImageController::class,'update']);
});

Route::group([
    'prefix'=>'home',
    'namespace' => 'API',
], function () {
    Route::get('product/index',[ProductController::class,'index']);
    Route::get('category/index',[CategoryController::class,'index']);
});

Route::group([
    'prefix'=>'home/user',
    'namespace' => 'API',
    'middleware' => 'auth:user',
], function () {
    Route::get('show/{id}',[UserController::class,'show']);
    Route::post('store', [UserController::class,'store']);
    Route::put('update/{id}',[UserController::class,'update']);
    Route::post('logout', [UserController::class,'logout']);
});
