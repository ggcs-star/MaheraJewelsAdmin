<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantValue extends Model
{
    protected $fillable = [
        'variant_id',
        'value',        // Red / XL / 100kg
        'color',        // optional
        'height',       // optional
        'width',        // optional
        'is_active'
    ];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}

