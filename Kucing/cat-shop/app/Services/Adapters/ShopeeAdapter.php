<?php

namespace App\Services\Adapters;

use App\Models\Channel;

class ShopeeAdapter
{
    protected $credentials;

    public function __construct(Channel $channel)
    {
        $this->credentials = $channel->credentials ?? [];
    }

    /**
     * Fetch orders from Shopee API.
     * This is a stub: implement real API calls with Shopee SDK or HTTP client.
     * Return an array of orders with keys: external_id, items[], total_amount, marketplace_fee, discount_amount
     */
    public function fetchOrders(): array
    {
        // Example stub data
        return [];
    }
}
