@extends('layouts.admin')

@section('title', 'Executive Dashboard Admin')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Executive Dashboard Admin</h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">Ringkasan performa penjualan dan operasional cafe real-time</p>
        </div>
        <div>
            <span class="badge badge-primary" style="padding: 8px 16px; font-size: 0.85rem;">
                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span>Sistem Aktif</span>
            </span>
        </div>
    </div>

    <!-- Executive Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700;">TOTAL OMSET HARI INI</span>
                <div class="stat-card-icon" style="background: var(--accent-light); color: var(--primary);">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
            </div>
            <div class="stat-val">Rp{{ number_format($stats['total_penjualan_hari_ini'], 0, ',', '.') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700;">TOTAL ORDER HARI INI</span>
                <div class="stat-card-icon" style="background: #eef2ff; color: #4f46e5;">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                </div>
            </div>
            <div class="stat-val">{{ $stats['total_order_hari_ini'] }} <span style="font-size: 1rem; color: var(--text-muted); font-weight: 600;">Transaksi</span></div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span style="font-size: 0.85rem; color: var(--warning); font-weight: 700;">SEDANG DIPROSES</span>
                <div class="stat-card-icon" style="background: var(--warning-bg); color: var(--warning);">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
            </div>
            <div class="stat-val" style="color: var(--warning);">{{ $stats['order_diproses'] }} <span style="font-size: 1rem; color: var(--text-muted); font-weight: 600;">Order</span></div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span style="font-size: 0.85rem; color: var(--success); font-weight: 700;">ORDER SELESAI</span>
                <div class="stat-card-icon" style="background: var(--success-bg); color: var(--success);">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
            </div>
            <div class="stat-val" style="color: var(--success);">{{ $stats['order_selesai'] }} <span style="font-size: 1rem; color: var(--text-muted); font-weight: 600;">Order</span></div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700;">TOTAL PRODUK MENU</span>
                <div class="stat-card-icon">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path></svg>
                </div>
            </div>
            <div class="stat-val" style="font-size: 1.6rem;">{{ $stats['total_produk'] }} <span style="font-size: 1rem; color: var(--text-muted); font-weight: 600;">Menu</span></div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700;">TOTAL MEJA CAFE</span>
                <div class="stat-card-icon">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"></rect><rect x="9" y="9" width="6" height="6"></rect></svg>
                </div>
            </div>
            <div class="stat-val" style="font-size: 1.6rem;">{{ $stats['total_meja'] }} <span style="font-size: 1rem; color: var(--text-muted); font-weight: 600;">Meja</span></div>
        </div>
    </div>

    <!-- Analytics Dashboard Content -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <!-- Top Selling Products -->
        <div style="background: #ffffff; padding: 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--primary); display: flex; align-items: center; gap: 10px;">
                    <svg class="svg-icon svg-icon-md" style="color: var(--accent);" viewBox="0 0 24 24"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path></svg>
                    <span>Produk Terlaris</span>
                </h3>
                <span class="badge badge-primary">Top 5 Item</span>
            </div>
            <div class="table-responsive" style="box-shadow: none; border: none;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Menu</th>
                            <th>Terjual</th>
                            <th>Total Penjualan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $item)
                            <tr>
                                <td style="font-weight: 700; color: var(--text-dark);">{{ $item->product->name ?? 'Menu' }}</td>
                                <td style="font-weight: 800; color: var(--accent-dark);">{{ $item->total_qty }} porsi</td>
                                <td style="font-weight: 800; color: var(--primary);">Rp{{ number_format($item->total_sales, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada data penjualan produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div style="background: #ffffff; padding: 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--primary); display: flex; align-items: center; gap: 10px;">
                    <svg class="svg-icon svg-icon-md" style="color: var(--accent);" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    <span>Transaksi Terbaru</span>
                </h3>
                <a href="{{ route('cashier.dashboard') }}" style="font-size: 0.8rem; font-weight: 700; color: var(--accent-dark);">Lihat Semua ↗</a>
            </div>
            <div class="table-responsive" style="box-shadow: none; border: none;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Meja</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $ord)
                            <tr>
                                <td style="font-weight: 700; color: var(--primary);">{{ $ord->order_number }}</td>
                                <td style="font-weight: 700;">Meja {{ $ord->table->table_number }}</td>
                                <td style="font-weight: 800;">Rp{{ number_format($ord->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $ord->payment_status === 'PAID' ? 'badge-success' : 'badge-warning' }}">
                                        {{ $ord->payment_status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada transaksi terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
