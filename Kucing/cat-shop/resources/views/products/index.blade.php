@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">New Product</a>
    <table class="table mt-3">
        <thead>
            <tr><th>SKU</th><th>Name</th><th>Stock</th><th>Sell</th><th>Cost</th><th></th></tr>
        </thead>
        <tbody>
        @foreach($products as $p)
            <tr>
                <td>{{ $p->sku }}</td>
                <td><a href="{{ route('products.show', $p) }}">{{ $p->name }}</a></td>
                <td>{{ $p->stock_qty }}</td>
                <td>{{ $p->sell_price }}</td>
                <td>{{ $p->cost_price }}</td>
                <td>
                    <a href="{{ route('products.edit', $p) }}" class="btn btn-sm btn-secondary">Edit</a>
                    <form method="POST" action="{{ route('products.destroy', $p) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
</div>
@endsection
