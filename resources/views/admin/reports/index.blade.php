@extends('layouts.admin')

@section('title', 'Laporan Penjualan - Admin')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Laporan Penjualan & Pendapatan</h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">Rekapitulasi transaksi dan pendapatan berdasarkan rentang tanggal</p>
        </div>
        <div>
            <a href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-accent" style="padding: 10px 20px;">
                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                <span>Export Excel (.csv)</span>
            </a>
        </div>
    </div>

    <!-- Date Filter Form -->
    <div style="background: #ffffff; padding: 20px 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 24px; box-shadow: var(--shadow-sm); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <form action="{{ route('admin.reports.index') }}" method="GET" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
            <div>
                <label class="form-label" style="margin-bottom: 6px;">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" style="padding: 10px 14px;">
            </div>

            <div>
                <label class="form-label" style="margin-bottom: 6px;">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" style="padding: 10px 14px;">
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 11px 22px;">
                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <span>Tampilkan Laporan</span>
            </button>
        </form>
    </div>

    <!-- Executive Summary Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700;">TOTAL OMSET (LUNAS)</span>
                <div class="stat-card-icon" style="background: var(--accent-light); color: var(--primary);">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
            </div>
            <div class="stat-val">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700;">TOTAL TRANSAKSI</span>
                <div class="stat-card-icon">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                </div>
            </div>
            <div class="stat-val">{{ $totalOrders }} <span style="font-size: 1rem; color: var(--text-muted); font-weight: 600;">Order</span></div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span style="font-size: 0.85rem; color: var(--success); font-weight: 700;">PENDAPATAN CASH</span>
                <div class="stat-card-icon" style="background: var(--success-bg); color: var(--success);">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>
                </div>
            </div>
            <div class="stat-val" style="color: var(--success);">Rp{{ number_format($cashRevenue, 0, ',', '.') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span style="font-size: 0.85rem; color: var(--info); font-weight: 700;">PENDAPATAN QRIS</span>
                <div class="stat-card-icon" style="background: var(--info-bg); color: var(--info);">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                </div>
            </div>
            <div class="stat-val" style="color: var(--info);">Rp{{ number_format($qrisRevenue, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Product Sales Breakdown -->
    <div style="background: #ffffff; padding: 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 24px; box-shadow: var(--shadow-sm);">
        <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
            <svg class="svg-icon svg-icon-md" style="color: var(--accent);" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
            <span>Rekap Penjualan Per Menu (Periode {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }})</span>
        </h3>
        <div class="table-responsive" style="box-shadow: none;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Total Porsi Terjual</th>
                        <th>Total Nominal Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($itemReport as $item)
                        <tr>
                            <td style="font-weight: 700; color: var(--text-dark);">{{ $item->product->name ?? 'Menu' }}</td>
                            <td><span class="badge badge-primary">{{ $item->product->category->name ?? '-' }}</span></td>
                            <td style="font-weight: 800; color: var(--accent-dark);">{{ $item->total_qty }} porsi</td>
                            <td style="font-weight: 800; color: var(--primary);">Rp{{ number_format($item->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada penjualan pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed Transactions Log -->
    <div style="background: #ffffff; padding: 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
        <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
            <svg class="svg-icon svg-icon-md" style="color: var(--accent);" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
            <span>Log Transaksi Lunas</span>
        </h3>
        <div class="table-responsive" style="box-shadow: none;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu Order</th>
                        <th>Order #</th>
                        <th>Meja</th>
                        <th>Pelanggan</th>
                        <th>Metode Bayar</th>
                        <th>Total Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $ord)
                        <tr>
                            <td style="font-size: 0.85rem; color: var(--text-muted);">{{ $ord->created_at->format('d M Y, H:i') }}</td>
                            <td style="font-weight: 700; color: var(--primary);">{{ $ord->order_number }}</td>
                            <td style="font-weight: 700;">Meja {{ $ord->table->table_number }}</td>
                            <td>{{ $ord->customer_name }}</td>
                            <td><span class="badge badge-primary">{{ strtoupper($ord->payment_method) }}</span></td>
                            <td style="font-weight: 800; color: var(--primary);">Rp{{ number_format($ord->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 24px;">Tidak ada transaksi lunas pada rentang tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
