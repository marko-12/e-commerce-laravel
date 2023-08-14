<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        $users = collect();
        $orderItems = collect();
        foreach ($orders as $order)
        {
            $user = $order?->user()->get();
            $users->push($user->first());
            $orderItem = $order?->product()->get();
            $orderItems->push($orderItem);
        }
        return response()->json(["orders" => $orders, "users" => $users, "order_items" => $orderItems], Response::HTTP_OK);
    }

    public function show($id)
    {
        $order = Order::find($id);
        $user = $order?->user()->get()->first();
        $orderItems = $order?->product()->get();
        return response()->json(["order" => $order, "user" => $user , "order_items" => $orderItems], Response::HTTP_OK);
    }
    public function store(OrderRequest $request)
    {
        $validated = $request->validated();

        $user = User::find($request->user_id);
        $newOrder = $user->order()->create($validated);
        $newOrderItems = $newOrder->product()->sync($request->order_items);

        if ($newOrder && $newOrderItems)
        {
            return response()->json(["message" => "Successfully created order", "order" => $newOrder], Response::HTTP_OK);
        }
        else
        {
            return response()->json(["message" => "Error while creating order"], Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy($id)
    {
        if (Order::find($id)->delete())
        {
            return response()->json(["message" => "Order successfully deleted"], Response::HTTP_OK);
        }
        else
        {
            return response()->json(["message" => "This order does not exist!"], Response::HTTP_NOT_FOUND);
        }
    }

    public  function myOrders($id)
    {
        $user = User::find($id);
        $orders = $user->order()->get();
        $orderItems = collect();
        foreach ($orders as $order)
        {
            $orderItem = $order?->product()->get();
            $orderItems->push($orderItem);
        }

        return response()->json(["orders" => $orders, "order_items" => $orderItems]);
    }
}
