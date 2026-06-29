<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cat Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brand: #2b547e;
            --accent: #f6b042
        }

        body {
            padding-top: 1rem;
            font-size: 18px;
            line-height: 1.4;
            color: #222
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--brand) !important
        }

        .nav-link {
            font-size: 1.05rem
        }

        .container {
            max-width: 1100px
        }

        h1 {
            font-size: 2.25rem;
            margin-bottom: 0.5rem
        }

        .lead-helper {
            font-size: 1rem;
            color: #444;
            margin-bottom: 1rem
        }

        .btn-large {
            padding: .75rem 1.1rem;
            font-size: 1.05rem
        }

        table thead th {
            font-weight: 700
        }

        table tbody td {
            vertical-align: middle;
            padding: .9rem
        }

        .empty-state {
            padding: 2.5rem;
            border: 1px dashed #ddd;
            border-radius: 8px;
            background: #fbfbfb
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg" style="background:#ffffff;border-bottom:1px solid #e9ecef;">
        <div class="container">
            <a class="navbar-brand" href="/">Cat Shop</a>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary btn-large" href="{{ route('products.index') }}">Products</a>
                <a class="btn btn-outline-secondary btn-large" href="{{ route('orders.index') }}">Orders</a>
                <a class="btn btn-outline-secondary btn-large" href="{{ route('reports.index') }}">Reports</a>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    </div>
    <div class="hero-banner" style="background:linear-gradient(90deg,#ff7a7a33,#ffd27f33);">
        <div class="container d-flex align-items-center justify-content-between py-4">
            <div>
                <h2 style="font-size:1.6rem;margin:0;color:var(--brand)">Kelola Toko Kucing dengan Mudah</h2>
                <p class="lead-helper" style="margin:0.25rem 0 0">Antarmuka sederhana, tombol besar, dan panduan yang
                    jelas — dibuat agar mudah digunakan.</p>
            </div>
            <div>
                <img src="https://via.placeholder.com/180x100.png?text=Cat+Shop" alt="Cat"
                    style="border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.08)">
            </div>
        </div>
    </div>
    @yield('content')
    <footer class="container mt-5 mb-4 text-muted" style="font-size:0.95rem">
        <div class="d-flex justify-content-between">
            <div>Need help? Run <strong>./setup.ps1</strong> once or contact admin.</div>
            <div>© Cat Shop</div>
        </div>
    </footer>
</body>

</html>
