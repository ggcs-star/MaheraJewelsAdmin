<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'api_key',
        'api_secret',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'marketplace_id',
        'region',
        'status',
        'is_enabled',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'is_enabled' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'platform_products')
            ->withPivot([
                'platform_sku',
                'platform_price',
                'platform_stock',
                'status'
            ])
            ->withTimestamps();
    }
    public function listings()
    {
        return $this->hasMany(PlatformProduct::class);
    }
    public static function getOwnWebsite()
    {
        return static::where(
            'name',
            config('constants.own_website_name')
        )
            ->where('is_enabled', true)
            ->where('status', 'active')
            ->first();
    }


}
