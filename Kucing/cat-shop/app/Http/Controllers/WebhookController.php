<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Order;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function receive(Request $request, $channelName)
    {
        $payload = $request->json()->all();
        $channel = Channel::where('name', $channelName)->first();
        if (! $channel) {
            return response()->json(['error' => 'unknown channel'], 404);
        }

        // Basic expected payload: external_id, items[], total_amount, marketplace_fee, discount_amount
        if (! isset($payload['external_id'])) {
            return response()->json(['error' => 'missing external_id'], 422);
        }

        if (Order::where('external_id', $payload['external_id'])->exists()) {
            return response()->json(['ok' => true]);
        }

        $order = Order::create([
            'external_id' => $payload['external_id'],
            'channel_id' => $channel->id,
            'status' => $payload['status'] ?? 'pending',
            'total_amount' => $payload['total_amount'] ?? 0,
            'marketplace_fee' => $payload['marketplace_fee'] ?? 0,
            'discount_amount' => $payload['discount_amount'] ?? 0,
        ]);

        foreach ($payload['items'] ?? [] as $item) {
            $order->orderItems()->create([
                'product_id' => null,
                'sku' => $item['sku'] ?? null,
                'qty' => $item['qty'] ?? 1,
                'price' => $item['price'] ?? 0,
                'cost_price' => $item['cost_price'] ?? 0,
            ]);
        }

        $order->calculateProfit();
        return response()->json(['ok' => true]);
    }
}
