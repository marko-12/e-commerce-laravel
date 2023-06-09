<?php

use App\Http\Controllers\Controller;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function (){
    if($user = \App\Models\User::factory()->create())
    {
        return("User successfully created");
    }
    else
    {
        return ("Error");
    }
});

Route::get('users', [UserController::class, 'getUsers']);
Route::get('users/{id}', [UserController::class, 'getUserById']);
Route::patch('users/{id}',[UserController::class, 'changeUser']);//Admin Changes the user(privilege)
Route::patch('profile/{id}',[UserController::class, 'updateProfile']);
Route::delete('users/{id}', [UserController::class, 'deleteUser']);
Route::post('signup', [UserController::class, 'createUser']);
Route::post('signin', [UserController::class, 'signIn']);//->middleware("auth:sanctum", "ability:login");

Route::get('products', [ProductController::class, 'getProducts']);
Route::get('products_paginated',[ProductController::class, 'getProductsPaginated']);
Route::get("products/{id}", [ProductController::class, 'getProductById']);
Route::post('products', [ProductController::class, 'createProduct']);
Route::delete('products/{id}', [ProductController::class, 'deleteProduct']);
Route::patch('products/{id}', [ProductController::class, 'updateProduct']);
Route::post('products/{id}/review', [ReviewController::class, 'postReview']);
Route::get('categories', [ProductController::class, 'getCategories']);
Route::get('products/{id}/review', [ReviewController::class, 'getReviews']);//mozda suvisno
Route::get('search', [ProductController::class, 'searchProducts']);

Route::get('orders', [OrderController::class, 'getOrders']);
Route::get('orders/{id}',[OrderController::class, 'getOrderById']);
Route::post('orders', [OrderController::class, 'createOrder']);
