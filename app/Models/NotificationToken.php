<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationToken extends Model
{
    protected $fillable = [

        'user_id',

        'fcm_token',

        'browser',

        'platform',

        'ip_address',

        'last_seen',

    ];
}