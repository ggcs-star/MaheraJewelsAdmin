<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCategoryOrder extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'position'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}