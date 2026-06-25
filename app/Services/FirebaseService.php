<?php

namespace App\Services;

use Google\Client;
use GuzzleHttp\Client as HttpClient;
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
    )
    {
        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $accessToken = $this->getAccessToken();

        $payload = [

            'message' => [

                'token' => $token,

                'notification' => [

                    'title' => $title,

                    'body' => $body,

                ],

                'data' => $data,

            ]

        ];

        return $this->http->post($url, [

            'headers' => [

                'Authorization' => 'Bearer ' . $accessToken,

                'Content-Type' => 'application/json',

            ],

            'json' => $payload,

        ]);
    }

    /**
     * Send Notification to User
     */
    public function sendToUser(
        int $userId,
        string $title,
        string $body,
        array $data = []
    )
    {
        $tokens = NotificationToken::where('user_id', $userId)->pluck('fcm_token');

        foreach ($tokens as $token) {

            $this->sendToToken(

                $token,

                $title,

                $body,

                $data

            );
        }
    }

    /**
     * Send Notification to All Users
     */
    public function sendToAll(
        string $title,
        string $body,
        array $data = []
    )
    {
        $tokens = NotificationToken::pluck('fcm_token');

        foreach ($tokens as $token) {

            $this->sendToToken(

                $token,

                $title,

                $body,

                $data

            );
        }
    }
}