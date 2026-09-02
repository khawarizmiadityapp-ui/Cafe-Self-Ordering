@extends('layouts.admin')

@section('title', 'Executive Dashboard Admin')

@section('content')
    <!-- Dashboard Header Banner -->
    <div class="dash-hero-header">
        <div class="dash-hero-title-group">
            <h1 class="dash-hero-title">Executive Dashboard</h1>
            <p class="dash-hero-sub">Ringkasan performa penjualan & operasional bisnis kafe real-time</p>
        </div>
        <div class="dash-hero-actions">
            <div class="system-status-chip">
                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span>Operasional Aktif</span>
            </div>
        </div>
    </div>

    <!-- Executive Stats Grid -->
    <div class="stats-grid">
        <!-- Hero Omset Card -->
        <div class="stat-card stat-card-hero">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Omset Hari Ini</span>
                <div class="stat-card-icon stat-icon-gold">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
            </div>
            <div class="stat-val text-gold">Rp{{ number_format($stats['total_penjualan_hari_ini'], 0, ',', '.') }}</div>
            <div class="stat-card-footer">
                <span>Updated real-time</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Order Hari Ini</span>
                <div class="stat-card-icon stat-icon-indigo">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                </div>
            </div>
            <div class="stat-val">{{ $stats['total_order_hari_ini'] }} <span class="stat-unit">Transaksi</span></div>
            <div class="stat-card-footer text-muted">Hari ini</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title text-warning">Sedang Diproses</span>
                <div class="stat-card-icon stat-icon-warning">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
            </div>
            <div class="stat-val text-warning">{{ $stats['order_diproses'] }} <span class="stat-unit">Order</span></div>
            <div class="stat-card-footer text-warning">Antrean dapur/kasir</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title text-success">Order Selesai</span>
                <div class="stat-card-icon stat-icon-success">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
            </div>
            <div class="stat-val text-success">{{ $stats['order_selesai'] }} <span class="stat-unit">Order</span></div>
            <div class="stat-card-footer text-success">Berhasil disajikan</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Produk Menu</span>
                <div class="stat-card-icon stat-icon-coffee">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path></svg>
                </div>
            </div>
            <div class="stat-val">{{ $stats['total_produk'] }} <span class="stat-unit">Menu</span></div>
            <div class="stat-card-footer text-muted">Katalog aktif</div>
        </div>

        <div class="stat-card stat-card-hero">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Meja Cafe</span>
                <div class="stat-card-icon stat-icon-brown">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"></rect><rect x="9" y="9" width="6" height="6"></rect></svg>
                </div>
            </div>
            <div class="stat-val">{{ $stats['total_meja'] }} <span class="stat-unit">Meja</span></div>
            <div class="stat-card-footer text-muted">Area makan</div>
        </div>
    </div>

    <!-- Analytics Dashboard Content -->
    <div class="admin-split-2col">
        <!-- Top Selling Products -->
        <div class="dashboard-panel">
            <div class="panel-header-clean">
                <div class="panel-title-wrap">
                    <div class="panel-icon-badge">
                        <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path></svg>
                    </div>
                    <div>
                        <h3 class="panel-title-text">Produk Terlaris</h3>
                        <p class="panel-subtitle-text">5 Menu Paling Banyak Dipesan</p>
                    </div>
                </div>
                <span class="chip-badge chip-gold">Top 5</span>
            </div>

            <div class="dash-table-wrap">
                <table class="dash-clean-table">
                    <thead>
                        <tr>
                            <th>NAMA MENU</th>
                            <th style="text-align: center;">TERJUAL</th>
                            <th style="text-align: right;">TOTAL OMSET</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $maxQty = $topProducts->max('total_qty') ?? 1; @endphp
                        @forelse($topProducts as $index => $item)
                            @php $percentage = round(($item->total_qty / $maxQty) * 100); @endphp
                            <tr>
                                <td>
                                    <div class="prod-item-cell">
                                        <span class="rank-num rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                                        <div>
                                            <div class="prod-name-bold">{{ $item->product->name ?? 'Menu' }}</div>
                                            <div class="prod-progress-bar">
                                                <div class="prod-progress-fill" style="width: {{ $percentage }}%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <span class="qty-pill">{{ $item->total_qty }} porsi</span>
                                </td>
                                <td style="text-align: right; font-weight: 800; color: var(--primary);">
                                    Rp{{ number_format($item->total_sales, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="empty-table-cell">Belum ada data penjualan produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="dashboard-panel">
            <div class="panel-header-clean">
                <div class="panel-title-wrap">
                    <div class="panel-icon-badge icon-badge-thunder">
                        <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    </div>
                    <div>
                        <h3 class="panel-title-text">Transaksi Terbaru</h3>
                        <p class="panel-subtitle-text">Aktivitas Pemesanan Terkini</p>
                    </div>
                </div>
                <a href="{{ route('cashier.dashboard') }}" class="panel-link-action">Lihat Semua &rarr;</a>
            </div>

            <div class="dash-table-wrap">
                <table class="dash-clean-table">
                    <thead>
                        <tr>
                            <th>ORDER #</th>
                            <th>MEJA</th>
                            <th>TOTAL</th>
                            <th style="text-align: right;">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $ord)
                            <tr>
                                <td>
                                    <span class="ord-num-tag">{{ $ord->order_number }}</span>
                                </td>
                                <td>
                                    <span class="table-tag">
                                        {{ $ord->table ? 'Meja ' . $ord->table->table_number : 'Takeaway' }}
                                    </span>
                                </td>
                                <td style="font-weight: 800; color: var(--text-dark);">
                                    Rp{{ number_format($ord->total_amount, 0, ',', '.') }}
                                </td>
                                <td style="text-align: right;">
                                    <span class="status-pill {{ $ord->payment_status === 'PAID' ? 'pill-paid' : 'pill-unpaid' }}">
                                        {{ $ord->payment_status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-table-cell">Belum ada transaksi terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

