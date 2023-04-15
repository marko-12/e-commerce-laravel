<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts()
    {
        return Product::all();
    }
    public function getProductById($id)
    {
        return Product::find($id);
    }
    public function createProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'brand' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|int',
            'count_in_stock' => 'required|int',
            'user_id' => 'required|int'
        ]);
        $name = $request->name;
        $image = $request->image;
        $brand = $request->brand;
        $category = $request->category;
        $description = $request->description;
        $price = $request->price;
        $count_in_stock = $request->count_in_stock;
        $user_id = $request->user_id;

        if ($newProduct = Product::factory()->create([
            'name' => $name,
            'image' => $image,
            'brand' => $brand,
            'category' => $category,
            'description' => $description,
            'price' => $price,
            'count_in_stock' => $count_in_stock,
            'rating' => 0,
            'num_of_reviews' => 0,
            'user_id' => $user_id
            ]))
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

            $name = $request->name;
            $image = $request->image;
            $brand = $request->brand;
            $category = $request->category;
            $description = $request->description;
            $price = $request->price;
            $count_in_stock = $request->count_in_stock;
            $rating = $request->rating;
            $num_of_reviews = $request->num_of_reviews;

//            if ($name = $request->name)
//            {
//                $product->update(['name' => $name]);
//            }
//            if ($image = $request->image)
//            {
//                $product->update(['image' => $image]);
//            }
//            if ($brand = $request->brand)
//            {
//                $product->update(['brand' => $brand]);
//            }
//            if ($category = $request->category)
//            {
//                $product->update(['category' => $category]);
//            }
//            if ($description = $request->description)
//            {
//                $product->update(['description' => $description]);
//            }
//            if ($price = $request->price)
//            {
//                $product->update(['price' => $price]);
//            }
//            if ($count_in_stock = $request->count_in_stock)
//            {
//                $product->update(['count_in_stock' => $count_in_stock]);
//            }
//            if ($rating = $request->rating)
//            {
//                $product->update(['rating' => $rating]);
//            }
//            if ($num_of_reviews = $request->num_of_reviews)
//            {
//                $product->update(['num_of_reviews' => $num_of_reviews]);
//            }
//
//            return response('Product successfully updated', 200);

            if ($product->update([
                'name' => $name,
                'image' => $image,
                'brand' => $brand,
                'category' => $category,
                'description' => $description,
                'price' => $price,
                'count_in_stock' => $count_in_stock,
                'rating' => $rating,
                'num_of_reviews' => $num_of_reviews,
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
}
