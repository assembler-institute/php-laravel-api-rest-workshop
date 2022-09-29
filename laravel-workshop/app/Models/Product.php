<?php

namespace App\Models;

use App\Services\ProductPriceService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $appends = [
        'formatted_price',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => (new ProductPriceService())->format($this->price_in_cents),
        );
    }
}
