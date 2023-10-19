<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get()->toQuery()->paginate(16);
        return ProductResource::collection($products)->response()->getData(true);
    }
    public function getProductsPaginated()
    {
        $products = Product::get()->toQuery()->paginate(10);
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

        return response()->json(["product" => new ProductResource($product), "reviews" => $reviews , "users" => $users]);
    }
    public function store(ProductRequest $request)
    {
        $request->validated();

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
                }
                return response()->json(["message" => "Product created successfully"], Response::HTTP_OK);
            }
        }
        else
        {
            return response()->json(["message" => "Error while creating product"], Response::HTTP_NOT_FOUND);
        }
    }
    public function update(Request $request, $id)
    {
        if ($request->validate([
            'name' => 'required|string',
            'brand' => 'required|string',
            'category_id' => 'required|int',
            'price' => 'required|int',
            'count_in_stock' => 'required|int',
            'description' => 'nullable|string|max:255'
        ]))
        {
            $product = Product::find($id);


            if ($updatedproduct = $product->update([
                'name' => $request->name,
                'image' => $request->image,
                'brand' => $request->brand,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'price' => $request->price,
                'count_in_stock' => $request->count_in_stock,
            ]))
                return response()->json(["message" => "Product successfully updated"], Response::HTTP_OK);
        }
        else
        {
            return response()->json(['message' => "Error while updating product"], Response::HTTP_NOT_FOUND);
        }
    }
    public function uploadImage(Request $request, $id)
    {
        if($request->validate([
            'image' => 'array|min:1',
            'image.*' => 'image'
        ]))
        {
            $product = Product::find($id);
            if (!$product)
                return response()->json(['message' => "The product doesn't exist"], Response::HTTP_NOT_FOUND);

            if (array_key_exists('image', $request->all())) {
                $product->media()->delete();
                foreach ($request->image as $img)
                {
                    $imageFileName = md5($img->getClientOriginalName()) . '.' .
                        $img->getClientOriginalExtension();

                    $file = $product->addMedia($img)
                        ->setFileName($imageFileName)
                        ->toMediaCollection('product-images', 'public');
                }
            }
            return response()->json(["message" => "Image(s) uploaded successfully"]);
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
        $categories = Category::all();
        return response()->json($categories);
    }

    public function searchProducts(Request $request)
    {
        $products = Product::filter($request->all());

        //$products = Product::filter($request->all())->get();

        if (key_exists('priceFrom', $request->all())) {
            $products->where('price', '>=', $request['priceFrom']);
        }

        if (key_exists('priceTo', $request->all())) {
            $products->where('price', '<=', $request['priceTo']);
        }
        $products = $products->paginate(9)
            ->appends(request()->query());
        $products = ProductResource::collection($products)->response()->getData(true);

        //$countProducts = Product::filter($request->all())->count();
        //$countProducts = count($products);

        return response()->json([
            'products' => $products,
//            'page' => $page,
//            'pages' => ceil($countProducts / $pageSize),
        ]);
    }
}
