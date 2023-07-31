<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return response(Order::all(), 200);
    }

    public function show($id)
    {
        return response(Order::find($id), 200);
    }
    public function store(OrderRequest $request)
    {
        $validated = $request->validated();

        $user = User::find($request->user_id);
        $newOrder = $user->order()->create($validated);
        $newOrderItems = $newOrder->product()->sync($request->order_items);

        if ($newOrder && $newOrderItems)
        {
            return response('Successfully created order', 200);
        }
        else
        {
            return response('Error while creating order', 404);
        }
    }
}
