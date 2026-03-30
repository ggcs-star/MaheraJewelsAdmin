<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $table = 'app_settings';

    protected $fillable = [
        'app_name',
        'app_logo',
        'splash_logo',
        'header_logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getAppLogoUrlAttribute(): ?string
    {
        return $this->app_logo
            ? \Storage::disk('s3')->url($this->app_logo)
            : null;
    }

    public function getSplashLogoUrlAttribute(): ?string
    {
        return $this->splash_logo
            ? \Storage::disk('s3')->url($this->splash_logo)
            : null;
    }

    public function getHeaderLogoUrlAttribute(): ?string
    {
        return $this->header_logo
            ? \Storage::disk('s3')->url($this->header_logo)
            : null;
    }
}