<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function productCreateTest(): string
    {
        if($product = Product::factory()->create())
        {
            return ('Product successfully created');
        }
        else
        {
            return ('Error: Product has not been created');
        }
    }
    public function userCreateTest(): string
    {
        if($user = User::factory()->create())
        {
            return ('User successfully created');
        }
        else
        {
            return ('Error: User has not been created');
        }
    }

    public function reviewCreateTest(): string
    {
        if($review = Review::factory()->create())
        {
            return ('Review successfully created');
        }
        else
        {
            return ('Error: Review has not been created');
        }
    }

    public  function  orderItemCreateTest() : string
    {
        if ($order = Order::factory()->create())
        {
            if($orderItem = OrderItem::factory()->create(['order_id' => 10,
                'product_id' => 3
                ]))
            {
                return ('Order and Order Item created successfully');
            }
        }
        else
        {
            return ('Error: Order or Order Item has not been created');
        }
    }
}
