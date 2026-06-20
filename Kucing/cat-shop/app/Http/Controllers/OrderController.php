<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Channel;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderItems')->orderBy('created_at', 'desc')->paginate(20);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('orderItems');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load('orderItems');
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|string',
            'marketplace_fee' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        $order->update($data);
        // You may also update items in future
        $order->calculateProfit();
        return redirect()->route('orders.index')->with('success', 'Order updated');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted');
    }

    // Webhook receiver for marketplace orders
    public function webhook(Request $request, $channelName)
    {
        $payload = $request->json()->all();

        // Try to find channel
        $channel = Channel::where('name', $channelName)->first();
        if (! $channel) {
            return response()->json(['error' => 'unknown channel'], 404);
        }

        // For now accept a simple order payload (must map in production)
        if (! isset($payload['external_id']) || ! isset($payload['items'])) {
            return response()->json(['error' => 'invalid payload'], 422);
        }

        // Create order if not exists
        $exists = Order::where('external_id', $payload['external_id'])->first();
        if ($exists) {
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

        foreach ($payload['items'] as $item) {
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
