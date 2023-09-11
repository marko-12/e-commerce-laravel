<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderProductResource;
use App\Http\Resources\ProductResource;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
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
        return response()->json(["order" => $order, "user" => $user , "order_items" => OrderProductResource::collection($orderItems)], Response::HTTP_OK);
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

    public function update($id, Request $request)
    {
        $request->validate([
            "delivered" => 'required|bool',
            "paid" => 'required|bool',
        ]);

        $order = Order::find($id);

        if (!$order)
            return response()->json(['message' => "The order doesn't exist"], Response::HTTP_NOT_FOUND);

        if ($request->delivered){
            $delivered_at = Carbon::now();
        }
        else{
            $delivered_at = null;
        }
        if ($request->paid){
            $paid_at = Carbon::now();
        }
        else{
            $paid_at = null;
        }
        if ($order->update([
            "delivered" => $request->delivered,
            "paid" => $request->paid,
            "delivered_at" => $delivered_at,
            "paid_at" => $paid_at
        ]))
        {
            return response()->json(["message" => "Successfully updated order"]);
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
