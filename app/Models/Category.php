<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $table = "categories";

    /**
     * @return HasMany
     */
    public function product(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public const BASIC_CATEGORIES = [
        self::SHOES => 'Shoes',
        self::CLOTHES => 'Clothes',
        self::PHONES => 'Phones',
        self::OTHER => 'Other'
    ];
    public const SHOES = 1;
    public const CLOTHES = 2;
    public const PHONES = 3;
    public const OTHER = 4;
}
