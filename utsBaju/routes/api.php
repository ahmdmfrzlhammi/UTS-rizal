<?php

use App\Http\Controllers\Api\AddToCartController;
use App\Http\Controllers\Api\Admin\categoryController;

use App\Http\Controllers\Api\admin\ProductController;
use App\Http\Controllers\Api\authController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CustomerMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware'=>'auth:sanctum'] , function (){
    Route::post('/logout',[authController::class,'logout']);
    
});

//auth
Route::Post('/Register', [AuthController::Class, 'Register']);
Route::post('/login', [AuthController::Class,'login']);


Route::group(['middleware'=>['auth:sanctum',AdminMiddleware::class],'prefix'=>'admin'], function (){
    //categories
    Route::get('/categories', [categoryController::class ,'index']);
    Route::post('/categories',[categoryController::class,'store']);
    Route::get('/categories/{id}',[categoryController::class,'show']);
    Route::post('/categories/{id}',[categoryController::class,'update']);
    Route::delete('/categories/{id}',[categoryController::class,'destroy']);
    //products
    Route::get('/products', [ProductController::class ,'index']);
    Route::post('/products',[ProductController::class,'store']);
    Route::get('/products/{id}',[ProductController::class,'show']);
    Route::post('/products/{id}',[ProductController::class,'update']);
    Route::delete('/products/{id}',[ProductController::class,'destroy']);
    
});

route::group(['middleware' => ['auth:sanctum' , CustomerMiddleware::class]], function () {
    Route::get('/add-to-cart', [AddToCartController::class ,'index']);
    Route::post('/add-to-cart', [AddToCartController::class ,'store']);
    Route::patch('/add-to-cart/{id}', [AddToCartController::class ,'update']);
    Route::delete('/add-to-cart/{id}', [AddToCartController::class ,'destroy']);

    Route::post('/checkout', [CheckoutController::class ,'store']);
});