@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $product->name }}</h1>
    <p><strong>SKU:</strong> {{ $product->sku }}</p>
    <p><strong>Stock:</strong> {{ $product->stock_qty }}</p>
    <p><strong>Sell:</strong> {{ $product->sell_price }}</p>
    <p><strong>Cost:</strong> {{ $product->cost_price }}</p>
</div>
@endsection
