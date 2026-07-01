<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramReel extends Model
{
    protected $fillable = [
        'instagram_media_id',
        'caption',
        'media_type',
        'media_product_type',
        'media_url',
        'thumbnail_url',
        'permalink',
        'instagram_created_at',
        'is_active',
    ];

    protected $casts = [
        'instagram_created_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}