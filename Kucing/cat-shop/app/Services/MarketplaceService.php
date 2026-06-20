<?php

namespace App\Services;

use App\Models\Channel;
use App\Services\Adapters\ShopeeAdapter;
use App\Services\Adapters\TokopediaAdapter;

class MarketplaceService
{
    public function fetchOrders(Channel $channel): array
    {
        $name = strtolower($channel->name);
        if ($name === 'shopee') {
            $adapter = new ShopeeAdapter($channel);
            return $adapter->fetchOrders();
        }

        if ($name === 'tokopedia') {
            $adapter = new TokopediaAdapter($channel);
            return $adapter->fetchOrders();
        }

        // unknown channel: return empty
        return [];
    }
}
