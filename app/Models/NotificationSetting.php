<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = [
        'name',
        'email',
        'receive_order',
        'receive_cancel',
        'receive_registration',
        'receive_contact',
        'is_active'
    ];
}