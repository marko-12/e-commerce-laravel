<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Faker\Core\DateTime;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrders()
    {
        return response(Order::all(), 200);
    }

    public function getOrderById($id)
    {
        return response(Order::find($id), 200);
    }
    public function createOrder(Request $request)
    {
        $request->validate([
            'country' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'user_id' => 'required|int',
            'quantity' => 'required|int',
            'product_id' => 'required|int',
        ]);
        $country = $request->country;
        $city = $request->city;
        $address = $request->address;
        $user_id = $request->user_id;
        $quantity = $request->quantuty;
        $product_id = $request->product_id;

        $newOrder = Order::factory()->create([
            'country' => $country,
            'city' => $city,
            'address' => $address,
            'delivered' => false,
            'delivered_at' => Carbon::now(),
            'user_id' => $user_id
        ]);
    }
}
