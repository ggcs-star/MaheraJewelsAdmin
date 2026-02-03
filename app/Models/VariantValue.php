<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantValue extends Model
{
    protected $fillable = ['variant_id','value','extra'];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
