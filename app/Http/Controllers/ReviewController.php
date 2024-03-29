<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewController extends Controller
{
    public function postReview(ReviewRequest $request, $id)
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = auth()->user();

        if (!$user)
        {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $product = Product::find($id);
        if ($product->review()->where('user_id','=', $user->id)->exists())
        {
            return response()->json(['message' => 'You have already submitted a review for this product'],Response::HTTP_FORBIDDEN);
        }

        $productOrdered = false;
        $userOrders = $user->order()->get();
        foreach ($userOrders as $order)
        {
            $product = $order->product()->wherePivot('product_id', $id)->first();

            if ($product) {
                $productOrdered = true;
                break;
            }
        }
        if (!$productOrdered)
        {
            return response()->json(['message' => 'You have not ordered this product'],Response::HTTP_FORBIDDEN);
        }


        $review = $product->review()->create([
            'comment' => $request->comment,
            'rating' => $request->rating,
            'product_id' => $id,
            'user_id' => $user->id]);
        $num_of_rev = $product->review()->count();
        $reviews_sum = $product->review()->sum('rating');
        $newRating = $reviews_sum/$num_of_rev;
        $product->update([
            'num_of_reviews' => $num_of_rev,
            'rating' => $newRating
        ]);

        if ($review)
        {
            return response()->json(['message' => 'Review submitted successfully'], Response::HTTP_CREATED);
        }
    }

    public function getReviews($id)
    {
        if (!$reviews = Product::find($id)->review()->get())
        {
            return response()->json(['message' => 'Product or reviews do not exist'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($reviews);
    }
}
