<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Order #{{ $order->order_number }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        @page {
            size: auto;
            margin: 0mm;
        }

        body {
            background-color: #f0ede8;
            padding: 20px 10px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            font-family: 'Courier New', Courier, monospace;
        }

        .receipt-container {
            width: 300px;
            max-width: 100%;
            background: #ffffff;
            padding: 16px 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-radius: 6px;
            color: #000000;
            font-size: 11.5px;
            line-height: 1.3;
        }

        .receipt-header {
            text-align: center;
            border-bottom: 1px dashed #444;
            padding-bottom: 12px;
            margin-bottom: 10px;
        }

        .receipt-title {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .receipt-subtitle {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
        }

        .receipt-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 11px;
        }

        .receipt-divider {
            border-top: 1px dashed #444;
            margin: 8px 0;
        }

        .receipt-items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
        }

        .receipt-items-table th {
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            border-bottom: 1px dashed #444;
            padding-bottom: 4px;
        }

        .receipt-items-table td {
            padding: 4px 0;
            vertical-align: top;
            font-size: 11.5px;
        }

        .item-notes {
            font-size: 10px;
            color: #666;
            font-style: italic;
            padding-left: 8px;
        }

        .receipt-total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 13px;
            margin: 4px 0;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px dashed #444;
            font-size: 11px;
            color: #555;
        }

        .no-print-bar {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
            background: rgba(30, 20, 14, 0.92);
            backdrop-filter: blur(8px);
            padding: 10px 18px;
            border-radius: 30px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.25);
            z-index: 9999;
        }

        @media print {
            @page {
                size: auto;
                margin: 0mm;
            }

            html, body {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
                color: #000000 !important;
            }

            .receipt-container {
                width: 100% !important;
                max-width: 100% !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 3mm 2mm !important;
                margin: 0 !important;
            }

            .no-print-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container" id="receipt">
        <div class="receipt-header">
            <div class="receipt-title">CAFE SELF-ORDERING</div>
            <div class="receipt-subtitle">Jl. Coffee Boulevard No. 88, Jakarta</div>
            <div class="receipt-subtitle">Telp: 0812-3456-7890</div>
        </div>

        <div class="receipt-info-row">
            <span>No. Order:</span>
            <strong>#{{ $order->order_number }}</strong>
        </div>
        <div class="receipt-info-row">
            <span>Tanggal:</span>
            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="receipt-info-row">
            <span>Kasir:</span>
            <span>{{ $order->payment?->payload['cashier_name'] ?? (auth()->user()->name ?? 'Kasir') }}</span>
        </div>
        <div class="receipt-info-row">
            <span>Pelanggan:</span>
            <span>{{ $order->customer_name }}</span>
        </div>
        <div class="receipt-info-row">
            <span>Tipe / Meja:</span>
            <strong>{{ $order->table ? 'Meja ' . $order->table->table_number : 'TAKEAWAY (Bungkus)' }}</strong>
        </div>

        <div class="receipt-divider"></div>

        <table class="receipt-items-table">
            <thead>
                <tr>
                    <th style="width: 52%;">Item</th>
                    <th style="width: 16%; text-align: center;">Qty</th>
                    <th style="width: 32%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalQty = 0;
                    $calculatedSubtotal = 0;
                @endphp
                @foreach($order->items as $item)
                    @php
                        $itemSubtotal = $item->subtotal ?? ($item->price * $item->quantity);
                        $calculatedSubtotal += $itemSubtotal;
                        $totalQty += $item->quantity;
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight: bold;">{{ $item->product->name ?? 'Menu Item' }}</div>
                            <div style="font-size: 10px; color: #555;">@ Rp{{ number_format($item->price, 0, ',', '.') }}</div>
                            @if($item->notes)
                                <div class="item-notes">"{{ $item->notes }}"</div>
                            @endif
                        </td>
                        <td style="text-align: center; vertical-align: middle;">{{ $item->quantity }}</td>
                        <td style="text-align: right; vertical-align: middle; font-weight: bold;">Rp{{ number_format($itemSubtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="receipt-divider"></div>

        <div class="receipt-info-row">
            <span>Total Item (Qty):</span>
            <span>{{ $totalQty }} item</span>
        </div>
        <div class="receipt-info-row">
            <span>Subtotal:</span>
            <span>Rp{{ number_format($calculatedSubtotal > 0 ? $calculatedSubtotal : $order->total_amount, 0, ',', '.') }}</span>
        </div>
        <div class="receipt-total-row">
            <span>TOTAL:</span>
            <span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>

        <div class="receipt-divider"></div>

        <div class="receipt-info-row">
            <span>Metode Bayar:</span>
            <strong>{{ strtoupper($order->payment_method ?? 'CASH') }}</strong>
        </div>

        @php
            $cashReceived = $order->payment?->payload['cash_received'] ?? $order->total_amount;
            $cashChange = $order->payment?->payload['cash_change'] ?? 0;
        @endphp

        @if(($order->payment_method ?? 'cash') === 'cash')
            <div class="receipt-info-row">
                <span>Tunai Diterima:</span>
                <span>Rp{{ number_format($cashReceived, 0, ',', '.') }}</span>
            </div>
            <div class="receipt-info-row">
                <span>Kembalian:</span>
                <strong style="color: #111;">Rp{{ number_format($cashChange, 0, ',', '.') }}</strong>
            </div>
        @endif

        <div class="receipt-info-row">
            <span>Status:</span>
            <strong>{{ $order->payment_status === 'PAID' ? 'LUNAS (PAID)' : 'BELUM LUNAS (UNPAID)' }}</strong>
        </div>

        <div class="receipt-footer">
            <div>Terima Kasih Atas Kunjungan Anda!</div>
            <div style="font-size: 10px; margin-top: 4px;">WiFi: CafeGuest / Pass: ngopidulu</div>
            <div style="font-size: 9px; margin-top: 6px; color: #888;">Simpan struk ini sebagai bukti pembayaran yang sah.</div>
        </div>
    </div>

    <div class="no-print-bar">
        <button onclick="window.print()" class="btn btn-accent btn-sm" style="font-weight: 700; cursor: pointer;">
            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            <span>Cetak Struk (Print)</span>
        </button>
        <button onclick="window.close()" class="btn btn-outline btn-sm" style="color: #fff; border-color: rgba(255,255,255,0.3); font-weight: 600;">
            <span>Tutup</span>
        </button>
    </div>

    <script>
        // Auto print prompt if query param ?print=1 is present
        if (new URLSearchParams(window.location.search).get('print') === '1') {
            window.addEventListener('load', () => {
                window.print();
            });
        }
    </script>
</body>
</html>
