<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function getProducts()
    {
        return response()->json(Product::all());
    }
    public function getProductsPaginated()
    {
        $products = Product::get()->toQuery()->paginate(2);
        return response($products,200);
    }
    public function getProductById($id)
    {
        $product = Product::find($id);
        $reviews = $product->review()->get();
        $users = collect();

        foreach ($reviews as $review)
        {
            $user = $review->user()->get();
            $users->push($user->first());
        }

        return response()->json([$product, $reviews , $users]);
    }
    public function createProduct(ProductRequest $request)
    {

        $validated = $request->validated();

        $user = User::find($request->user_id);
        if ($user->product()->create($validated))
        {
            return response()->json(["message" => " Product created successfully"], Response::HTTP_OK);
        }
        else
        {
            return response()->json(["message" => "Error while creating product"], Response::HTTP_NOT_FOUND);
        }
    }
    public function deleteProduct($id)
    {
        if ($product = Product::find($id))
        {
            if ($product->delete())
            {
                return response()->json(["message" => "Product successfully deleted"], Response::HTTP_OK);
            }
            else
            {
                return response()->json(["message" => "Error while deleting product"], Response::HTTP_NOT_FOUND);
            }
        }
        else
        {
            return response()->json(["message" => "The product does not exist!"], 404);
        }
    }
    public function updateProduct(Request $request, $id)
    {
        if ($product = Product::find($id))
        {
            $request->validate([
                'name' => 'required|string',
                'image' => 'required|string',
                'brand' => 'required|string',
                'category' => 'required|string',
                'description' => 'required|string',
                'price' => 'required|int',
                'count_in_stock' => 'required|int',
                //'rating' => 'required|int',
                //'num_of_reviews' => 'required|int'
            ]);

            if ($product->update([
                'name' => $request->name,
                'image' => $request->image,
                'brand' => $request->brand,
                'category' => $request->category,
                'description' => $request->description,
                'price' => $request->price,
                'count_in_stock' => $request->count_in_stock,
                //'rating' => $request->rating,
                //'num_of_reviews' => $request->num_of_reviews,
            ]))
            {
                return response()->json(["message" => "Product successfully updated"], Response::HTTP_OK);
            }
        }
        else
        {
            return response(['message' => "The product does not exist"], Response::HTTP_NOT_FOUND);
        }
    }

    public function getCategories()
    {
        $categories = Product::distinct('category')->pluck('category');
        return response()->json($categories);
    }

    public function searchProducts(Request $request)
    {
        $products = Product::filter($request->all());

        //$products = Product::filter($request->all())->get();

        //$products = Product::filter($request->all());

        if (key_exists('priceFrom', $request->all())) {
            $products->where('price', '>=', $request['priceFrom']);
        }

        if (key_exists('priceTo', $request->all())) {
            $products->where('price', '<=', $request['priceTo']);
        }
        $products = $products->paginate(2)
            ->appends(request()->query());


        //$products = $products->get();

        //$countProducts = Product::filter($request->all())->count();
        $countProducts = $products->count();

        return response()->json([
            'products' => $products,
            'countProducts' => $countProducts,
//            'page' => $page,
//            'pages' => ceil($countProducts / $pageSize),
        ]);
    }
}
