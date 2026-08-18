@extends('layouts.customer')

@section('title', 'Status Pesanan - ' . $order->order_number)

@section('content')
    <header class="customer-header" style="text-align: center; padding-bottom: 20px;">
        <div style="width: 52px; height: 52px; background: rgba(212, 163, 115, 0.2); border: 1.5px solid var(--accent); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px auto; color: var(--accent);">
            <svg class="svg-icon svg-icon-xl" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
        </div>
        <h1 style="font-size: 1.3rem; font-weight: 800;">Status Pesanan Anda</h1>
        <p style="font-size: 0.8rem; color: var(--accent);">Order #{{ $order->order_number }} • {{ $order->table ? 'Meja ' . $order->table->table_number : 'Takeaway' }}</p>
    </header>

    <div style="padding: 20px 16px;">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Order Status Stepper Card -->
        <div style="background: #ffffff; border-radius: var(--radius-lg); padding: 20px; box-shadow: var(--shadow-md); border: 1px solid var(--border-color); margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; margin-bottom: 16px;">
                <div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">PEMBAYARAN</div>
                    @if($order->payment_status === 'PAID')
                        <span class="badge badge-success">LUNAS ({{ strtoupper($order->payment_method) }})</span>
                    @else
                        <span class="badge badge-warning">MENUNGGU PEMBAYARAN</span>
                    @endif
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">STATUS PESANAN</div>
                    @php
                        $statusBadgeClass = match($order->order_status) {
                            'PENDING' => 'badge-warning',
                            'WAITING_KITCHEN' => 'badge-info',
                            'PROCESSING' => 'badge-warning',
                            'READY', 'COMPLETED' => 'badge-success',
                            'CANCELLED' => 'badge-danger',
                            default => 'badge-primary',
                        };
                    @endphp
                    <span class="badge {{ $statusBadgeClass }}">{{ str_replace('_', ' ', $order->order_status) }}</span>
                </div>
            </div>

            <!-- Visual Progress Stepper -->
            <div style="margin: 20px 0 10px 0;">
                <div style="display: flex; justify-content: space-between; position: relative;">
                    <!-- Stepper Line -->
                    <div style="position: absolute; top: 14px; left: 10%; width: 80%; height: 3px; background: var(--border-color); z-index: 1;"></div>

                    @php
                        $steps = ['PENDING', 'WAITING_KITCHEN', 'PROCESSING', 'COMPLETED'];
                        $stepLabels = ['Order', 'Dapur', 'Diproses', 'Selesai'];
                        $currentIndex = array_search($order->order_status, $steps);
                        if ($currentIndex === false && $order->order_status === 'READY') $currentIndex = 3;
                    @endphp

                    @foreach($steps as $idx => $step)
                        @php
                            $isPassed = $currentIndex !== false && $idx <= $currentIndex;
                            $stepColor = $isPassed ? 'var(--primary)' : 'var(--border-color)';
                            $textColor = $isPassed ? 'var(--primary)' : 'var(--text-muted)';
                        @endphp
                        <div style="text-align: center; position: relative; z-index: 2;">
                            <div style="width: 30px; height: 30px; border-radius: 50%; background: {{ $isPassed ? 'var(--accent)' : '#ffffff' }}; border: 2px solid {{ $stepColor }}; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: {{ $isPassed ? '#fff' : 'var(--text-muted)' }}; font-weight: 800; font-size: 0.8rem;">
                                {{ $idx + 1 }}
                            </div>
                            <div style="font-size: 0.72rem; font-weight: 700; color: {{ $textColor }}; margin-top: 6px;">{{ $stepLabels[$idx] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($order->payment_method === 'cash' && $order->payment_status === 'UNPAID')
                <div style="margin-top: 16px; padding: 12px; background: var(--warning-bg); border-radius: var(--radius-sm); font-size: 0.82rem; color: var(--warning); text-align: center; display: flex; align-items: center; justify-content: center; gap: 6px;">
                    <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span><strong>Instruksi:</strong> Silakan mendatangi Kasir dan bayar tunai sejumlah <strong>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong> agar pesanan langsung diproses dapur.</span>
                </div>
            @endif
        </div>

        <!-- Order Items Detail Card -->
        <div style="background: #ffffff; border-radius: var(--radius-lg); padding: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); margin-bottom: 24px;">
            <div style="font-size: 0.95rem; font-weight: 800; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; margin-bottom: 12px;">
                Detail Pesanan (Pelanggan: {{ $order->customer_name }})
            </div>

            @foreach($order->items as $item)
                <div style="padding: 8px 0; border-bottom: 1px dashed var(--border-color);">
                    <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 0.9rem;">
                        <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                        <span style="color: var(--primary);">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($item->notes)
                        <div style="font-size: 0.78rem; color: var(--danger); font-style: italic; margin-top: 2px;">
                            Catatan: {{ $item->notes }}
                        </div>
                    @endif
                </div>
            @endforeach

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px; padding-top: 10px; font-weight: 800; font-size: 1.1rem; color: var(--primary);">
                <span>TOTAL</span>
                <span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <a href="{{ route('customer.menu', ['table' => $order->table ? $order->table->table_number : '01']) }}" class="btn btn-outline btn-block" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Tambah Pesanan Lain</span>
        </a>
    </div>
@endsection

@push('scripts')
<script>
    // Poll order status live every 4 seconds
    setInterval(function() {
        fetch("{{ route('customer.order.status', ['order_number' => $order->order_number]) }}", {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            // Reload page if order_status or payment_status changes
            if (data.order_status !== "{{ $order->order_status }}" || data.payment_status !== "{{ $order->payment_status }}") {
                window.location.reload();
            }
        });
    }, 4000);
</script>
@endpush
