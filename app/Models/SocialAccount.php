<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialAccount extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',

        'platform',

        'facebook_user_id',

        'page_id',

        'instagram_business_id',

        'instagram_username',

        'access_token',

        'expires_at',

        'is_active'
    ];

    protected $casts = [

        'expires_at' => 'datetime',

        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}