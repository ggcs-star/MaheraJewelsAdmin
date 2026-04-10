<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = 'stock_movements';

    protected $fillable = [

        'product_id',

        'variant_id',

        'platform_id',

        'movement',

        'quantity',

        'balance',

        'reference_type',

        'reference_id',

        'remarks',

    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(
            ProductVariant::class,
            'variant_id'
        );
    }

    public function platform()
    {
        return $this->belongsTo(
            Platform::class,
            'platform_id'
        );
    }
}