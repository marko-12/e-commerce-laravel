<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
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

    protected $likeFilterFields = [
        'name'
    ];
    protected $largerThanFields = [
        'rating'
    ];
    protected $betweenFields = [
        'price'
    ];

    protected $table = 'products';

    public function scopeFilter($builder, $filters = [])
    {
        if (!$filters) {
            return $builder;
        }
        $tableName = $this->getTable();
        $defaultFillableFields = $this->fillable;
        foreach ($filters as $field => $value) {
            if (!in_array($field, $defaultFillableFields) || !$value) {
                continue;
            }

            if (in_array($field, $this->likeFilterFields)) {
                $builder->where($tableName . '.' . $field, 'LIKE', "%$value%");
            }
            else if (in_array($field, $this->largerThanFields)){
                $builder->where($tableName . '.' . $field, '>=', $value);
            }
            else if (is_array($value)) {
                $builder->whereIn($field, $value);
            } else {
                $builder->where($field, $value);
            }
        }
        return $builder;
    }

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
     * @return BelongsToMany
     */
    public function order() : BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_items')->withPivot('quantity')->withTimestamps();
    }
}
