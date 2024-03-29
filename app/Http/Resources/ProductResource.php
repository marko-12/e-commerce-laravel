<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            "category_id" => $this->category_id,
            "description" => $this->description,
            "price" => $this->price,
            "count_in_stock" => $this->count_in_stock,
            "rating" => $this->rating,
            "num_of_reviews" => $this->num_of_reviews,
            "user_id" => $this->user_id,
            "images" => ImageResource::collection($this->getImages()),
        ];
    }
}
