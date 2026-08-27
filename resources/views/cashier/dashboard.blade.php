@extends('layouts.staff')

@section('title', 'Dashboard Kasir - Cafe Self-Ordering System')

@section('content')
    <div class="page-header" style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 46px; height: 46px; border-radius: 14px; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%); display: flex; align-items: center; justify-content: center; color: #fff; box-shadow: 0 4px 14px rgba(212, 163, 115, 0.35);">
                <svg class="svg-icon svg-icon-lg" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
            </div>
            <div>
                <h1 class="page-title" style="font-size: 1.6rem; margin-bottom: 2px;">Dashboard Kasir</h1>
                <p style="font-size: 0.875rem; color: var(--text-muted);">Kelola konfirmasi pembayaran tunai & pengiriman pesanan ke dapur</p>
            </div>
        </div>

        <div>
            <a href="{{ route('cashier.pos') }}" class="btn btn-accent" style="padding: 12px 22px; font-weight: 800; font-size: 0.95rem; border-radius: 12px; box-shadow: 0 4px 16px rgba(212, 163, 115, 0.35);">
                <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                <span>BUKA KASIR POS</span>
            </a>
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
                    <th style="width: 110px;">Meja / Tipe</th>
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
                            @if($order->table)
                                <span style="font-weight: 800; background: var(--accent-light); color: var(--primary); padding: 4px 10px; border-radius: 8px; font-size: 0.85rem; border: 1px solid rgba(212,163,115,0.3);">
                                    Meja {{ $order->table->table_number }}
                                </span>
                            @else
                                <span style="font-weight: 800; background: #f3e5f5; color: #7b1fa2; padding: 4px 10px; border-radius: 8px; font-size: 0.8rem; border: 1px solid #e1bee7;">
                                    Takeaway
                                </span>
                            @endif
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
                                        <button type="button" class="action-dropdown-item item-success" onclick="openCashPaymentModal({{ $order->id }}, '{{ $order->order_number }}', {{ $order->total_amount }}, '{{ addslashes($order->customer_name) }}')">
                                            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                            <span>Bayar Tunai di Kasir</span>
                                        </button>

                                        <form action="{{ route('cashier.orders.confirm-payment', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="action-dropdown-item">
                                                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                <span>Quick Confirm (LUNAS)</span>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Print Receipt Button -->
                                    <button type="button" class="action-dropdown-item item-primary" onclick="printReceipt({{ $order->id }})">
                                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                        <span>Cetak Struk / Nota</span>
                                    </button>

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

    <!-- CASH PAYMENT CALCULATOR MODAL FOR DASHBOARD -->
    <div class="pos-modal-overlay" id="dashboardCashModal">
        <div class="pos-modal-card" style="max-width: 460px;">
            <div class="pos-modal-header">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; background: var(--accent-light); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                        <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                    </div>
                    <div>
                        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-dark);">Bayar Tunai di Kasir</h3>
                        <div style="font-size: 0.78rem; color: var(--text-muted);" id="cashModalSubtitle">Order #000</div>
                    </div>
                </div>
                <button type="button" class="btn-close-modal" onclick="closeCashPaymentModal()">&times;</button>
            </div>

            <div class="pos-modal-body">
                <input type="hidden" id="cashModalOrderId">
                <input type="hidden" id="cashModalTotalAmount">

                <div style="background: linear-gradient(135deg, #3c2a21 0%, #1e140e 100%); border-radius: 12px; padding: 16px; color: #fff; text-align: center; margin-bottom: 16px;">
                    <div style="font-size: 0.75rem; color: rgba(255,255,255,0.7); text-transform: uppercase; font-weight: 700;">Total Tagihan</div>
                    <div style="font-size: 1.8rem; font-weight: 800; color: var(--accent);" id="cashModalTotalDisplay">Rp 0</div>
                </div>

                <div class="form-group" style="margin-bottom: 12px;">
                    <label style="font-weight: 700; font-size: 0.85rem; color: var(--text-dark); margin-bottom: 6px; display: block;">
                        Uang Tunai Diterima (Rp) <span style="color: var(--danger);">*</span>
                    </label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-weight: 800; color: var(--primary); font-size: 1.1rem;">Rp</span>
                        <input type="number" id="dashInputCashReceived" class="input-pos-cash" placeholder="0" oninput="calculateDashChange()" style="padding-left: 44px; font-size: 1.25rem; font-weight: 800; color: var(--primary); width: 100%;">
                    </div>
                </div>

                <!-- Quick Presets -->
                <div class="quick-cash-grid">
                    <button type="button" class="btn-quick-cash" onclick="setDashCashExact()">Uang Pas</button>
                    <button type="button" class="btn-quick-cash" onclick="setDashCashPreset(20000)">20.000</button>
                    <button type="button" class="btn-quick-cash" onclick="setDashCashPreset(50000)">50.000</button>
                    <button type="button" class="btn-quick-cash" onclick="setDashCashPreset(100000)">100.000</button>
                    <button type="button" class="btn-quick-cash" onclick="setDashCashPreset(200000)">200.000</button>
                    <button type="button" class="btn-quick-cash" onclick="setDashCashPreset(500000)">500.000</button>
                </div>

                <div class="change-calc-box" id="dashChangeCalcBox">
                    <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted);" id="dashChangeLabel">KEMBALIAN:</div>
                    <div class="change-val" id="dashChangeVal">Rp 0</div>
                </div>
            </div>

            <div class="pos-modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeCashPaymentModal()">Batal</button>
                <button type="button" class="btn btn-accent" id="btnSubmitDashCash" onclick="submitDashCashPayment()" style="font-weight: 800; min-width: 180px;">
                    <span>KONFIRMASI LUNAS</span>
                    <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<style>
@keyframes pulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(1.2); }
    100% { opacity: 1; transform: scale(1); }
}

/* Modal Styling */
.pos-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(18, 12, 8, 0.6);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.pos-modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.pos-modal-card {
    background: #ffffff;
    width: 92%;
    border-radius: 20px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    transform: translateY(20px) scale(0.96);
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.pos-modal-overlay.active .pos-modal-card {
    transform: translateY(0) scale(1);
}

.pos-modal-header {
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--border-color);
}

.btn-close-modal {
    background: none;
    border: none;
    font-size: 1.6rem;
    line-height: 1;
    color: var(--text-muted);
    cursor: pointer;
}

.pos-modal-body {
    padding: 20px;
}

.pos-modal-footer {
    padding: 14px 20px;
    background: #faf8f5;
    border-top: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
}

.input-pos-cash {
    border: 2px solid var(--border-color);
    border-radius: 10px;
    padding: 10px 14px;
    outline: none;
    transition: all 0.2s ease;
}

.input-pos-cash:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(212, 163, 115, 0.2);
}

.quick-cash-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    margin-bottom: 14px;
}

.btn-quick-cash {
    background: #ffffff;
    border: 1.5px solid var(--border-color);
    border-radius: 8px;
    padding: 8px 4px;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--text-dark);
    cursor: pointer;
    transition: all 0.15s ease;
}

.btn-quick-cash:hover {
    background: var(--accent-light);
    border-color: var(--accent);
    color: var(--primary);
}

.change-calc-box {
    background: var(--success-bg);
    border: 1.5px solid #c8e6c9;
    border-radius: 10px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.change-calc-box.underpaid {
    background: var(--danger-bg);
    border-color: #ffcdd2;
}

.change-val {
    font-size: 1.35rem;
    font-weight: 800;
    color: var(--success);
}

.change-calc-box.underpaid .change-val {
    color: var(--danger);
    font-size: 1.1rem;
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
            closeCashPaymentModal();
        }
    });

    // Base URL for cashier orders
    const cashierOrdersBaseUrl = "{{ url('cashier/orders') }}";

    // Print Receipt in popup
    function printReceipt(orderId) {
        const url = `${cashierOrdersBaseUrl}/${orderId}/receipt?print=1`;
        const printWin = window.open(url, '_blank', 'width=450,height=600,top=100,left=100');
        if (printWin) {
            printWin.focus();
        }
    }

    // Cash Payment Modal for Unpaid Orders
    let currentDashOrderId = null;
    let currentDashTotal = 0;

    function openCashPaymentModal(orderId, orderNumber, totalAmount, customerName) {
        currentDashOrderId = orderId;
        currentDashTotal = totalAmount;

        document.getElementById('cashModalOrderId').value = orderId;
        document.getElementById('cashModalTotalAmount').value = totalAmount;
        document.getElementById('cashModalSubtitle').innerText = `#${orderNumber} • ${customerName}`;
        document.getElementById('cashModalTotalDisplay').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(totalAmount)}`;
        document.getElementById('dashInputCashReceived').value = totalAmount;

        calculateDashChange();
        document.getElementById('dashboardCashModal').classList.add('active');
    }

    function closeCashPaymentModal() {
        document.getElementById('dashboardCashModal').classList.remove('active');
    }

    function setDashCashExact() {
        document.getElementById('dashInputCashReceived').value = currentDashTotal;
        calculateDashChange();
    }

    function setDashCashPreset(val) {
        document.getElementById('dashInputCashReceived').value = val;
        calculateDashChange();
    }

    function calculateDashChange() {
        const cash = parseFloat(document.getElementById('dashInputCashReceived').value) || 0;
        const box = document.getElementById('dashChangeCalcBox');
        const label = document.getElementById('dashChangeLabel');
        const valEl = document.getElementById('dashChangeVal');
        const btnSubmit = document.getElementById('btnSubmitDashCash');

        if (cash < currentDashTotal) {
            box.classList.add('underpaid');
            label.innerText = 'UANG KURANG:';
            valEl.innerText = `- Rp ${new Intl.NumberFormat('id-ID').format(currentDashTotal - cash)}`;
            btnSubmit.disabled = true;
        } else {
            box.classList.remove('underpaid');
            label.innerText = 'KEMBALIAN:';
            valEl.innerText = `Rp ${new Intl.NumberFormat('id-ID').format(cash - currentDashTotal)}`;
            btnSubmit.disabled = false;
        }
    }

    function submitDashCashPayment() {
        if (!currentDashOrderId) return;
        const cashReceived = parseFloat(document.getElementById('dashInputCashReceived').value) || 0;
        const btn = document.getElementById('btnSubmitDashCash');
        btn.disabled = true;
        btn.innerText = 'Memproses...';

        const tokenEl = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = tokenEl ? tokenEl.getAttribute('content') : '';

        fetch(`${cashierOrdersBaseUrl}/${currentDashOrderId}/confirm-cash`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                cash_received: cashReceived
            })
        })
        .then(async res => {
            const data = await res.json().catch(() => null);
            if (!res.ok) {
                const errMsg = (data && data.message) ? data.message : `Error (${res.status}): Gagal memproses konfirmasi pembayaran.`;
                throw new Error(errMsg);
            }
            return data;
        })
        .then(data => {
            if (data && data.success) {
                closeCashPaymentModal();
                alert(data.message);
                window.location.reload();
            } else {
                alert((data && data.message) || 'Terjadi kesalahan saat memproses pembayaran.');
                btn.disabled = false;
                btn.innerText = 'KONFIRMASI LUNAS';
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerText = 'KONFIRMASI LUNAS';
            alert(err.message || 'Gagal menghubungi server. Silakan coba kembali.');
        });
    }

    // Live poll refresh cashier table every 8 seconds (only if user is not interacting with modal or dropdown)
    setInterval(function() {
        const hasOpenDropdown = document.querySelector('.action-dropdown.open');
        const hasOpenModal = document.querySelector('.pos-modal-overlay.active');
        if (!hasOpenDropdown && !hasOpenModal) {
            fetch(window.location.href, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                // background auto reload
                window.location.reload();
            }).catch(err => {});
        }
    }, 8000);
</script>
@endpush


