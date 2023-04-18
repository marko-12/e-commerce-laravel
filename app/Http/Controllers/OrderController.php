<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Faker\Core\DateTime;
use http\Env\Response;
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
            'order_items' => 'required|array',
            'user_id' => 'required|int',
        ]);

        $newOrder = Order::factory()->create([
            'country' => $request->country,
            'city' => $request->city,
            'address' => $request->address,
            'delivered' => false,
            'delivered_at' => null,
            'user_id' => $request->user_id
        ]);

        $success = true;
        foreach ($request->order_items as $orderItem)
            {
                if (!$newOrderItem = OrderItem::factory()->create([
                    'quantity' => $orderItem["quantity"],
                    'product_id' => $orderItem["product_id"],
                    'order_id' => $newOrder->id
                ]))
                {
                    $success = false;
                }
            }

//        $newOrderItem = OrderItem::factory()->create([
//            'quantity' => $request->quantity,
//            'product_id' => $request->product_id,
//            'order_id' => $newOrder->id
//        ]);
        if ($newOrder && $success)
        {
            return response('Successfully created order', 200);
        }
        else
        {
            return response('Error while creating order', 404);
        }
    }
}
