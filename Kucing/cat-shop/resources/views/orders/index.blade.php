@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Orders</h1>
    <table class="table">
        <thead><tr><th>ID</th><th>External</th><th>Channel</th><th>Total</th><th>Cost</th><th>Profit</th><th>Created</th></tr></thead>
        <tbody>
        @foreach($orders as $o)
            <tr>
                <td>{{ $o->id }}</td>
                <td>{{ $o->external_id }}</td>
                <td>{{ $o->channel_id }}</td>
                <td>{{ $o->total_amount }}</td>
                <td>{{ $o->total_cost }}</td>
                <td>{{ $o->profit }}</td>
                <td>{{ $o->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $orders->links() }}
</div>
@endsection
