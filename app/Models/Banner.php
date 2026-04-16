<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Helpers\S3Helper;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'mobile_image',
        'button_text',
        'button_link',
        'page',
        'position',
        'layout',
        'text_color',
        'sort_order',
        'status',
        'start_date',
        'end_date',
    ];

    // Desktop image full URL
    public function getImageAttribute($value)
    {
        if (!$value) return null;

        if (str_starts_with($value, 'http')) {
            return $value;
        }

         return S3Helper::url($value);
    }

    // Mobile image full URL
    public function getMobileImageAttribute($value)
    {
        if (!$value) return null;

        if (str_starts_with($value, 'http')) {
            return $value;
        }

         return S3Helper::url($value);
    }
}