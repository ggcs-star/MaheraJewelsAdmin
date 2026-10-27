<?php

namespace App\Services;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;

class AppSettingService
{
    public function getActiveSetting(): ?AppSetting
    {
        return Cache::remember(
            'app_settings',
            3600,
            fn () => AppSetting::query()
                ->where('is_active', true)
                ->latest()
                ->first()
        );
    }
}
