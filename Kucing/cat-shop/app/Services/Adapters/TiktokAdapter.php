<?php

namespace App\Services\Adapters;

use Illuminate\Support\Facades\Http;

class TiktokAdapter
{
    protected $apiBase = 'https://open-api.tiktok.com';
    protected $clientKey;
    protected $clientSecret;

    public function __construct($clientKey = null, $clientSecret = null)
    {
        $this->clientKey = $clientKey ?: config('services.tiktok.client_key');
        $this->clientSecret = $clientSecret ?: config('services.tiktok.client_secret');
    }

    // Authenticate / obtain token
    public function authenticate()
    {
        // TODO: implement OAuth/token flow
        throw new \Exception('Not implemented');
    }

    // Fetch products list (skeleton)
    public function fetchProducts()
    {
        // TODO: call TikTok Shop product API and map to local product fields
        return [];
    }

    // Fetch orders list (skeleton)
    public function fetchOrders()
    {
        // TODO: call TikTok Shop order API and map to local order model
        return [];
    }

    // Download an image from marketplace and save locally (helper)
    public function downloadImageToStorage(string $url, string $destFilename)
    {
        $resp = Http::get($url);
        if ($resp->successful()) {
            \Illuminate\Support\Facades\Storage::put('public/products/'.$destFilename, $resp->body());
            return $destFilename;
        }
        return null;
    }
}
