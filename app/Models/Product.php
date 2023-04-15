<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'category',
        'description',
        'brand',
        'price',
        'count_in_stock',
        'rating',
        'num_of_reviews',
        'user_id'
    ];
    protected $table = 'products';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function review(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * @return HasMany
     */
    public function order_item() : HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
