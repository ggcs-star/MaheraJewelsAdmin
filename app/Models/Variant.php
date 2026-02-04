<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = [
        'name',            
        'input_type',      
        'has_dimensions',  
        'is_active'
    ];

    public function values()
    {
        return $this->hasMany(VariantValue::class);
    }
}

