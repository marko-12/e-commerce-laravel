<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'city',
        'address',
        'postal_code',
        'delivered',
        'delivered_at',
        'paid',
        'paid_at',
        'pay_before_shipping',
        'user_id'
    ];

    protected $table = 'orders';

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany
     */
    public function product() : BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items')->withPivot('quantity')->withTimestamps();
    }
}
