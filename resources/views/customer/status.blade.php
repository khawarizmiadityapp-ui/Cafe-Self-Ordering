@extends('layouts.customer')

@section('title', 'Pantau Status Pesanan - ' . $order->order_number)

@section('content')
    <!-- Top Header Status (Sticky) -->
    <div class="sticky-top-header" style="position: sticky; top: 0; z-index: 999; background: var(--bg-main, #faf7f2); box-shadow: 0 4px 16px rgba(0,0,0,0.12);">
        <div class="customer-header" style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px 16px 22px 16px; border-radius: 0 0 24px 24px;">
            <div style="width: 52px; height: 52px; background: rgba(212, 163, 115, 0.2); border: 1.5px solid var(--accent); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; color: var(--accent);">
                <svg style="width: 26px; height: 26px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </div>
            <h1 style="font-size: 1.3rem; font-weight: 700; color: #ffffff; margin: 0; text-align: center; width: 100%;">Pantau Status Pesanan</h1>
            <p style="font-size: 0.85rem; color: var(--accent); margin-top: 4px; font-weight: 500; text-align: center; width: 100%;">
                {{ $order->order_number }} • {{ $order->table ? 'Meja ' . $order->table->table_number : 'Takeaway' }}
            </p>
        </div>
    </div>

    <div style="flex: 1; overflow-y: auto; padding: 20px 16px 40px 16px; -webkit-overflow-scrolling: touch;">
        @if(session('success'))
            <div style="background: #e8f5e9; border: 1px solid #c8e6c9; color: #2e7d32; padding: 12px 16px; border-radius: 14px; font-size: 0.85rem; font-weight: 600; margin-bottom: 16px; text-align: center;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Visual Progress Stepper Card -->
        <div style="background: #ffffff; border-radius: 20px; padding: 20px; box-shadow: 0 4px 16px rgba(0,0,0,0.05); border: 1px solid #e7e0d6; margin-bottom: 20px;">
            
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f0e6dc; padding-bottom: 12px; margin-bottom: 18px;">
                <div>
                    <div style="font-size: 0.7rem; color: #8c7a6b; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase;">METODE PEMBAYARAN</div>
                    @if($order->payment_status === 'PAID')
                        <span style="display: inline-block; background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; padding: 3px 10px; border-radius: 12px; font-weight: 600; font-size: 0.75rem; margin-top: 2px;">
                            LUNAS ({{ strtoupper($order->payment_method) }})
                        </span>
                    @else
                        <span style="display: inline-block; background: #fff3e0; color: #e65100; border: 1px solid #ffcc80; padding: 3px 10px; border-radius: 12px; font-weight: 600; font-size: 0.75rem; margin-top: 2px;">
                            BAYAR DI KASIR
                        </span>
                    @endif
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.7rem; color: #8c7a6b; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase;">PROSES SAAT INI</div>
                    @php
                        $statusText = match($order->order_status) {
                            'PENDING' => 'Pesanan Masuk',
                            'WAITING_KITCHEN' => 'Di Dapur',
                            'PROCESSING' => 'Sedang Dimasak',
                            'READY', 'COMPLETED' => 'Selesai & Sajikan',
                            'CANCELLED' => 'Dibatalkan',
                            default => $order->order_status,
                        };
                        $statusBg = match($order->order_status) {
                            'PENDING' => '#e3f2fd',
                            'WAITING_KITCHEN' => '#fff8e1',
                            'PROCESSING' => '#e0f2f1',
                            'READY', 'COMPLETED' => '#e8f5e9',
                            default => '#f5f5f5'
                        };
                        $statusFg = match($order->order_status) {
                            'PENDING' => '#1565c0',
                            'WAITING_KITCHEN' => '#f57f17',
                            'PROCESSING' => '#00695c',
                            'READY', 'COMPLETED' => '#2e7d32',
                            default => '#424242'
                        };
                    @endphp
                    <span style="display: inline-block; background: {{ $statusBg }}; color: {{ $statusFg }}; padding: 4px 12px; border-radius: 12px; font-weight: 600; font-size: 0.775rem; margin-top: 2px;">
                        {{ $statusText }}
                    </span>
                </div>
            </div>

            <!-- Stepper Progress Line -->
            <div style="margin: 24px 0 12px 0;">
                <div style="display: flex; justify-content: space-between; position: relative;">
                    <!-- Line Background -->
                    <div style="position: absolute; top: 15px; left: 10%; width: 80%; height: 3px; background: #e7e0d6; z-index: 1;"></div>

                    @php
                        $steps = ['PENDING', 'WAITING_KITCHEN', 'PROCESSING', 'COMPLETED'];
                        $stepLabels = ['Order', 'Dapur', 'Masak', 'Selesai'];
                        $currentIndex = array_search($order->order_status, $steps);
                        if ($currentIndex === false && $order->order_status === 'READY') $currentIndex = 3;
                    @endphp

                    @foreach($steps as $idx => $step)
                        @php
                            $isPassed = $currentIndex !== false && $idx <= $currentIndex;
                            $stepBg = $isPassed ? '#3c2a21' : '#ffffff';
                            $stepBorder = $isPassed ? '#3c2a21' : '#d4c3b3';
                            $textColor = $isPassed ? '#1e140e' : '#a09083';
                        @endphp
                        <div style="text-align: center; position: relative; z-index: 2;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $stepBg }}; border: 2px solid {{ $stepBorder }}; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: {{ $isPassed ? '#ffffff' : '#a09083' }}; font-weight: 700; font-size: 0.8rem; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                                {{ $idx + 1 }}
                            </div>
                            <div style="font-size: 0.725rem; font-weight: 600; color: {{ $textColor }}; margin-top: 8px;">{{ $stepLabels[$idx] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($order->payment_method === 'cash' && $order->payment_status === 'UNPAID')
                <div style="margin-top: 18px; padding: 14px 16px; background: #faf5ee; border: 1px solid #eddcc9; border-radius: 14px; font-size: 0.85rem; color: #3c2a21; text-align: center; line-height: 1.5; font-weight: 400;">
                    Silakan sebutkan nomor pesanan <strong style="color: #1e140e; font-weight: 600;">#{{ $order->order_number }}</strong> ke Kasir untuk pembayaran tunai sebesar <strong style="color: #1e140e; font-weight: 600;">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong>.
                </div>
            @endif
        </div>

        <!-- Order Items Detail Card -->
        <div style="background: #ffffff; border-radius: 20px; padding: 20px; box-shadow: 0 4px 16px rgba(0,0,0,0.03); border: 1px solid #e7e0d6; margin-bottom: 20px;">
            <div style="font-size: 0.95rem; font-weight: 700; color: #1e140e; border-bottom: 1px solid #f0e6dc; padding-bottom: 10px; margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between;">
                <span>Rincian Pesanan</span>
                <span style="font-size: 0.8rem; color: #8c7a6b; font-weight: 500;">Pelanggan: {{ $order->customer_name }}</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 8px;">
                @foreach($order->items as $item)
                    <div style="padding: 10px 14px; background: #faf7f2; border: 1px solid #e7e0d6; border-radius: 14px;">
                        <div style="display: flex; justify-content: space-between; font-weight: 500; font-size: 0.875rem; color: #1e140e;">
                            <span>{{ $item->quantity }}x {{ $item->product->name ?? 'Menu' }}</span>
                            <span style="font-weight: 600; color: #1e140e;">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($item->notes)
                            <div style="font-size: 0.775rem; color: #8c7a6b; font-style: italic; margin-top: 4px; font-weight: 400;">
                                Catatan: {{ $item->notes }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding-top: 14px; border-top: 1px solid #f0e6dc; font-weight: 600; font-size: 0.95rem; color: #1e140e;">
                <span>Total Bayar</span>
                <span style="font-weight: 700; font-size: 1.05rem; color: #1e140e;">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <a href="{{ route('customer.menu', ['table' => $order->table ? $order->table->table_number : '01']) }}" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px; background: #ffffff; border: 1.5px solid #3c2a21; color: #3c2a21; font-weight: 700; font-size: 0.9rem; border-radius: 16px; text-decoration: none; text-align: center; transition: all 0.2s ease;">
            <span>+ Tambah Pesanan Lain</span>
        </a>
    </div>
@endsection

@push('scripts')
<script>
    // Live auto-polling status sync every 3 seconds
    setInterval(function() {
        fetch("{{ route('customer.order.status', ['order_number' => $order->order_number]) }}", {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            // Live auto-reload if order status or payment status progresses
            if (data.order_status !== "{{ $order->order_status }}" || data.payment_status !== "{{ $order->payment_status }}") {
                window.location.reload();
            }
        }).catch(err => {});
    }, 3000);
</script>
@endpush
