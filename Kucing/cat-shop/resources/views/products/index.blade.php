@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1>Products</h1>
                <div class="lead-helper">Kelola produk: tambahkan, edit, atau hapus. Tombol besar untuk memudahkan.</div>
            </div>
            <div>
                <a href="{{ route('products.create') }}" class="btn btn-success btn-large">+ Produk Baru</a>
                <form method="POST" action="{{ route('tiktok.syncProducts') }}" style="display:inline;" class="d-inline ms-2">
                    @csrf
                    <button class="btn btn-outline-info btn-large">Sync TikTok Products</button>
                </form>
            </div>
        </div>

        @if ($products->count() == 0)
            <div class="empty-state text-center">
                <p style="font-size:1.1rem;margin-bottom:0.5rem"><strong>Tidak ada produk</strong></p>
                <p class="text-muted">Tambahkan produk baru agar bisa menerima pesanan.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>SKU</th>
                            <th>Name</th>
                            <th>Stock</th>
                            <th>Sell</th>
                            <th>Cost</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $p)
                            <tr>
                                <td style="width:120px">
                                    @php
                                        $imgPath = $p->image ? storage_path('app/public/products/' . $p->image) : null;
                                        $imgUrl = $p->image ? asset('storage/products/' . $p->image) : null;
                                        $missing = $p->image && !file_exists($imgPath);
                                    @endphp
                                    @if ($p->image && !$missing)
                                        <img src="{{ $imgUrl }}" alt=""
                                            style="max-height:60px;border-radius:6px">
                                    @elseif($missing)
                                        <div
                                            style="width:100%;padding:6px;border-radius:6px;background:#fff3cd;color:#856404;border:1px solid #ffeeba">
                                            Missing image
                                            <form method="POST" action="{{ route('products.replaceImage', $p) }}"
                                                enctype="multipart/form-data" class="mt-2">
                                                @csrf
                                                <input type="file" name="image" accept="image/*"
                                                    class="form-control form-control-sm mb-1">
                                                <button class="btn btn-sm btn-warning">Upload</button>
                                            </form>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('products.replaceImage', $p) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="image" accept="image/*"
                                                class="form-control form-control-sm mb-1">
                                            <input type="text" name="image_url" class="form-control form-control-sm mb-1"
                                                placeholder="or paste image URL">
                                            <button class="btn btn-sm btn-outline-primary">Upload/Use URL</button>
                                        </form>
                                    @endif
                                </td>
                                <td>{{ $p->sku }}</td>
                                <td><a href="{{ route('products.show', $p) }}">{{ $p->name }}</a></td>
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <form method="POST" action="{{ route('products.adjustStock', $p) }}"
                                            class="d-flex gap-1 align-items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input name="stock_qty" type="number" class="form-control form-control-sm"
                                                style="width:90px" value="{{ $p->stock_qty }}">
                                            <button class="btn btn-sm btn-primary">Save</button>
                                        </form>
                                    </div>
                                </td>
                                <td>{{ number_format($p->sell_price, 0, ',', '.') }}</td>
                                <td>{{ number_format($p->cost_price, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('products.edit', $p) }}" class="btn btn-sm btn-secondary">Edit</a>
                                    <form method="POST" action="{{ route('products.destroy', $p) }}"
                                        style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $products->links() }}
        @endif
    </div>
@endsection
