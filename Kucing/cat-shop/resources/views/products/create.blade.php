@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Product</h1>
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Name</label>
                <input name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>SKU</label>
                <input name="sku" class="form-control">
            </div>
            <div class="form-group">
                <label>Cost Price</label>
                <input name="cost_price" type="number" step="0.01" class="form-control" value="0">
            </div>
            <div class="form-group">
                <label>Sell Price</label>
                <input name="sell_price" type="number" step="0.01" class="form-control" value="0">
            </div>
            <div class="form-group">
                <label>Stock Qty</label>
                <input name="stock_qty" type="number" class="form-control" value="0">
            </div>
            <div class="form-group mt-2">
                <label>Photo</label>
                <input name="image" type="file" accept="image/*" class="form-control">
            </div>
            <button class="btn btn-primary mt-2">Save</button>
        </form>
    </div>
@endsection
