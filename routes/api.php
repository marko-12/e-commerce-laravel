<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// change to /auth/login and /auth/register
// add endpoint for logout
// move auth endpoints to new Auth controller
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['auth:api']], function() {
    Route::apiResource('users', UserController::class);
//    Route::get('users', [UserController::class, 'getUsers']);
//    Route::get('users/{id}', [UserController::class, 'getUserById']);
//    Route::patch('profile/{id}',[UserController::class, 'updateProfile']);
//    Route::delete('users/{id}', [UserController::class, 'deleteUser']);
    Route::patch('change-user/{id}',[UserController::class, 'changeUser']);//Admin Changes the user(privilege)
    Route::patch('reset-password/{id}', [UserController::class, 'resetPassword']);
    Route::get('user-info', [UserController::class, 'userInfo']);
    Route::get('temp', [UserController::class, 'temp']);


    //Route::get('products', [ProductController::class, 'getProducts']);
    Route::get('products_paginated',[ProductController::class, 'getProductsPaginated']);
    //Route::get("products/{id}", [ProductController::class, 'getProductById']);
    //Route::post('products', [ProductController::class, 'createProduct']);
    //Route::delete('products/{id}', [ProductController::class, 'deleteProduct']);
    //Route::patch('products/{id}', [ProductController::class, 'updateProduct']);
    Route::post('products/{id}/review', [ReviewController::class, 'postReview']);
    Route::get('products/{id}/review', [ReviewController::class, 'getReviews']);//mozda suvisno
    Route::get('search', [ProductController::class, 'searchProducts']);

    Route::apiResource('orders', OrderController::class);
    Route::get('orders/my/{id}', [OrderController::class, 'myOrders']);

    //Route::get('orders', [OrderController::class, 'getOrders']);
    //Route::get('orders/{id}',[OrderController::class, 'getOrderById']);
    //Route::post('orders', [OrderController::class, 'createOrder']);

    Route::apiResource('products', ProductController::class);
});

Route::get('categories', [ProductController::class, 'getCategories']);
