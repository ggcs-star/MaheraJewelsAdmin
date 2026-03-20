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
        'status',
        'views_count',
        'likes_count',
        'shares_count',
        'comments_count'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function platformProduct()
    {
        return $this->belongsTo(PlatformProduct::class);
    }

    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            PlatformProduct::class,
            'id',
            'id',
            'platform_product_id',
            'product_id'
        );
    }

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

    public function likes()
    {
        return $this->hasMany(ReelLike::class);
    }

    public function shares()
    {
        return $this->hasMany(ReelShare::class);
    }

    public function views()
    {
        return $this->hasMany(ReelView::class);
    }

    public function comments()
    {
        return $this->hasMany(ReelComment::class);
    }
}