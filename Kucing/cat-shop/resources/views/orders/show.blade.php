@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Order #{{ $order->id }} ({{ $order->external_id }})</h1>
    <p><strong>Channel:</strong> {{ $order->channel_id }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <p><strong>Total:</strong> {{ $order->total_amount }}</p>
    <p><strong>Fees:</strong> {{ $order->marketplace_fee }}</p>
    <p><strong>Discounts:</strong> {{ $order->discount_amount }}</p>
    <p><strong>Cost:</strong> {{ $order->total_cost }}</p>
    <p><strong>Profit:</strong> {{ $order->profit }}</p>

    <h3>Items</h3>
    <table class="table">
        <thead><tr><th>SKU</th><th>Qty</th><th>Price</th><th>Cost</th></tr></thead>
        <tbody>
        @foreach($order->orderItems as $it)
            <tr>
                <td>{{ $it->sku }}</td>
                <td>{{ $it->qty }}</td>
                <td>{{ $it->price }}</td>
                <td>{{ $it->cost_price }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('orders.edit', $order) }}" class="btn btn-secondary">Edit</a>
    <a href="{{ route('orders.index') }}" class="btn btn-link">Back</a>
</div>
@endsection
