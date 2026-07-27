<?php

namespace App\Services\Amazon;

use App\Models\AmazonSyncLog;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AmazonAuthService
{
    /**
     * Amazon Credentials
     */
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;
    protected string $accessKey;
    protected string $secretKey;
    protected string $region;
    protected string $endpoint;

    /**
     * Access Token
     */
    protected ?string $accessToken = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->clientId = config('services.amazon.client_id');

        $this->clientSecret = config('services.amazon.client_secret');

        $this->refreshToken = config('services.amazon.refresh_token');

        $this->accessKey = config('services.amazon.aws_access_key');

        $this->secretKey = config('services.amazon.aws_secret_key');

        $this->region = config('services.amazon.region', 'eu-west-1');

        $this->endpoint = config(
            'services.amazon.endpoint',
            'https://sellingpartnerapi-eu.amazon.com'
        );
    }

    /**
     * Get Access Token
     */
    public function getAccessToken(): string
    {
        if (!empty($this->accessToken)) {
            return $this->accessToken;
        }

        try {

            $response = Http::asForm()->post(
                'https://api.amazon.com/auth/o2/token',
                [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $this->refreshToken,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]
            );

            if (!$response->successful()) {

                throw new Exception(
                    'Unable to generate Amazon Access Token.'
                );
            }

            $this->accessToken = $response['access_token'];

            return $this->accessToken;

        } catch (Exception $e) {

            Log::error($e->getMessage());

            AmazonSyncLog::create([

                'module' => 'auth',

                'api_name' => 'OAuth Token',

                'http_method' => 'POST',

                'status' => 'failed',

                'message' => $e->getMessage(),

                'http_status' => 500,

                'synced_at' => now(),

            ]);

            throw $e;
        }
    }
    /**
     * Default Headers
     */
    protected function getHeaders(): array
    {
        return [

            'x-amz-access-token' => $this->getAccessToken(),

            'Content-Type' => 'application/json',

            'Accept' => 'application/json',
        ];
    }

    /**
     * Base Endpoint
     */
    protected function endpoint(string $uri): string
    {
        return rtrim($this->endpoint, '/') . '/' . ltrim($uri, '/');
    }

    /**
     * GET Request
     */
    public function get(string $uri, array $query = [])
    {
        try {

            $response = Http::withHeaders(
                $this->getHeaders()
            )->get(
                $this->endpoint($uri),
                $query
            );

            $this->storeLog(
                'GET',
                $uri,
                $query,
                $response
            );

            return $this->validateResponse($response);

        } catch (Exception $e) {

            Log::error($e->getMessage());

            throw $e;
        }
    }

    /**
     * POST Request
     */
    public function post(string $uri, array $payload = [])
    {
        try {

            $response = Http::withHeaders(
                $this->getHeaders()
            )->post(
                $this->endpoint($uri),
                $payload
            );

            $this->storeLog(
                'POST',
                $uri,
                $payload,
                $response
            );

            return $this->validateResponse($response);

        } catch (Exception $e) {

            Log::error($e->getMessage());

            throw $e;
        }
    }

    /**
     * PUT Request
     */
    public function put(string $uri, array $payload = [])
    {
        try {

            $response = Http::withHeaders(
                $this->getHeaders()
            )->put(
                $this->endpoint($uri),
                $payload
            );

            $this->storeLog(
                'PUT',
                $uri,
                $payload,
                $response
            );

            return $this->validateResponse($response);

        } catch (Exception $e) {

            Log::error($e->getMessage());

            throw $e;
        }
    }

    /**
     * DELETE Request
     */
    public function delete(string $uri)
    {
        try {

            $response = Http::withHeaders(
                $this->getHeaders()
            )->delete(
                $this->endpoint($uri)
            );

            $this->storeLog(
                'DELETE',
                $uri,
                [],
                $response
            );

            return $this->validateResponse($response);

        } catch (Exception $e) {

            Log::error($e->getMessage());

            throw $e;
        }
    }    /**
     * Validate Response
     */
    protected function validateResponse($response): array
    {
        if (!$response->successful()) {

            throw new Exception(
                $response->body() ?: 'Amazon API Request Failed.'
            );
        }

        return $response->json();
    }

    /**
     * Store API Log
     */
    protected function storeLog(
        string $method,
        string $uri,
        array $request,
        $response
    ): void {

        try {

            AmazonSyncLog::create([

                'module' => 'auth',

                'api_name' => $uri,

                'http_method' => $method,

                'status' => $response->successful()
                    ? 'success'
                    : 'failed',

                'message' => $response->successful()
                    ? 'API Request Successful'
                    : 'API Request Failed',

                'request_payload' => json_encode($request),

                'response_payload' => $response->body(),

                'http_status' => $response->status(),

                'synced_at' => now(),

            ]);

        } catch (Exception $e) {

            Log::error($e->getMessage());
        }
    }

    /**
     * Success Response
     */
    protected function successResponse(
        array $data = []
    ): array {

        return [

            'success' => true,

            'data' => $data,

        ];
    }

    /**
     * Failed Response
     */
    protected function failedResponse(
        string $message
    ): array {

        return [

            'success' => false,

            'message' => $message,

        ];
    }
}