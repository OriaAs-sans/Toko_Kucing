<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Adapters\TiktokAdapter;
use App\Models\Product;
use App\Models\Order;

class TiktokController extends Controller
{
    protected $adapter;

    public function __construct()
    {
        $this->adapter = new TiktokAdapter();
    }

    public function syncProducts(Request $request)
    {
        // Basic guard: check config
        if (!config('services.tiktok.client_key') || !config('services.tiktok.client_secret')) {
            return redirect()->back()->with('success', 'TikTok credentials not configured. Set services.tiktok in .env');
        }

        try {
            $items = $this->adapter->fetchProducts();
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'Adapter error: '.$e->getMessage());
        }

        $created = 0; $updated = 0;
        foreach ($items as $it) {
            // Expect item keys: sku, name, sell_price, cost_price, stock_qty, image_url
            $sku = $it['sku'] ?? null;
            $product = null;
            if ($sku) $product = Product::where('sku', $sku)->first();
            if (!$product && !empty($it['name'])) $product = Product::where('name', $it['name'])->first();

            $data = [
                'sku' => $sku,
                'name' => $it['name'] ?? 'Unnamed',
                'sell_price' => $it['sell_price'] ?? 0,
                'cost_price' => $it['cost_price'] ?? 0,
                'stock_qty' => $it['stock_qty'] ?? 0,
            ];

            if ($product) {
                $product->update($data);
                $updated++;
            } else {
                $product = Product::create($data);
                $created++;
            }

            // download image if provided
            if (!empty($it['image_url'])) {
                try {
                    $filename = time().'_'.basename(parse_url($it['image_url'], PHP_URL_PATH));
                    $this->adapter->downloadImageToStorage($it['image_url'], $filename);
                    $product->image = $filename;
                    $product->save();
                } catch (\Exception $e) {
                    // ignore per-item image failures
                }
            }
        }

        return redirect()->back()->with('success', "Products synced: created={$created}, updated={$updated}");
    }

    public function syncOrders(Request $request)
    {
        if (!config('services.tiktok.client_key') || !config('services.tiktok.client_secret')) {
            return redirect()->back()->with('success', 'TikTok credentials not configured. Set services.tiktok in .env');
        }

        try {
            $orders = $this->adapter->fetchOrders();
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'Adapter error: '.$e->getMessage());
        }

        $created = 0; $skipped = 0;
        foreach ($orders as $o) {
            // Expect keys: external_id, total_amount, marketplace_fee, discount_amount, status, items(array)
            if (Order::where('external_id', $o['external_id'] ?? null)->exists()) { $skipped++; continue; }
            $order = Order::create([
                'external_id' => $o['external_id'] ?? null,
                'channel_id' => 'tiktok',
                'status' => $o['status'] ?? 'new',
                'total_amount' => $o['total_amount'] ?? 0,
                'marketplace_fee' => $o['marketplace_fee'] ?? 0,
                'discount_amount' => $o['discount_amount'] ?? 0,
            ]);

            // items mapping left as exercise - marketplaces differ
            $created++;
        }

        return redirect()->back()->with('success', "Orders processed: created={$created}, skipped={$skipped}");
    }
}
