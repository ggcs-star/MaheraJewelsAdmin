<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\S3Helper;
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
            ? S3Helper::url($this->app_logo)
            : null;
    }

    public function getSplashLogoUrlAttribute(): ?string
    {
        return $this->splash_logo
            ? S3Helper::url($this->splash_logo)
            : null;
    }

    public function getHeaderLogoUrlAttribute(): ?string
    {
        return $this->header_logo
            ? S3Helper::url($this->header_logo)
            : null;
    }
}