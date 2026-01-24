<?php
namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function getFullProduct($slug)
    {
        return Product::with([
            'variants.stock',
            'platformListings.platform',
            'platformListings.pricing.variant'
        ])->where('slug', $slug)->first();
    }
}
