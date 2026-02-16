<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantValue extends Model
{
    protected $fillable = [
        'variant_id',
        'value',        
        'color',        
        'height',       
        'width',        
        'is_active'
    ];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}

