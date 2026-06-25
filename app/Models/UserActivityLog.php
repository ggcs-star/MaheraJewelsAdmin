<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'activity_type',
        'reference_id',
        'ip_address',
        'country',
        'state',
        'city',
        'device',
        'browser',
        'platform',
        'user_agent',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}