@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Reports</h1>
    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto"><input type="date" name="from" class="form-control" value="{{ $from ?? '' }}"></div>
        <div class="col-auto"><input type="date" name="to" class="form-control" value="{{ $to ?? '' }}"></div>
        <div class="col-auto"><button class="btn btn-primary">Filter</button></div>
    </form>

    <div class="row">
        <div class="col-md-3"><div class="card p-3">Revenue<br><strong>{{ $totalRevenue }}</strong></div></div>
        <div class="col-md-3"><div class="card p-3">Cost<br><strong>{{ $totalCost }}</strong></div></div>
        <div class="col-md-3"><div class="card p-3">Fees<br><strong>{{ $totalFees }}</strong></div></div>
        <div class="col-md-3"><div class="card p-3">Profit<br><strong>{{ $profit }}</strong></div></div>
    </div>

    <h3 class="mt-4">Orders</h3>
    <table class="table">
        <thead><tr><th>ID</th><th>External</th><th>Total</th><th>Cost</th><th>Profit</th><th>Created</th></tr></thead>
        <tbody>
        @foreach($orders as $o)
            <tr>
                <td>{{ $o->id }}</td>
                <td>{{ $o->external_id }}</td>
                <td>{{ $o->total_amount }}</td>
                <td>{{ $o->total_cost }}</td>
                <td>{{ $o->profit }}</td>
                <td>{{ $o->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
