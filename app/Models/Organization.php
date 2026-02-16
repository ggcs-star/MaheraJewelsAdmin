<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'organizations';

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'website',
        'logo_path',

        'address',
        'city',
        'state',
        'country',
        'pincode',

        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path
            ? \Storage::disk('s3')->url($this->logo_path)
            : null;
    }
}
