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
        self::COSMETICS => 'Cosmetics and Body Care',
        self::ELECTRONICS => 'Electronics',
        self::OFFICE_EQUIPMENT => 'Office Equipment',
        self::PET_CARE => 'Pet Care',
        self::MEDIA => 'Media',
        self::HOUSEHOLD_ITEMS => 'Household Items',
        self::OTHER => 'Other'
    ];
    public const SHOES = 1;
    public const CLOTHES = 2;
    public const COSMETICS = 3;
    public const ELECTRONICS = 4;
    public const OFFICE_EQUIPMENT  = 5;
    public const PET_CARE  = 6;
    public const MEDIA  = 7;
    public const HOUSEHOLD_ITEMS  = 8;
    public const OTHER  = 9;
}
