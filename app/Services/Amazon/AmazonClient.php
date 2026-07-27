<?php

namespace App\Services\Amazon;

use SellingPartnerApi\SellingPartnerApi;
use SellingPartnerApi\Enums\Endpoint;

class AmazonClient
{
    protected SellingPartnerApi $client;

    public function __construct()
    {
        $this->client = new SellingPartnerApi(
            clientId: config('services.amazon.client_id'),
            clientSecret: config('services.amazon.client_secret'),
            refreshToken: config('services.amazon.refresh_token'),
            endpoint: Endpoint::EU
        );
    }

    /**
     * Get Seller Connector
     */
    public function seller()
    {
        return $this->client->seller();
    }

    /**
     * Get Raw Client
     */
    public function client(): SellingPartnerApi
    {
        return $this->client;
    }

    /**
     * Marketplace Id
     */
    public function marketplaceId(): string
    {
        return config('services.amazon.marketplace_id');
    }

    /**
     * Seller Id
     */
    public function sellerId(): string
    {
        return config('services.amazon.seller_id');
    }
}