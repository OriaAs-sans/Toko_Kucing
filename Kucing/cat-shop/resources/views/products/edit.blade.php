@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Product</h1>
        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Name</label>
                <input name="name" class="form-control" value="{{ $product->name }}" required>
            </div>
            <div class="form-group">
                <label>SKU</label>
                <input name="sku" class="form-control" value="{{ $product->sku }}">
            </div>
            <div class="form-group">
                <label>Cost Price</label>
                <input name="cost_price" type="number" step="0.01" class="form-control"
                    value="{{ $product->cost_price }}">
            </div>
            <div class="form-group">
                <label>Sell Price</label>
                <input name="sell_price" type="number" step="0.01" class="form-control"
                    value="{{ $product->sell_price }}">
            </div>
            <div class="form-group">
                <label>Stock Qty</label>
                <input name="stock_qty" type="number" class="form-control" value="{{ $product->stock_qty }}">
            </div>
            <div class="form-group mt-2">
                <label>Photo</label>
                @if ($product->image)
                    <div class="mb-2"><img src="{{ asset('storage/products/' . $product->image) }}" alt=""
                            style="max-height:90px;border-radius:6px"></div>
                @endif
                <input name="image" type="file" accept="image/*" class="form-control">
            </div>
            <button class="btn btn-primary mt-2">Save</button>
        </form>
    </div>
@endsection
