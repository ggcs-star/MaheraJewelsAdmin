<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use UAParser\Parser;

class ActivityLogService
{
    public static function log(
        User $user,
        string $activityType,
        ?int $referenceId = null,
        string $status = 'success',
        array $meta = []
    ): void {

        $request = request();

        $parser = Parser::create();

        $result = $parser->parse($request->userAgent());

        // Local testing
        $ip = app()->environment('local')
            ? '8.8.8.8'
            : $request->ip();

        $response = Http::timeout(5)
            ->get("https://ipwho.is/{$ip}");

        $location = $response->successful()
            ? $response->json()
            : [];

        UserActivityLog::create([
            'user_id'       => $user->id,
            'activity_type' => $activityType,
            'reference_id'  => $referenceId,

            'ip_address'    => $request->ip(),

            'country'       => $location['country'] ?? null,
            'state'         => $location['region'] ?? null,
            'city'          => $location['city'] ?? null,

            'device'        => $result->device->family,
            'browser'       => $result->ua->family,
            'platform'      => $result->os->family,

            'user_agent'    => $request->userAgent(),

            'status'        => $status,

            'meta'          => $meta,
        ]);
    }
}