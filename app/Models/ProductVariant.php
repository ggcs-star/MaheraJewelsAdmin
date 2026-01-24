<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'variant_type',
        'variant_value',
        'sku_suffix',
        'image_url',
        'sort_order',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
