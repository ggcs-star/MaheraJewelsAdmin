<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InstagramGraphService
{
    protected string $graphVersion;
    protected string $appId;
    protected string $appSecret;
    protected string $redirectUri;

    public function __construct()
    {
        $this->graphVersion = env('FACEBOOK_GRAPH_VERSION', 'v23.0');
        $this->appId = env('FACEBOOK_APP_ID');
        $this->appSecret = env('FACEBOOK_APP_SECRET');
        $this->redirectUri = env('FACEBOOK_REDIRECT_URI');
    }

    /**
     * Facebook Login URL
     */
    public function getLoginUrl(): string
    {
        return "https://www.facebook.com/{$this->graphVersion}/dialog/oauth?" . http_build_query([
            'client_id'     => $this->appId,
            'redirect_uri'  => $this->redirectUri,
            'scope'         => implode(',', [
                'instagram_basic',
                'pages_show_list',
                'business_management',
            ]),
            'response_type' => 'code',
        ]);
    }

    /**
     * Get Short Lived Token
     */
 public function getShortLivedToken(string $code): array
{
    $response = Http::timeout(60)
        ->retry(3, 1000)
        ->get(
            "https://graph.facebook.com/{$this->graphVersion}/oauth/access_token",
            [
                'client_id'     => $this->appId,
                'client_secret' => $this->appSecret,
                'redirect_uri'  => $this->redirectUri,
                'code'          => $code,
            ]
        );

    return [
        'status' => $response->status(),
        'body'   => $response->json(),
    ];
}

    /**
     * Convert to Long Lived Token
     */
public function getLongLivedToken(string $shortToken): array
{
    $response = Http::get(
        "https://graph.facebook.com/{$this->graphVersion}/oauth/access_token",
        [
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => $this->appId,
            'client_secret'     => $this->appSecret,
            'fb_exchange_token' => $shortToken,
        ]
    );

    return [
        'status' => $response->status(),
        'body'   => $response->json(),
    ];
}

    /**
     * Get Facebook Pages
     */
    public function getPages(string $token): array
    {
        $response = Http::get("https://graph.facebook.com/{$this->graphVersion}/me/accounts", [
            'access_token' => $token,
        ]);

        return $response->json();
    }

    /**
     * Get Instagram Business Account
     */
    public function getInstagramBusiness(string $pageId, string $token): array
    {
        $response = Http::get("https://graph.facebook.com/{$this->graphVersion}/{$pageId}", [
            'fields'       => 'instagram_business_account',
            'access_token' => $token,
        ]);

        return $response->json();
    }

    /**
     * Get Instagram Profile
     */
    public function getProfile(string $instagramId, string $token): array
    {
        $response = Http::get("https://graph.facebook.com/{$this->graphVersion}/{$instagramId}", [
            'fields'       => 'id,username,followers_count,follows_count,media_count,profile_picture_url',
            'access_token' => $token,
        ]);

        return $response->json();
    }

    /**
     * Get All Media
     */
    public function getMedia(string $instagramId, string $token): array
    {
        $response = Http::get("https://graph.facebook.com/{$this->graphVersion}/{$instagramId}/media", [
            'fields' => implode(',', [
                'id',
                'caption',
                'media_type',
                'media_product_type',
                'media_url',
                'thumbnail_url',
                'permalink',
                'timestamp',
            ]),
            'access_token' => $token,
        ]);

        return $response->json();
    }

    /**
     * Only Reels
     */
public function getReels(string $instagramId, string $token, int $limit = 20): array
{
    $response = Http::get(
        "https://graph.facebook.com/{$this->graphVersion}/{$instagramId}/media",
        [
            'fields' => implode(',', [

                'id',
                'caption',
                'media_type',
                'media_product_type',
                'media_url',
                'thumbnail_url',
                'permalink',
                'timestamp'

            ]),

            'limit' => $limit,

            'access_token' => $token
        ]
    );

    $json = $response->json();

    if (!isset($json['data'])) {
        return [
            'data' => [],
            'paging' => null
        ];
    }

    $reels = collect($json['data'])

        ->filter(function ($item) {

            return isset($item['media_product_type']) &&
                $item['media_product_type'] === 'REELS';

        })

        ->values()
        ->toArray();

    return [

        'data' => $reels,

        'paging' => $json['paging'] ?? null
    ];
}

    /**
     * Refresh Long Lived Token
     */
    public function refreshAccessToken(string $token): array
    {
        $response = Http::get("https://graph.facebook.com/{$this->graphVersion}/refresh_access_token", [
            'grant_type'  => 'ig_refresh_token',
            'access_token'=> $token,
        ]);

        return $response->json();
    }
}