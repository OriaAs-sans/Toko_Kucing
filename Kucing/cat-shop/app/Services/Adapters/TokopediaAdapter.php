<?php

namespace App\Services\Adapters;

use App\Models\Channel;

class TokopediaAdapter
{
    protected $credentials;

    public function __construct(Channel $channel)
    {
        $this->credentials = $channel->credentials ?? [];
    }

    /**
     * Fetch orders from Tokopedia API.
     * This is a stub: implement real API calls with Tokopedia SDK or HTTP client.
     */
    public function fetchOrders(): array
    {
        return [];
    }
}
