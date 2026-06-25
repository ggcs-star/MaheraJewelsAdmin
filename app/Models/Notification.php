<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [

        'user_id',

        'order_id',

        'title',

        'message',

        'type',

        'action_url',

        'is_read',

    ];
}