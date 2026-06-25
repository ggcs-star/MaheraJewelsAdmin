<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use App\Helpers\S3Helper;
class User extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable;

   protected $fillable = [
    'name',
    'email',
    'password',
    'email_verified_at',
    'profile_image',
    'mobile',
    'address',

    'last_login_ip',
    'country',
    'state',
    'city',
    'device',
    'browser',
    'platform',
    'last_login_at',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getProfileImageAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if (str_starts_with($value, 'http')) {
            return $value;
        }

        return S3Helper::url($value);
    }
        
}
