<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchOrdersJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle()
    {
        // Basic implementation that uses MarketplaceService to fetch orders
        $channels = \App\Models\Channel::all();
        $service = new \App\Services\MarketplaceService();

        foreach ($channels as $channel) {
            try {
                $orders = $service->fetchOrders($channel);
            } catch (\Throwable $e) {
                logger()->error('Marketplace fetch error for channel '.$channel->name.': '.$e->getMessage());
                continue;
            }

            foreach ($orders as $o) {
                if (empty($o['external_id'])) continue;
                if (\App\Models\Order::where('external_id', $o['external_id'])->exists()) continue;

                $order = \App\Models\Order::create([
                    'external_id' => $o['external_id'],
                    'channel_id' => $channel->id,
                    'status' => $o['status'] ?? 'pending',
                    'total_amount' => $o['total_amount'] ?? 0,
                    'marketplace_fee' => $o['marketplace_fee'] ?? 0,
                    'discount_amount' => $o['discount_amount'] ?? 0,
                ]);

                foreach ($o['items'] as $it) {
                    $order->orderItems()->create([
                        'product_id' => null,
                        'sku' => $it['sku'] ?? null,
                        'qty' => $it['qty'] ?? 1,
                        'price' => $it['price'] ?? 0,
                        'cost_price' => $it['cost_price'] ?? 0,
                    ]);
                }

                // update stock movements if product matched by SKU
                foreach ($order->orderItems as $item) {
                    if (! empty($item->product_id)) continue;
                    if (! empty($item->sku)) {
                        $product = \App\Models\Product::where('sku', $item->sku)->first();
                        if ($product) {
                            $item->product_id = $product->id;
                            $item->cost_price = $item->cost_price ?: $product->cost_price;
                            $item->save();
                            // reduce stock
                            $product->decrement('stock_qty', $item->qty);
                            \App\Models\StockMovement::create([
                                'product_id' => $product->id,
                                'change' => -1 * $item->qty,
                                'reason' => 'order_import',
                                'reference' => $order->id,
                            ]);
                        }
                    }
                }

                $order->calculateProfit();
            }
        }
    }
}
