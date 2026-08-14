@extends('layouts.staff')

@section('title', 'Kitchen Display System (KDS) - Cafe Self-Ordering')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">🍳 Kitchen & Barista Display System</h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">Layar khusus Dapur / Barista untuk memproses orderan masuk</p>
        </div>
        <div>
            <span class="badge badge-success" style="padding: 8px 14px; font-size: 0.85rem;">
                🟢 LIVE KITCHEN FEED ACTIVE
            </span>
        </div>
    </div>

    <!-- Active Orders Grid -->
    <div class="kitchen-grid">
        @forelse($orders as $order)
            <div class="kitchen-card {{ $order->order_status === 'PROCESSING' ? 'processing' : '' }}">
                <div class="kitchen-header">
                    <div>
                        <div class="kitchen-table-num">MEJA {{ $order->table->table_number }}</div>
                        <div style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">
                            {{ $order->customer_name }} • {{ $order->order_number }}
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span class="badge {{ $order->order_status === 'PROCESSING' ? 'badge-warning' : 'badge-info' }}" style="font-size: 0.8rem; padding: 6px 12px;">
                            {{ $order->order_status === 'PROCESSING' ? 'DIPROSES' : 'BARU MASUK' }}
                        </span>
                        <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 4px; font-weight: 700;">
                            {{ $order->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

                <div class="kitchen-items">
                    @foreach($order->items as $item)
                        <div class="kitchen-item-row">
                            <div>
                                <div class="item-qty-name">
                                    <span style="color: var(--primary); font-size: 1.15rem;">{{ $item->quantity }}x</span>
                                    <span>{{ $item->product->name }}</span>
                                </div>
                                @if($item->notes)
                                    <div class="item-notes">
                                        ⚠️ {{ $item->notes }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="padding: 12px 16px; background: #faf8f5; border-top: 1px solid var(--border-color);">
                    @if($order->order_status === 'WAITING_KITCHEN')
                        <form action="{{ route('kitchen.orders.process', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-block" style="padding: 12px; font-size: 1rem; background: #ed6c02; color: #fff;">
                                🧑‍🍳 MULAI PROSES
                            </button>
                        </form>
                    @elseif($order->order_status === 'PROCESSING')
                        <form action="{{ route('kitchen.orders.complete', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block" style="padding: 12px; font-size: 1rem; background: #2e7d32; color: #fff;">
                                ✅ SELESAI & SAJIKAN
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; background: #ffffff; padding: 60px; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-color);">
                <div style="font-size: 3rem; margin-bottom: 10px;">✨</div>
                <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--text-dark);">Belum Ada Pesanan yang Perlu Diproses</h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 4px;">Pesanan yang telah divalidasi oleh kasir akan muncul di layar ini secara otomatis.</p>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
<script>
    // Live refresh kitchen feed every 4 seconds
    setInterval(function() {
        fetch(window.location.href, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            // Automatic sync reload if orders payload count or list changes
            window.location.reload();
        }).catch(err => {});
    }, 4000);
</script>
@endpush
