<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'city',
        'address',
        'delivered',
        'deliver_at',
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
    public function order_items() : HasMany
    {
        return $this->belongsTo(OrderItem::class);
    }
}
