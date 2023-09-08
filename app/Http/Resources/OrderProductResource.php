<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->resource = $resource;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "brand" => $this->brand,
            "category" => $this->category,
            "description" => $this->description,
            "price" => $this->price,
            "count_in_stock" => $this->count_in_stock,
            "rating" => $this->rating,
            "num_of_reviews" => $this->num_of_reviews,
            "user_id" => $this->user_id,
            "images" => ImageResource::collection($this->getImages()),
            'pivot' => [
                'order_id' => $this->pivot->order_id,
                'product_id' => $this->pivot->product_id,
                'quantity' => $this->pivot->quantity,
                'created_at' => $this->pivot->created_at,
                'updated_at' => $this->pivot->updated_at,
            ],
        ];
    }
}
