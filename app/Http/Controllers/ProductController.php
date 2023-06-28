<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts()
    {
        return response()->json(Product::all());
        //return response(Product::all(),200);
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
            return response('Successfully created new product', 200);
        }
        else
        {
            return response('Error creating product', 404);
        }
    }
    public function deleteProduct($id)
    {
        if ($product = Product::find($id))
        {
            if ($product->delete())
            {
                return response('Product successfully deleted', 200);
            }
            else
            {
                return response('Error while deleting product', 404);
            }
        }
        else
        {
            return response('The product does not exist!', 404);
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
                'rating' => 'required|int',
                'num_of_reviews' => 'required|int'
            ]);

            if ($product->update([
                'name' => $request->name,
                'image' => $request->image,
                'brand' => $request->brand,
                'category' => $request->category,
                'description' => $request->description,
                'price' => $request->price,
                'count_in_stock' => $request->count_in_stock,
                'rating' => $request->rating,
                'num_of_reviews' => $request->num_of_reviews,
            ]))
            {
                return response('Product successfully updated', 200);
            }
        }
        else
        {
            return response('The product does not exist', 404);
        }
    }

    public function getCategories()
    {
        $categories = Product::distinct('category')->pluck('category');
        return response()->json($categories);
    }

    public function searchProducts(Request $request)
    {
//        $PAGE_SIZE = 3;
//        $pageSize = $request->input('pageSize', $PAGE_SIZE);
//        $page = $request->input('page', 1);
//        $category = $request->input('category', '');
//        $price = $request->input('price', '');
//        $rating = $request->input('rating', '');
//        $order = $request->input('order', '');
//        $searchQuery = $request->input('query', '');
//
//        $searchFilter = $searchQuery && $searchQuery !== 'all' ? $searchQuery : '';
//        $categoryFilter = $category && $category !== 'all' ? ['category' => $category] : [];
//        $ratingFilter = $rating && $rating !== 'all' ? ['rating' => ['$gte' => (int)$rating]] : [];
//        $priceFilter = $price && $price !== 'all' ? ['price' => ['$gte' => (int)explode('-', $price)[0], '$lte' => (int)explode('-', $price)[1]]] : [];
//
//        $sortOrder = $order === 'featured' ? ['featured' => -1] :
//            ($order === 'lowest' ? ['price' => 1] :
//                ($order === 'highest' ? ['price' => -1] :
//                    ($order === 'toprated' ? ['rating' => -1] :
//                        ($order === 'newest'
//            ? ['createdAt' => -1]
//            : ['_id' => -1]))));
//
//        $products = Product::where('name', 'LIKE', '%'.$searchFilter.'%')
//            ->where($categoryFilter)
//            ->where($priceFilter)
//            ->where($ratingFilter)
//            ->get()
//            ->sortBy($sortOrder)
//            ->skip($pageSize * ($page - 1))
//            ->take($pageSize);
//
//        $countProducts = Product::where('name', 'LIKE', '%'.$searchFilter.'%')
//            ->where($categoryFilter)
//            ->where($priceFilter)
//            ->where($ratingFilter)
//            ->count();

//        $products = Product::filter($request->all())
//            ->paginate(2)
//            ->appends(request()->query());
        $products = Product::filter($request)->get();
        //echo 'ty';

        $countProducts = Product::filter($request->all())->count();

        return response()->json([
            'products' => $products,
            //'countProducts' => $countProducts,
//            'page' => $page,
//            'pages' => ceil($countProducts / $pageSize),
        ]);
    }
}
