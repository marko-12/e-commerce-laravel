<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Authenticatable
{
    use HasFactory;

    protected $hidden = [
        'name',
        'image',
        'category',
        'description',
        'brand',
        'price',
        'count_in_stock',
        'rating',
        'num_of_reviews'
    ];
    protected $table = 'product';

    /**
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
