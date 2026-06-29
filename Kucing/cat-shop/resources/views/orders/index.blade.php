@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1>Orders</h1>
                <div class="lead-helper">Daftar pesanan terbaru. Klik baris untuk melihat detail.</div>
            </div>
            <div>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-large">Tambah Produk</a>
                <form method="POST" action="{{ route('tiktok.syncOrders') }}" style="display:inline;" class="d-inline ms-2">
                    @csrf
                    <button class="btn btn-outline-info btn-large">Sync TikTok Orders</button>
                </form>
            </div>
        </div>

        @if ($orders->count() == 0)
            <div class="empty-state text-center">
                <p style="font-size:1.1rem;margin-bottom:0.5rem"><strong>Tidak ada pesanan</strong></p>
                <p class="text-muted">Belum ada pesanan masuk. Coba tambah produk atau sinkronisasi pesanan dari
                    marketplace.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>External</th>
                            <th>Channel</th>
                            <th>Total</th>
                            <th>Cost</th>
                            <th>Profit</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $o)
                            <tr style="cursor:pointer" onclick="window.location='{{ route('orders.show', $o) }}'">
                                <td>{{ $o->id }}</td>
                                <td>{{ $o->external_id }}</td>
                                <td>{{ $o->channel->name ?? $o->channel_id }}</td>
                                <td>{{ number_format($o->total_amount, 0, ',', '.') }}</td>
                                <td>{{ number_format($o->total_cost, 0, ',', '.') }}</td>
                                <td>{{ number_format($o->profit, 0, ',', '.') }}</td>
                                <td>{{ $o->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        @endif
    </div>
@endsection
