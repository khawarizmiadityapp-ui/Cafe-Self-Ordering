@extends('layouts.customer')

@section('title', 'Pembayaran QRIS - ' . $order->order_number)

@section('content')
    <header class="customer-header" style="text-align: center; padding-bottom: 24px;">
        <div style="width: 52px; height: 52px; background: rgba(212, 163, 115, 0.2); border: 1.5px solid var(--accent); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px auto; color: var(--accent);">
            <svg class="svg-icon svg-icon-xl" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
        </div>
        <h1 style="font-size: 1.3rem; font-weight: 800;">Pembayaran QRIS</h1>
        <p style="font-size: 0.8rem; color: var(--accent);">{{ $order->table ? 'Meja ' . $order->table->table_number : 'Takeaway' }} • {{ $order->customer_name }}</p>
    </header>

    <div style="padding: 20px 16px;">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div style="background: #ffffff; border-radius: var(--radius-lg); padding: 24px 20px; box-shadow: var(--shadow-md); border: 1px solid var(--border-color); text-align: center; margin-bottom: 20px;">
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">TOTAL PEMBAYARAN</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: var(--primary); margin: 4px 0 16px 0;">
                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
            </div>

            <!-- Dynamic QR Code Card -->
            <div style="background: var(--accent-light); padding: 16px; border-radius: var(--radius-md); display: inline-block; border: 1.5px solid var(--accent); margin-bottom: 16px;">
                <img src="{{ $qrCodeUrl }}" alt="QRIS Code" style="width: 220px; height: 220px; border-radius: 8px; display: block; margin: 0 auto;">
            </div>

            <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-dark); margin-bottom: 4px;">
                Scan QRIS dengan e-Wallet / Mobile Banking Anda
            </div>
            <div style="font-size: 0.78rem; color: var(--text-muted);">
                Gopay, OVO, ShopeePay, Dana, BCA, Mandiri, BRI, DLL.
            </div>
            <div style="font-size: 0.75rem; color: var(--accent-dark); margin-top: 8px; font-weight: 600;">
                No. Ref: {{ $order->payment->reference_number ?? 'QRIS-' . $order->order_number }}
            </div>
        </div>

        <!-- Order Summary Card -->
        <div style="background: #ffffff; border-radius: var(--radius-md); padding: 16px; border: 1px solid var(--border-color); margin-bottom: 20px;">
            <div style="font-size: 0.85rem; font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-bottom: 10px;">
                Rincian Order #{{ $order->order_number }}
            </div>
            @foreach($order->items as $item)
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; padding: 4px 0;">
                    <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                    <span style="font-weight: 700;">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <!-- Simulation Payment Button (As requested in Requirement #8 & #16) -->
        <div style="background: #fff4e5; border: 1px dashed #ed6c02; border-radius: var(--radius-md); padding: 16px; text-align: center;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #ed6c02; margin-bottom: 8px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                <span>SIMULASI GATEWAY PEMBAYARAN QRIS</span>
            </div>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 12px;">
                Klik tombol di bawah ini untuk mensimulasikan notifikasi callback pembayaran berhasil dari gateway QRIS.
            </p>
            <form action="{{ route('customer.payment.simulate', ['order_number' => $order->order_number]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-accent btn-block" style="padding: 12px; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <span>Simulasikan Pembayaran Berhasil</span>
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Poll order payment status every 4 seconds
    setInterval(function() {
        fetch("{{ route('customer.order.status', ['order_number' => $order->order_number]) }}", {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.payment_status === 'PAID') {
                window.location.href = "{{ route('customer.order.status', ['order_number' => $order->order_number]) }}";
            }
        });
    }, 4000);
</script>
@endpush
