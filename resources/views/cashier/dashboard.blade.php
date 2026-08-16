@extends('layouts.staff')

@section('title', 'Dashboard Kasir - Cafe Self-Ordering System')

@section('content')
    <div class="page-header" style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 46px; height: 46px; border-radius: 14px; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%); display: flex; align-items: center; justify-content: center; color: #fff; box-shadow: 0 4px 14px rgba(212, 163, 115, 0.35);">
                <svg class="svg-icon svg-icon-lg" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
            </div>
            <div>
                <h1 class="page-title" style="font-size: 1.6rem; margin-bottom: 2px;">Dashboard Kasir</h1>
                <p style="font-size: 0.875rem; color: var(--text-muted);">Kelola konfirmasi pembayaran tunai & pengiriman pesanan ke dapur</p>
            </div>
        </div>
    </div>

    <!-- Executive Stats Cards Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Order Baru</span>
                <div class="stat-card-icon" style="background: var(--accent-light); color: var(--primary);">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                </div>
            </div>
            <div class="stat-val">{{ $stats['order_baru'] }}</div>
        </div>

        <div class="stat-card" style="border-left: 4px solid var(--warning);">
            <div class="stat-card-header">
                <span style="font-size: 0.8rem; color: var(--warning); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Belum Dibayar</span>
                <div class="stat-card-icon" style="background: var(--warning-bg); color: var(--warning);">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
            </div>
            <div class="stat-val" style="color: var(--warning);">{{ $stats['menunggu_pembayaran'] }}</div>
        </div>

        <div class="stat-card" style="border-left: 4px solid var(--info);">
            <div class="stat-card-header">
                <span style="font-size: 0.8rem; color: var(--info); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Sedang Diproses</span>
                <div class="stat-card-icon" style="background: var(--info-bg); color: var(--info);">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path></svg>
                </div>
            </div>
            <div class="stat-val" style="color: var(--info);">{{ $stats['diproses'] }}</div>
        </div>

        <div class="stat-card" style="border-left: 4px solid var(--success);">
            <div class="stat-card-header">
                <span style="font-size: 0.8rem; color: var(--success); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Selesai Hari Ini</span>
                <div class="stat-card-icon" style="background: var(--success-bg); color: var(--success);">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
            </div>
            <div class="stat-val" style="color: var(--success);">{{ $stats['selesai'] }}</div>
        </div>

        <div class="stat-card" style="border-left: 4px solid var(--primary); background: linear-gradient(135deg, #ffffff 0%, #faf6f0 100%);">
            <div class="stat-card-header">
                <span style="font-size: 0.8rem; color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Omset</span>
                <div class="stat-card-icon" style="background: var(--accent-light); color: var(--primary);">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
            </div>
            <div class="stat-val" style="font-size: 1.55rem; color: var(--primary);">Rp{{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Filter Pills Bar -->
    <div class="cashier-filter-tabs">
        <a href="{{ route('cashier.dashboard', ['status' => 'all']) }}" class="filter-pill {{ $currentFilter === 'all' ? 'active' : '' }}">
            <span>Semua Order</span>
        </a>
        <a href="{{ route('cashier.dashboard', ['status' => 'unpaid']) }}" class="filter-pill {{ $currentFilter === 'unpaid' ? 'active' : '' }}">
            <span>Belum Dibayar</span>
            @if($stats['menunggu_pembayaran'] > 0)
                <span class="pill-badge">{{ $stats['menunggu_pembayaran'] }}</span>
            @endif
        </a>
        <a href="{{ route('cashier.dashboard', ['status' => 'paid']) }}" class="filter-pill {{ $currentFilter === 'paid' ? 'active' : '' }}">
            <span>Lunas (Perlu Dikirim)</span>
        </a>
        <a href="{{ route('cashier.dashboard', ['status' => 'processing']) }}" class="filter-pill {{ $currentFilter === 'processing' ? 'active' : '' }}">
            <span>Proses Dapur</span>
            @if($stats['diproses'] > 0)
                <span class="pill-badge">{{ $stats['diproses'] }}</span>
            @endif
        </a>
        <a href="{{ route('cashier.dashboard', ['status' => 'completed']) }}" class="filter-pill {{ $currentFilter === 'completed' ? 'active' : '' }}">
            <span>Selesai</span>
        </a>

        <div style="margin-left: auto;">
            <form action="{{ route('cashier.orders.clear-completed') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA pesanan yang sudah Selesai atau Dibatalkan?')">
                @csrf
                <button type="submit" class="filter-pill" style="color: var(--danger); border-color: #ffcdd2; background: #fff5f5;">
                    <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    <span>Bersihkan Pesanan Selesai/Batal</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="table-responsive has-dropdown" style="border-radius: var(--radius-md); border: 1px solid var(--border-color); background: #ffffff; box-shadow: var(--shadow-sm);">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 130px;">Order #</th>
                    <th style="width: 100px;">Meja</th>
                    <th style="width: 140px;">Pelanggan</th>
                    <th>Detail Pesanan</th>
                    <th style="width: 130px;">Total</th>
                    <th style="width: 110px;">Metode</th>
                    <th style="width: 110px;">Pembayaran</th>
                    <th style="width: 140px;">Status Order</th>
                    <th style="width: 70px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <div style="font-weight: 800; color: var(--primary); font-size: 0.95rem;">
                                {{ $order->order_number }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500; margin-top: 2px; display: flex; align-items: center; gap: 4px;">
                                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                <span>{{ $order->created_at->format('H:i:s') }}</span>
                            </div>
                        </td>
                        <td>
                            <span style="font-weight: 800; background: var(--accent-light); color: var(--primary); padding: 4px 10px; border-radius: 8px; font-size: 0.85rem; border: 1px solid rgba(212,163,115,0.3);">
                                Meja {{ $order->table->table_number }}
                            </span>
                        </td>
                        <td style="font-weight: 600; color: var(--text-dark);">
                            {{ $order->customer_name }}
                        </td>
                        <td>
                            <ul class="order-items-list">
                                @foreach($order->items as $item)
                                    <li>
                                        <span class="order-item-badge">
                                            <span class="order-item-qty">{{ $item->quantity }}x</span>
                                            <span>{{ $item->product->name }}</span>
                                        </span>
                                        @if($item->notes)
                                            <span class="order-item-note">"{{ $item->notes }}"</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td style="font-weight: 800; color: var(--primary); font-size: 0.95rem;">
                            Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge badge-primary" style="font-size: 0.725rem;">
                                {{ strtoupper($order->payment_method) }}
                            </span>
                        </td>
                        <td>
                            @if($order->payment_status === 'PAID')
                                <span class="badge badge-success" style="font-size: 0.725rem;">LUNAS</span>
                            @else
                                <span class="badge badge-warning" style="font-size: 0.725rem;">UNPAID</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeCls = match($order->order_status) {
                                    'PENDING' => 'badge-warning',
                                    'WAITING_KITCHEN' => 'badge-info',
                                    'PROCESSING' => 'badge-warning',
                                    'READY', 'COMPLETED' => 'badge-success',
                                    'CANCELLED' => 'badge-danger',
                                    default => 'badge-primary'
                                };
                            @endphp
                            <span class="badge {{ $badgeCls }}" style="font-size: 0.725rem;">
                                {{ str_replace('_', ' ', $order->order_status) }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <div class="action-dropdown" id="dropdown-{{ $order->id }}">
                                <button type="button" class="btn-dots" onclick="toggleActionDropdown(event, 'dropdown-{{ $order->id }}')" title="Pilihan Aksi">
                                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24">
                                        <circle cx="12" cy="5" r="2" fill="currentColor"></circle>
                                        <circle cx="12" cy="12" r="2" fill="currentColor"></circle>
                                        <circle cx="12" cy="19" r="2" fill="currentColor"></circle>
                                    </svg>
                                </button>
                                <div class="action-dropdown-menu">
                                    <div class="action-dropdown-header">Aksi Order #{{ $order->order_number }}</div>

                                    @if($order->payment_status === 'UNPAID' && $order->order_status !== 'CANCELLED')
                                        <form action="{{ route('cashier.orders.confirm-payment', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="action-dropdown-item item-success">
                                                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                                <span>Konfirmasi Bayar (PAID)</span>
                                            </button>
                                        </form>
                                    @endif

                                    @if($order->payment_status === 'PAID' && $order->order_status === 'PENDING')
                                        <form action="{{ route('cashier.orders.send-kitchen', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="action-dropdown-item item-primary">
                                                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path></svg>
                                                <span>Kirim ke Dapur</span>
                                            </button>
                                        </form>
                                    @endif

                                    @if($order->order_status === 'PENDING')
                                        <form action="{{ route('cashier.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan order ini?')">
                                            @csrf
                                            <button type="submit" class="action-dropdown-item item-danger">
                                                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                                <span>Batalkan Order</span>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Delete Order Action -->
                                    <form action="{{ route('cashier.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permanen Order #{{ $order->order_number }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-dropdown-item item-danger" style="color: var(--danger);">
                                            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span>Hapus Pesanan</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                            <div style="width: 48px; height: 48px; background: var(--accent-light); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px auto; color: var(--primary);">
                                <svg class="svg-icon svg-icon-lg" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                            </div>
                            <div style="font-weight: 700; font-size: 1rem; color: var(--text-dark);">Belum ada transaksi</div>
                            <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">Tidak ada data pesanan yang sesuai dengan filter saat ini.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $orders->links() }}
    </div>
@endsection

@push('scripts')
<style>
@keyframes pulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(1.2); }
    100% { opacity: 1; transform: scale(1); }
}
</style>

<script>
    function toggleActionDropdown(event, dropdownId) {
        event.stopPropagation();
        const target = document.getElementById(dropdownId);
        const isOpen = target.classList.contains('open');

        // Close any other open dropdowns
        document.querySelectorAll('.action-dropdown.open').forEach(el => {
            if (el.id !== dropdownId) {
                el.classList.remove('open');
                const btn = el.querySelector('.btn-dots');
                if (btn) btn.classList.remove('active');
            }
        });

        // Toggle current dropdown
        if (isOpen) {
            target.classList.remove('open');
            target.querySelector('.btn-dots').classList.remove('active');
        } else {
            target.classList.add('open');
            target.querySelector('.btn-dots').classList.add('active');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.action-dropdown')) {
            document.querySelectorAll('.action-dropdown.open').forEach(el => {
                el.classList.remove('open');
                const btn = el.querySelector('.btn-dots');
                if (btn) btn.classList.remove('active');
            });
        }
    });

    // Close dropdown on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.action-dropdown.open').forEach(el => {
                el.classList.remove('open');
                const btn = el.querySelector('.btn-dots');
                if (btn) btn.classList.remove('active');
            });
        }
    });

    // Live poll refresh cashier table every 6 seconds (only if user is not interacting with a dropdown)
    setInterval(function() {
        const hasOpenDropdown = document.querySelector('.action-dropdown.open');
        if (!hasOpenDropdown) {
            fetch(window.location.href, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                window.location.reload();
            }).catch(err => {});
        }
    }, 6000);
</script>
@endpush

