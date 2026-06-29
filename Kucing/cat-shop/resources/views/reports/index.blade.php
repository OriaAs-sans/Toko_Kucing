@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1>Reports</h1>
                <div class="lead-helper">Ringkasan keuangan dan pesanan. Pilih tanggal untuk memfilter.</div>
            </div>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2 align-items-center">
                    <input type="date" name="from" class="form-control" value="{{ $from ?? '' }}">
                    <input type="date" name="to" class="form-control" value="{{ $to ?? '' }}">
                    <button class="btn btn-primary btn-large">Filter</button>
                </form>
                <button id="exportCsv" class="btn btn-outline-secondary btn-large">Export CSV</button>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="text-muted">Revenue</div>
                    <div class="h4">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="text-muted">Cost</div>
                    <div class="h4">{{ number_format($totalCost ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="text-muted">Fees</div>
                    <div class="h4">{{ number_format($totalFees ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3" style="background:var(--accent);color:#222">
                    <div class="text-muted">Profit</div>
                    <div class="h4">{{ number_format($profit ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <canvas id="ordersChart" height="80"></canvas>
        </div>

        <h3 class="mt-4">Orders</h3>
        @if ($orders->count() == 0)
            <div class="empty-state">Tidak ada pesanan untuk periode ini.</div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>External</th>
                            <th>Total</th>
                            <th>Cost</th>
                            <th>Profit</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $o)
                            <tr>
                                <td>{{ $o->id }}</td>
                                <td>{{ $o->external_id }}</td>
                                <td>{{ number_format($o->total_amount, 0, ',', '.') }}</td>
                                <td>{{ number_format($o->total_cost, 0, ',', '.') }}</td>
                                <td>{{ number_format($o->profit, 0, ',', '.') }}</td>
                                <td>{{ $o->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Build a simple JSON array server-side-safe (avoid arrow fn in Blade)
        const orders = @json(
            $orders->map(function ($o) {
                    return [
                        'date' => $o->created_at->format('Y-m-d'),
                        'total' => (float) $o->total_amount,
                        'id' => $o->id,
                        'external_id' => $o->external_id,
                    ];
                })->all());
        const grouped = {};
        orders.forEach(o => grouped[o.date] = (grouped[o.date] || 0) + o.total);
        const labels = Object.keys(grouped).sort();
        const data = labels.map(l => grouped[l]);
        const ctx = document.getElementById('ordersChart');
        if (ctx && labels.length) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Revenue',
                        data,
                        backgroundColor: '#2b547e'
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        document.getElementById('exportCsv')?.addEventListener('click', () => {
            const rows = [
                ['ID', 'External', 'Total', 'Created']
            ];
            orders.forEach(o => rows.push([o.id || '', o.external_id || '', o.total || 0, o.date]));
            const csv = rows.map(r => r.map(c => '"' + String(c).replace(/"/g, '""') + '"').join(',')).join('\n');
            const blob = new Blob([csv], {
                type: 'text/csv'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'reports.csv';
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
        });
    </script>
@endsection
