<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReelLike extends Model
{

    protected $fillable = [
        'reel_id',
        'user_id',
        'ip_address'
    ];

}