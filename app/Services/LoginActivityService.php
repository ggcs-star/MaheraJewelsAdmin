<?php

namespace App\Services;

use App\Models\LoginLog;
use Stevebauman\Location\Facades\Location;

class LoginActivityService
{
    public function store($user, $request)
    {
        $position = Location::get($request->ip());

        LoginLog::create([
            'user_id'    => $user->id,
            'ip_address' => $request->ip(),
            'country'    => is_object($position) ? $position->countryName : null,
            'city'       => is_object($position) ? $position->cityName : null,
            'browser'    => $this->browser($request->userAgent()),
            'platform'   => $this->platform($request->userAgent()),
            'user_agent' => $request->userAgent(),
        ]);
    }

    protected function browser($agent)
    {
        return match (true) {
            str_contains($agent, 'Chrome') => 'Chrome',
            str_contains($agent, 'Firefox') => 'Firefox',
            str_contains($agent, 'Safari') => 'Safari',
            default => 'Unknown',
        };
    }

    protected function platform($agent)
    {
        return match (true) {
            str_contains($agent, 'Windows') => 'Windows',
            str_contains($agent, 'Mac') => 'Mac',
            str_contains($agent, 'Linux') => 'Linux',
            str_contains($agent, 'Android') => 'Android',
            str_contains($agent, 'iPhone') => 'iOS',
            default => 'Unknown',
        };
    }
}
