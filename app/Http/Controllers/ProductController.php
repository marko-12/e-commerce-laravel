<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(Product::all());
    }
    public function getProductsPaginated()
    {
        $products = Product::get()->toQuery()->paginate(2);
        return response()->json($products,Response::HTTP_OK);
    }
    public function show($id)
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
    public function store(ProductRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($newProduct = $user->product()->create($request->except('image')))
        {
            if (array_key_exists('image', $request->validated())) {
                foreach ($request->image as $img)
                {
                    $imageFileName = md5($img->getClientOriginalName()) . '.' .
                        $img->getClientOriginalExtension();
                    $file = $newProduct->addMedia($img)
                        ->setFileName($imageFileName)
                        ->toMediaCollection('product-images', 'public');
//                    $imageFileName = md5($request->file('image')->getClientOriginalName()) . '.' .
//                        $request->file('image')->getClientOriginalExtension();
//                    $file = $newProduct->addMediaFromRequest('image')
//                        ->setFileName($imageFileName)
//                        ->toMediaCollection('product-images', 'public');
                }
                return response()->json(["message" => "Product created successfully"], Response::HTTP_OK);
            }
        }
        else
        {
            return response()->json(["message" => "Error while creating product"], Response::HTTP_NOT_FOUND);
        }
    }
    public function update($id, Request $request)
    {
        if ($request->validate([
            'name' => 'required|string',
            'brand' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|int',
            'count_in_stock' => 'required|int',
            'description' => 'nullable|string|max:255'
            //'rating' => 'required|int',
            //'num_of_reviews' => 'required|int'
        ]))
        {
            $product = Product::find($id);
            if (!$product)
                return response()->json(['message' => "The product doesn't exist"], Response::HTTP_NOT_FOUND);

            if ($updatedproduct = $product->update([
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
//                if (array_key_exists('image', $request->validated()))
//                {
//                    foreach ($request->image as $img)
//                    {
//                        $imageFileName = md5($img->getClientOriginalName()) . '.' .
//                            $img->getClientOriginalExtension();
//                        $updatedproduct->getMedia('product-images')->destroy();
//                        $file = $updatedproduct->addMedia($img)
//                            ->setFileName($imageFileName)
//                            ->toMediaCollection('product-images', 'public');
//                    }
//                }
                return response()->json(["message" => "Product successfully updated"], Response::HTTP_OK);
            }
        }
        else
        {
            return response()->json(['message' => "Error while updating product"], Response::HTTP_NOT_FOUND);
        }
    }
    public function destroy($id)
    {
        if (Product::find($id)->delete())
        {
            return response()->json(["message" => "Product successfully deleted"], Response::HTTP_OK);
        }
        else
        {
            return response()->json(["message" => "The product does not exist!"], Response::HTTP_NOT_FOUND);
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
