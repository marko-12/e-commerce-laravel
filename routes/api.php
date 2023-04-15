<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
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

Route::get('product', [Controller::class, 'productCreateTest']);
Route::get('usertest', [Controller::class, 'userCreateTest']);
Route::get('review', [Controller::class, 'reviewCreateTest']);
Route::get('orderitem',[Controller::class, 'orderItemCreateTest']);

Route::get('users', [UserController::class, 'getUsers']);
Route::get('users/{id}', [UserController::class, 'getUserById']);
Route::patch('users/resetpassword/{id}',[UserController::class, 'resetPassword']);
Route::delete('users/deleteuser/{id}', [UserController::class, 'deleteUser']);
Route::post('signup', [UserController::class, 'createUser']);
Route::post('signin', [UserController::class, 'signIn']);

Route::get('products', [ProductController::class, 'getProducts']);
Route::get("products/{id}", [ProductController::class, 'getProductById']);
Route::post('products/', [ProductController::class, 'createProduct']);
Route::delete('products/{id}', [ProductController::class, 'deleteProduct']);
Route::patch('products/{id}', [ProductController::class, 'updateProduct']);

Route::get('orders', [OrderController::class, 'getOrders']);
