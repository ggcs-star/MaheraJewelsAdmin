<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

   protected $fillable = [
    'name',
    'email',
    'password',
    'email_verified_at',
];
protected $casts = [
    'email_verified_at' => 'datetime',
];

}
