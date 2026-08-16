@extends('layouts.staff')

@section('title', 'Kitchen Display System (KDS) - Cafe Self-Ordering')

@section('content')
    <div class="page-header" style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 46px; height: 46px; border-radius: 14px; background: linear-gradient(135deg, #ed6c02 0%, #c75600 100%); display: flex; align-items: center; justify-content: center; color: #fff; box-shadow: 0 4px 14px rgba(237, 108, 2, 0.35);">
                <svg class="svg-icon svg-icon-lg" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path></svg>
            </div>
            <div>
                <h1 class="page-title" style="font-size: 1.6rem; margin-bottom: 2px;">Kitchen & Barista Display System</h1>
                <p style="font-size: 0.875rem; color: var(--text-muted);">Layar khusus Dapur / Barista untuk memproses orderan masuk</p>
            </div>
        </div>
        <div>
            <span class="badge badge-success" style="padding: 8px 16px; font-size: 0.825rem; background: #ffffff; border: 1.5px solid var(--border-color); box-shadow: var(--shadow-xs); color: var(--success);">
                <span style="display: inline-block; width: 8px; height: 8px; background: var(--success); border-radius: 50%; animation: pulse 1.5s infinite; margin-right: 4px;"></span>
                LIVE KITCHEN FEED
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
                                    <div class="item-notes" style="display: flex; align-items: center; gap: 4px; margin-top: 4px;">
                                        <svg class="svg-icon svg-icon-sm" style="color: var(--danger);" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                        <span>{{ $item->notes }}</span>
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
                            <button type="submit" class="btn btn-warning btn-block" style="padding: 12px; font-size: 0.95rem; background: #ed6c02; color: #fff; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                <span>MULAI PROSES</span>
                            </button>
                        </form>
                    @elseif($order->order_status === 'PROCESSING')
                        <form action="{{ route('kitchen.orders.complete', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block" style="padding: 12px; font-size: 0.95rem; background: #2e7d32; color: #fff; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                <span>SELESAI & SAJIKAN</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; background: #ffffff; padding: 60px; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-color);">
                <div style="width: 56px; height: 56px; background: var(--accent-light); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px auto; color: var(--primary);">
                    <svg class="svg-icon svg-icon-xl" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path></svg>
                </div>
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
