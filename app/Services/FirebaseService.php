<?php

namespace App\Services;

use Google\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use App\Models\NotificationToken;

class FirebaseService
{
    protected string $projectId;
    protected string $credentials;
    protected HttpClient $http;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id');
        $this->credentials = config('services.firebase.credentials');

        $this->http = new HttpClient([
            'timeout' => 30,
        ]);
    }

    /**
     * Get Firebase OAuth Access Token
     */
    private function getAccessToken(): string
    {
        $client = new Client();

        $client->setAuthConfig($this->credentials);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $token = $client->fetchAccessTokenWithAssertion();

        if (!isset($token['access_token'])) {
            throw new \Exception('Unable to generate Firebase Access Token.');
        }

        return $token['access_token'];
    }

    /**
     * Send Notification to Single Token
     */
  public function sendToToken(
    string $token,
    string $title,
    string $body,
    array $data = []
) {
    $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

    $accessToken = $this->getAccessToken();

    $payload = [
        'message' => [
            'token' => $token,

            'webpush' => [
                'headers' => [
                    'Urgency' => 'high',
                ],

                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'icon' => url('/favicon.ico'),
                    'badge' => url('/favicon.ico'),
                    'requireInteraction' => true,
                ],

                'fcm_options' => [
                    'link' => url('/admin/dashboard'),
                ],
            ],

            'data' => array_merge([
                'title' => $title,
                'body' => $body,
                'url' => url('/admin/dashboard'),
            ], array_map('strval', $data)),
        ],
    ];

    try {

        $response = $this->http->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
        ]);

        return [
            'success' => true,
            'status' => $response->getStatusCode(),
            'body' => json_decode($response->getBody()->getContents(), true),
        ];

    } catch (RequestException $e) {

        return [
            'success' => false,
            'status' => optional($e->getResponse())->getStatusCode(),
            'body' => optional($e->getResponse())
                ? json_decode($e->getResponse()->getBody()->getContents(), true)
                : $e->getMessage(),
        ];
    }
}
    /**
     * Send Notification to Single User
     */
  public function sendToUser(
    int $userId,
    string $title,
    string $body,
    array $data = []
) {
    $tokens = NotificationToken::where('user_id', $userId)
        ->pluck('fcm_token');

    $responses = [];

    foreach ($tokens as $token) {
        $responses[] = $this->sendToToken(
            $token,
            $title,
            $body,
            $data
        );
    }

    return $responses;
}
    /**
     * Send Notification to All Users
     */
   public function sendToAll(
    string $title,
    string $body,
    array $data = []
) {
    $tokens = NotificationToken::pluck('fcm_token');

    $responses = [];

    foreach ($tokens as $token) {
        $responses[] = $this->sendToToken(
            $token,
            $title,
            $body,
            $data
        );
    }

    return $responses;
}
}