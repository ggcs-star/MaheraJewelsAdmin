<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'mobile',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'zip_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
        'country'   => 'India',
    ];
}
