@extends('layouts.staff')

@section('title', 'Dashboard Kasir - Cafe Self-Ordering System')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard Kasir</h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">Kelola konfirmasi pembayaran tunai & kirim orderan ke dapur</p>
        </div>
        <div>
            <span class="badge badge-primary" style="padding: 8px 14px; font-size: 0.85rem;">
                🔴 LIVE POLLING ACTIVE
            </span>
        </div>
    </div>

    <!-- Executive Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700;">ORDER BARU</div>
            <div class="stat-val">{{ $stats['order_baru'] }}</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--warning);">
            <div style="font-size: 0.85rem; color: var(--warning); font-weight: 700;">MENUNGGU PEMBAYARAN</div>
            <div class="stat-val" style="color: var(--warning);">{{ $stats['menunggu_pembayaran'] }}</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--info);">
            <div style="font-size: 0.85rem; color: var(--info); font-weight: 700;">SEDANG DIPROSES</div>
            <div class="stat-val" style="color: var(--info);">{{ $stats['diproses'] }}</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--success);">
            <div style="font-size: 0.85rem; color: var(--success); font-weight: 700;">SELESAI HARI INI</div>
            <div class="stat-val" style="color: var(--success);">{{ $stats['selesai'] }}</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--primary);">
            <div style="font-size: 0.85rem; color: var(--primary); font-weight: 700;">OMSET HARI INI</div>
            <div class="stat-val" style="font-size: 1.5rem;">Rp{{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap;">
        <a href="{{ route('cashier.dashboard', ['status' => 'all']) }}" class="btn {{ $currentFilter === 'all' ? 'btn-primary' : 'btn-outline' }} btn-sm">Semua Order</a>
        <a href="{{ route('cashier.dashboard', ['status' => 'unpaid']) }}" class="btn {{ $currentFilter === 'unpaid' ? 'btn-primary' : 'btn-outline' }} btn-sm">Belum Dibayar (CASH)</a>
        <a href="{{ route('cashier.dashboard', ['status' => 'paid']) }}" class="btn {{ $currentFilter === 'paid' ? 'btn-primary' : 'btn-outline' }} btn-sm">Sudah Dibayar (LUNAS)</a>
        <a href="{{ route('cashier.dashboard', ['status' => 'processing']) }}" class="btn {{ $currentFilter === 'processing' ? 'btn-primary' : 'btn-outline' }} btn-sm">Sedang Diproses Dapur</a>
        <a href="{{ route('cashier.dashboard', ['status' => 'completed']) }}" class="btn {{ $currentFilter === 'completed' ? 'btn-primary' : 'btn-outline' }} btn-sm">Selesai</a>
    </div>

    <!-- Orders Table -->
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Meja</th>
                    <th>Pelanggan</th>
                    <th>Item Pesanan</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Bayar</th>
                    <th>Status Order</th>
                    <th>Aksi Kasir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td style="font-weight: 800; color: var(--primary);">
                            {{ $order->order_number }}
                            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 400;">{{ $order->created_at->format('H:i:s') }}</div>
                        </td>
                        <td style="font-weight: 800;">Meja {{ $order->table->table_number }}</td>
                        <td style="font-weight: 600;">{{ $order->customer_name }}</td>
                        <td>
                            <ul style="padding-left: 14px; margin: 0; font-size: 0.85rem;">
                                @foreach($order->items as $item)
                                    <li>
                                        <strong>{{ $item->product->name }}</strong> × {{ $item->quantity }}
                                        @if($item->notes)
                                            <span style="color: var(--danger); font-style: italic;">({{ $item->notes }})</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td style="font-weight: 800; color: var(--primary);">
                            Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ strtoupper($order->payment_method) }}</span>
                        </td>
                        <td>
                            @if($order->payment_status === 'PAID')
                                <span class="badge badge-success">LUNAS</span>
                            @else
                                <span class="badge badge-warning">UNPAID</span>
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
                            <span class="badge {{ $badgeCls }}">{{ str_replace('_', ' ', $order->order_status) }}</span>
                        </td>
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                @if($order->payment_status === 'UNPAID' && $order->order_status !== 'CANCELLED')
                                    <form action="{{ route('cashier.orders.confirm-payment', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-accent btn-sm btn-block">
                                            💵 Konfirmasi Bayar (PAID)
                                        </button>
                                    </form>
                                @endif

                                @if($order->payment_status === 'PAID' && $order->order_status === 'PENDING')
                                    <form action="{{ route('cashier.orders.send-kitchen', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm btn-block">
                                            🍳 Kirim ke Dapur
                                        </button>
                                    </form>
                                @endif

                                @if($order->order_status === 'PENDING')
                                    <form action="{{ route('cashier.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan order ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm btn-block" style="color: var(--danger); border-color: var(--danger-bg);">
                                            ❌ Batal
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 30px; color: var(--text-muted);">
                            Belum ada transaksi pesanan yang sesuai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $orders->links() }}
    </div>
@endsection

@push('scripts')
<script>
    // Live poll refresh cashier table every 6 seconds
    setInterval(function() {
        fetch(window.location.href, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            // Check if stats changed, reload page
            window.location.reload();
        }).catch(err => {});
    }, 6000);
</script>
@endpush
