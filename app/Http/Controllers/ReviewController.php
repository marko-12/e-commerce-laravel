<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function postReview(ReviewRequest $request, $id)
    {
        $validated = $request->validated();

        if (!$user = User::find($request->user_id))
        {
            return response('User not found', 401);
        }
        $product = Product::find($id);
        if ($product->review()->where('user_id','=', $request->user_id)->exists())
        {
            return response('You have already submitted a review for this product',404);
        }

        $review = $product->review()->create($validated, ['product_id' => $id]);
        $num_of_rev = $product->review()->count();
        $reviews_sum = $product->review()->sum('rating');
        $newRating = $reviews_sum/$num_of_rev;
        $product->update([
            'num_of_reviews' => $num_of_rev,
            'rating' => $newRating
        ]);

        if ($review)
        {
            return response()->json([$review, $num_of_rev, $newRating]);
        }
    }

    public function getReviews($id)
    {
        if (!$reviews = Product::find($id)->review()->get())
        {
            return response('Product or reviews do not exist', 404);
        }
        return response()->json($reviews);
    }
}
