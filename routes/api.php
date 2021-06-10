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
use App\Http\Controllers\API\OrderUserController;
use App\Http\Controllers\API\OrderAdminController;


Route::post('admin/login', [API\AdminLoginControler::class,'login']);
Route::post('home/user/login', [API\UserController::class,'login']);
Route::post('home/user/register', [API\UserController::class,'store']);

Route::group([
    'prefix'=>'admin',
    'middleware' => 'auth',
    'namespace' => 'API'
], function () {
    Route::post('logout', [AdminLoginControler::class,'logout']);
    Route::get('index',[AdminController::class,'index']);
    Route::get('show/{id}',[AdminController::class,'show']);
    Route::put('update/{id}',[AdminController::class,'update']);
    Route::delete('destroy/{id}', [AdminController::class,'destroy']);
    Route::post('register', [AdminController::class,'store']);

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
    Route::delete('delete/{id}', [ProductImageController::class,'destroy']);
    Route::get('show/{id}',[ProductImageController::class,'show']);
    Route::post('store', [ProductImageController::class,'store']);
    Route::put('update/{id}',[ProductImageController::class,'update']);
});

Route::group([
    'prefix'=>'home',
    'namespace' => 'API',
], function () {
    Route::get('product/index',[ProductController::class,'index']);
    Route::get('product/index/{id}',[ProductController::class,'show']);
    Route::get('category/index',[CategoryController::class,'index']);
    Route::get('data_category',[ProductController::class,'data_category']);
    Route::get('data_category/{id}',[ProductController::class,'show_data_category']);
    Route::get('sub_category',[ProductController::class,'sub_category']);
    Route::get('sub_category/{id}',[CategoryController::class,'show']);

});

Route::group([
    'prefix'=>'home/user',
    'namespace' => 'API',
    'middleware' => 'auth:user',
], function () {
    Route::get('show',[UserController::class,'show']);
    Route::post('store', [UserController::class,'store']);
    Route::put('update',[UserController::class,'update']);
    Route::put('change_address',[UserController::class,'change_address']);
    Route::put('change_password',[UserController::class,'change_password']);
    Route::put('update_image',[UserController::class,'update_image']);
    Route::post('logout', [UserController::class,'logout']);
});

Route::group([
    'prefix'=>'home/user/cart',
    'namespace' => 'API',
    'middleware' => 'auth:user',
], function () {
    Route::get('index', [OrderUserController::class,'index']);
    Route::put('cancel/{order_id}',[OrderUserController::class,'cancel']);
    Route::get('checkout',[OrderUserController::class,'checkout']);
    Route::post('order',[OrderUserController::class,'store']);
    Route::get('show',[OrderUserController::class,'show']);
});

Route::group([
    'prefix'=>'admin/order',
    'namespace' => 'API',
    'middleware' => 'auth',
], function () {
    Route::get('index',[OrderAdminController::class,'index']);
    Route::get('show/{id}',[OrderAdminController::class,'show']);
    Route::patch('update/{order_id}',[OrderAdminController::class,'update']);
    Route::get('exportCSV',[OrderAdminController::class,'exportCSV']);
    Route::post('statistical',[OrderAdminController::class,'statistical']);

});

Route::get('test',[OrderAdminController::class,'statistical']);
Route::get('show',[OrderUserController::class,'show']);
Route::get('exportCSV',[OrderAdminController::class,'exportCSV']);
