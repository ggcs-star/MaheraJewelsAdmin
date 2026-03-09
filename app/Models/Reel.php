<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reel extends Model
{

    protected $table = 'reels';

    protected $fillable = [
        'platform_product_id',
        'title',
        'description',
        'video',
        'status'
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Reel -> Platform Product
    public function platformProduct()
    {
        return $this->belongsTo(PlatformProduct::class, 'platform_product_id');
    }


    // Reel -> Actual Product
    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            PlatformProduct::class,
            'id',              // PlatformProduct primary key
            'id',              // Product primary key
            'platform_product_id',
            'product_id'
        );
    }


    // Reel -> Platform (Amazon / Flipkart / Meesho etc)
    public function platform()
    {
        return $this->hasOneThrough(
            Platform::class,
            PlatformProduct::class,
            'id',
            'id',
            'platform_product_id',
            'platform_id'
        );
    }

}