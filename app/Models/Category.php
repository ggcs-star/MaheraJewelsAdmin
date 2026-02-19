<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'parent_id',
        'image_url', 'meta_title', 'meta_description',
        'meta_keywords', 'sort_order', 'is_featured',
        'visibility', 'status',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

public function getImageUrlAttribute($value)
{
    if (!$value) {
        return null;
    }

    // 🔥 agar DB me full URL already hai
    if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
        return $value;
    }

    // 🔥 agar sirf path hai
    return Storage::disk('s3')->url($value);
}
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }


}
