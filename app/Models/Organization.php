<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\S3Helper;
class Organization extends Model
{
    protected $table = 'organizations';

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'website',
        'logo_path',
        'business_hours',
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
            ? S3Helper::url($this->logo_path)
            : null;
    }
}
