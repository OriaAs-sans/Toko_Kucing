@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Order #{{ $order->id }}</h1>
    <form method="POST" action="{{ route('orders.update', $order) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Status</label>
            <input name="status" class="form-control" value="{{ $order->status }}">
        </div>
        <div class="form-group">
            <label>Marketplace Fee</label>
            <input name="marketplace_fee" type="number" step="0.01" class="form-control" value="{{ $order->marketplace_fee }}">
        </div>
        <div class="form-group">
            <label>Discount Amount</label>
            <input name="discount_amount" type="number" step="0.01" class="form-control" value="{{ $order->discount_amount }}">
        </div>
        <button class="btn btn-primary mt-2">Save</button>
        <a href="{{ route('orders.index') }}" class="btn btn-link">Cancel</a>
    </form>
</div>
@endsection
