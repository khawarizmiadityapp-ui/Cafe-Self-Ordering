@extends('layouts.staff')

@section('title', 'Point of Sale (POS) Kasir - Cafe Self-Ordering System')

@section('content')
<div class="pos-wrapper">
    <!-- Left Column: Menu Catalog & Categories -->
    <div class="pos-menu-section">
        <!-- POS Top Header & Search Bar -->
        <div class="pos-header">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                <div>
                    <h1 style="font-size: 1.45rem; font-weight: 800; color: var(--text-dark); display: flex; align-items: center; gap: 10px;">
                        <svg class="svg-icon svg-icon-md" style="color: var(--accent-dark);" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        <span>Kasir POS Terminal</span>
                    </h1>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Pilih menu untuk membuat pesanan & pembayaran langsung</p>
                </div>

                <!-- Search Input Box -->
                <div class="pos-search-box">
                    <svg class="svg-icon svg-icon-sm search-icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="posSearchInput" placeholder="Cari nama menu / kopi..." onkeyup="filterProducts()">
                    <button type="button" id="posClearSearch" onclick="clearSearch()" style="display: none; background: none; border: none; cursor: pointer; color: var(--text-muted); padding: 4px;">
                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
            </div>

            <!-- Category Filter Pills -->
            <div class="pos-categories-bar">
                <button type="button" class="pos-cat-pill active" data-cat="all" onclick="selectCategory('all', this)">
                    <span>Semua Menu</span>
                    <span class="cat-count">{{ $products->count() }}</span>
                </button>
                @foreach($categories as $category)
                    <button type="button" class="pos-cat-pill" data-cat="{{ $category->id }}" onclick="selectCategory('{{ $category->id }}', this)">
                        <span>{{ $category->name }}</span>
                        <span class="cat-count">{{ $category->products->count() }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Product Grid -->
        <div class="pos-product-grid" id="posProductGrid">
            @forelse($products as $product)
                <div class="pos-product-card" 
                     data-id="{{ $product->id }}" 
                     data-name="{{ $product->name }}" 
                     data-price="{{ $product->price }}" 
                     data-category="{{ $product->category_id }}"
                     onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})">
                    
                    <div class="pos-card-img-wrap">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="pos-card-img" onerror="this.onerror=null; this.src='{{ asset('images/coffee-default.svg') }}'">
                        <span class="pos-badge-cat">{{ $product->category->name ?? 'Menu' }}</span>
                        <div class="pos-in-cart-badge" id="badge-qty-{{ $product->id }}" style="display: none;">0</div>
                    </div>

                    <div class="pos-card-body">
                        <div class="pos-card-title">{{ $product->name }}</div>
                        <div class="pos-card-price">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">
                    Belum ada menu produk aktif.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Right Column: Order Terminal & Cart -->
    <div class="pos-ticket-section">
        <div class="pos-ticket-card">
            <div class="pos-ticket-header">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="font-weight: 800; font-size: 1.1rem; color: var(--text-dark); display: flex; align-items: center; gap: 8px;">
                        <svg class="svg-icon svg-icon-md" style="color: var(--primary);" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                        <span>Tagihan Pesanan</span>
                    </div>
                    <button type="button" class="btn-clear-cart" onclick="clearCart()" title="Kosongkan Keranjang">
                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        <span>Reset</span>
                    </button>
                </div>

                <!-- Customer Details Form -->
                <div class="pos-customer-form">
                    <div class="form-group-compact">
                        <label for="posCustomerName">Nama Pelanggan <span style="color: var(--danger);">*</span></label>
                        <input type="text" id="posCustomerName" class="input-pos" placeholder="Misal: Kak Rizmi" value="Pelanggan Kasir">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 8px;">
                        <div class="form-group-compact">
                            <label>Tipe Order</label>
                            <div class="pos-type-toggle">
                                <button type="button" id="btnTypeDineIn" class="type-btn active" onclick="setOrderType('dine_in')">
                                    Dine In
                                </button>
                                <button type="button" id="btnTypeTakeaway" class="type-btn" onclick="setOrderType('takeaway')">
                                    Takeaway
                                </button>
                            </div>
                        </div>

                        <div class="form-group-compact" id="tableSelectWrapper">
                            <label for="posTableId">Pilih Meja <span style="color: var(--danger);">*</span></label>
                            <select id="posTableId" class="input-pos">
                                @foreach($tables as $table)
                                    <option value="{{ $table->id }}" {{ $loop->first ? 'selected' : '' }}>
                                        Meja {{ $table->table_number }} {{ $table->name ? '(' . $table->name . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Items Scroll List -->
            <div class="pos-ticket-items" id="posCartItemsList">
                <div class="pos-empty-cart" id="posEmptyCartNotice">
                    <div style="width: 50px; height: 50px; background: var(--accent-light); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px auto; color: var(--primary);">
                        <svg class="svg-icon svg-icon-lg" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    </div>
                    <div style="font-weight: 700; color: var(--text-dark); font-size: 0.95rem;">Keranjang Masih Kosong</div>
                    <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 4px;">Klik menu di sebelah kiri untuk menambahkan pesanan</div>
                </div>
            </div>

            <!-- Ticket Footer Summary & Checkout Button -->
            <div class="pos-ticket-footer">
                <div class="pos-summary-row">
                    <span>Total Item:</span>
                    <strong id="posSummaryTotalQty">0 item</strong>
                </div>
                <div class="pos-summary-row pos-total-highlight">
                    <span>TOTAL BAYAR:</span>
                    <strong id="posSummaryTotalAmount">Rp 0</strong>
                </div>

                <button type="button" class="btn btn-accent btn-block btn-pay-now" id="btnOpenPaymentModal" onclick="openPaymentModal()" disabled>
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    <span>BAYAR & PROSES SEKARANG</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SMART PAYMENT MODAL (Cash Calculator, QRIS, Debit) -->
<div class="pos-modal-overlay" id="paymentModal">
    <div class="pos-modal-card">
        <div class="pos-modal-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; background: var(--accent-light); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-dark);">Pembayaran Kasir</h3>
                    <div style="font-size: 0.78rem; color: var(--text-muted);">Selesaikan transaksi pelanggan</div>
                </div>
            </div>
            <button type="button" class="btn-close-modal" onclick="closePaymentModal()">&times;</button>
        </div>

        <div class="pos-modal-body">
            <!-- Total Tagihan Banner -->
            <div class="payment-bill-banner">
                <div style="font-size: 0.8rem; font-weight: 700; color: rgba(255,255,255,0.75); text-transform: uppercase; letter-spacing: 0.5px;">Total Yang Harus Dibayar</div>
                <div class="bill-amount" id="modalBillAmountDisplay">Rp 0</div>
                <div style="font-size: 0.78rem; color: rgba(255,255,255,0.9); margin-top: 4px;" id="modalOrderSummaryText">
                    0 Item • Meja 01
                </div>
            </div>

            <!-- Payment Method Tabs -->
            <div class="payment-tabs-bar">
                <button type="button" class="pay-tab-btn active" id="tabCash" onclick="selectPaymentMethod('cash')">
                    <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>
                    <span>Tunai (Cash)</span>
                </button>
                <button type="button" class="pay-tab-btn" id="tabQris" onclick="selectPaymentMethod('qris')">
                    <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>QRIS</span>
                </button>
            </div>

            <!-- CASH PAYMENT SECTION -->
            <div id="sectionCashPayment" class="pay-section-content">
                <div class="form-group" style="margin-bottom: 12px;">
                    <label style="font-weight: 700; font-size: 0.85rem; color: var(--text-dark); margin-bottom: 6px; display: block;">
                        Uang Diterima (Rp) <span style="color: var(--danger);">*</span>
                    </label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-weight: 800; color: var(--primary); font-size: 1.1rem;">Rp</span>
                        <input type="number" id="inputCashReceived" class="input-pos-cash" placeholder="0" oninput="calculateChange()" style="padding-left: 44px; font-size: 1.25rem; font-weight: 800; color: var(--primary); width: 100%;">
                    </div>
                </div>

                <!-- Quick Cash Presets -->
                <div class="quick-cash-grid">
                    <button type="button" class="btn-quick-cash" onclick="setCashExact()">Uang Pas</button>
                    <button type="button" class="btn-quick-cash" onclick="setCashPreset(10000)">10.000</button>
                    <button type="button" class="btn-quick-cash" onclick="setCashPreset(20000)">20.000</button>
                    <button type="button" class="btn-quick-cash" onclick="setCashPreset(50000)">50.000</button>
                    <button type="button" class="btn-quick-cash" onclick="setCashPreset(100000)">100.000</button>
                    <button type="button" class="btn-quick-cash" onclick="setCashPreset(200000)">200.000</button>
                </div>

                <!-- Kembalian Live Display -->
                <div class="change-calc-box" id="changeCalcBox">
                    <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted);" id="changeCalcLabel">KEMBALIAN:</div>
                    <div class="change-val" id="changeCalcAmount">Rp 0</div>
                </div>
            </div>

            <!-- QRIS PAYMENT SECTION -->
            <div id="sectionQrisPayment" class="pay-section-content" style="display: none; text-align: center; padding: 10px 0;">
                <div style="background: #faf8f5; border: 1.5px dashed var(--accent); border-radius: 14px; padding: 18px; display: inline-block; margin-bottom: 10px;">
                    <div style="width: 140px; height: 140px; margin: 0 auto; background: #fff; padding: 8px; border-radius: 8px; box-shadow: var(--shadow-xs); display: flex; align-items: center; justify-content: center;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=CAFE-POS-QRIS-DIRECT" alt="QRIS Code" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <div style="font-weight: 800; font-size: 0.85rem; color: var(--primary); margin-top: 8px;">QRIS STANDAR NASIONAL</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Pelanggan scan barcode di atas</div>
                </div>
                <div style="font-size: 0.8rem; color: var(--success); font-weight: 600;">
                    ✓ Pembayaran QRIS akan otomatis divalidasi LUNAS
                </div>
            </div>

            <!-- Workflow Option: Send to Kitchen or Direct Complete -->
            <div style="margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
                <label style="font-size: 0.825rem; font-weight: 700; color: var(--text-dark);">Tindakan Pesanan:</label>
                <div style="display: flex; gap: 12px;">
                    <label style="font-size: 0.8rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 4px;">
                        <input type="radio" name="order_action_radio" value="send_kitchen" checked>
                        <span>Kirim ke Dapur</span>
                    </label>
                    <label style="font-size: 0.8rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 4px;">
                        <input type="radio" name="order_action_radio" value="direct_complete">
                        <span>Langsung Selesai</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="pos-modal-footer">
            <button type="button" class="btn btn-outline" onclick="closePaymentModal()" style="border-color: var(--border-color); font-weight: 600;">
                Batal
            </button>
            <button type="button" class="btn btn-accent" id="btnSubmitPayment" onclick="submitPosPayment()" style="font-weight: 800; padding: 12px 24px; min-width: 180px;">
                <span>KONFIRMASI BAYAR</span>
                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </button>
        </div>
    </div>
</div>

<!-- ITEM NOTES MODAL -->
<div class="pos-modal-overlay" id="notesModal">
    <div class="pos-modal-card" style="max-width: 420px;">
        <div class="pos-modal-header">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-dark);" id="notesModalItemTitle">Catatan Khusus Menu</h3>
            <button type="button" class="btn-close-modal" onclick="closeNotesModal()">&times;</button>
        </div>
        <div class="pos-modal-body">
            <input type="hidden" id="editingNotesItemId">
            <div class="form-group">
                <label style="font-size: 0.825rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; display: block;">
                    Tambahkan request khusus (misal: "Less ice, no sugar, pedas sedang"):
                </label>
                <textarea id="itemNotesInput" class="input-pos" rows="3" placeholder="Tulis catatan di sini..." style="width: 100%;"></textarea>
            </div>
        </div>
        <div class="pos-modal-footer">
            <button type="button" class="btn btn-outline btn-sm" onclick="closeNotesModal()">Batal</button>
            <button type="button" class="btn btn-accent btn-sm" onclick="saveItemNotes()">Simpan Catatan</button>
        </div>
    </div>
</div>

<!-- SUCCESS & THERMAL RECEIPT MODAL -->
<div class="pos-modal-overlay" id="receiptModal">
    <div class="pos-modal-card" style="max-width: 420px; text-align: center;">
        <div class="pos-modal-body" style="padding: 24px;">
            <div style="width: 60px; height: 60px; background: var(--success-bg); color: var(--success); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px auto; box-shadow: 0 4px 14px rgba(46, 125, 50, 0.25);">
                <svg class="svg-icon svg-icon-lg" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--text-dark);">Pembayaran Berhasil!</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;" id="receiptSuccessSubtitle">
                Pesanan telah tercatat dan pembayaran LUNAS.
            </p>

            <div class="receipt-preview-box" id="receiptPreviewContainer">
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 4px;">
                    <span style="color: var(--text-muted);">No. Order:</span>
                    <strong id="receiptOrderNumber">#ORD-000</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 4px;">
                    <span style="color: var(--text-muted);">Total Bayar:</span>
                    <strong id="receiptTotalAmount" style="color: var(--primary);">Rp 0</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 4px;" id="receiptChangeRow">
                    <span style="color: var(--text-muted);">Kembalian:</span>
                    <strong id="receiptChangeAmount" style="color: var(--success);">Rp 0</strong>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-accent btn-block" onclick="printReceiptDirectly()" style="font-weight: 800; padding: 12px; font-size: 0.95rem;">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    <span>CETAK STRUK PEMBAYARAN</span>
                </button>
                <button type="button" class="btn btn-outline btn-block" onclick="resetPosForNewOrder()" style="font-weight: 700;">
                    <span>TRANSAKSI BARU (+NEXT)</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* POS Layout Architecture */
.pos-wrapper {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 24px;
    align-items: flex-start;
}

@media (max-width: 1024px) {
    .pos-wrapper {
        grid-template-columns: 1fr;
    }
}

.pos-menu-section {
    background: #ffffff;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-color);
    padding: 20px;
    box-shadow: var(--shadow-sm);
}

.pos-header {
    margin-bottom: 18px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border-color);
}

.pos-search-box {
    position: relative;
    display: flex;
    align-items: center;
    background: var(--bg-main);
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius-full);
    padding: 2px 14px;
    width: 280px;
    transition: all 0.2s ease;
}

.pos-search-box:focus-within {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(212, 163, 115, 0.2);
    background: #ffffff;
}

.pos-search-box input {
    width: 100%;
    border: none;
    background: none;
    padding: 8px 6px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-dark);
    outline: none;
}

.pos-search-box .search-icon {
    color: var(--text-muted);
}

.pos-categories-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    overflow-x: auto;
    padding: 12px 0 4px 0;
    scrollbar-width: thin;
}

.pos-cat-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: var(--radius-full);
    font-size: 0.8rem;
    font-weight: 700;
    background: #f7f3ee;
    color: var(--text-muted);
    border: 1.5px solid var(--border-color);
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s ease;
}

.pos-cat-pill:hover {
    background: var(--accent-light);
    color: var(--primary);
    border-color: var(--accent);
}

.pos-cat-pill.active {
    background: var(--primary);
    color: #ffffff;
    border-color: var(--primary);
    box-shadow: 0 4px 12px rgba(60, 42, 33, 0.25);
}

.cat-count {
    background: rgba(0, 0, 0, 0.08);
    padding: 1px 6px;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
}

.pos-cat-pill.active .cat-count {
    background: rgba(255, 255, 255, 0.25);
    color: #fff;
}

/* Product Grid */
.pos-product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
    gap: 16px;
    max-height: calc(100vh - 270px);
    overflow-y: auto;
    padding-right: 4px;
}

.pos-product-card {
    background: #ffffff;
    border: 1.5px solid var(--border-color);
    border-radius: 14px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
    position: relative;
    user-select: none;
}

.pos-product-card:hover {
    transform: translateY(-3px);
    border-color: var(--accent);
    box-shadow: 0 8px 20px rgba(60, 42, 33, 0.1);
}

.pos-product-card:active {
    transform: scale(0.98);
}

.pos-card-img-wrap {
    position: relative;
    width: 100%;
    height: 110px;
    background: var(--accent-light);
}

.pos-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pos-card-img-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    opacity: 0.6;
}

.pos-badge-cat {
    position: absolute;
    top: 6px;
    left: 6px;
    background: rgba(30, 20, 14, 0.75);
    backdrop-filter: blur(4px);
    color: #ffffff;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
    text-transform: uppercase;
}

.pos-in-cart-badge {
    position: absolute;
    top: 6px;
    right: 6px;
    background: var(--primary);
    color: #fff;
    font-weight: 800;
    font-size: 0.75rem;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.25);
}

.pos-card-body {
    padding: 10px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.pos-card-title {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--text-dark);
    line-height: 1.3;
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.pos-card-price {
    font-size: 0.95rem;
    font-weight: 800;
    color: var(--primary);
}

/* Right Section: Ticket / Cart */
.pos-ticket-section {
    position: sticky;
    top: 20px;
}

.pos-ticket-card {
    background: #ffffff;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 120px);
}

.pos-ticket-header {
    padding: 16px;
    border-bottom: 1px solid var(--border-color);
}

.btn-clear-cart {
    background: none;
    border: none;
    color: var(--danger);
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 4px;
}

.btn-clear-cart:hover {
    text-decoration: underline;
}

.pos-customer-form {
    margin-top: 12px;
}

.form-group-compact label {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--text-muted);
    margin-bottom: 3px;
    display: block;
}

.input-pos {
    width: 100%;
    padding: 7px 10px;
    border-radius: 8px;
    border: 1.5px solid var(--border-color);
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-dark);
    background: #faf8f5;
    outline: none;
    transition: all 0.2s ease;
}

.input-pos:focus {
    border-color: var(--accent);
    background: #ffffff;
}

.pos-type-toggle {
    display: flex;
    background: #faf8f5;
    border: 1.5px solid var(--border-color);
    border-radius: 8px;
    padding: 2px;
}

.type-btn {
    flex: 1;
    border: none;
    background: none;
    padding: 5px 8px;
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--text-muted);
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s ease;
}

.type-btn.active {
    background: var(--primary);
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(60, 42, 33, 0.2);
}

/* Cart Items List */
.pos-ticket-items {
    flex: 1;
    overflow-y: auto;
    padding: 12px 16px;
    min-height: 180px;
    max-height: 320px;
}

.pos-empty-cart {
    text-align: center;
    padding: 30px 10px;
}

.pos-cart-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px dashed var(--border-color);
}

.pos-cart-row:last-child {
    border-bottom: none;
}

.pos-item-info {
    flex: 1;
    padding-right: 10px;
}

.pos-item-name {
    font-weight: 700;
    font-size: 0.85rem;
    color: var(--text-dark);
    line-height: 1.2;
}

.pos-item-price {
    font-size: 0.78rem;
    color: var(--text-muted);
    margin-top: 2px;
}

.pos-item-note-text {
    font-size: 0.72rem;
    color: var(--danger);
    background: var(--danger-bg);
    padding: 1px 6px;
    border-radius: 4px;
    display: inline-block;
    margin-top: 3px;
    font-style: italic;
}

.pos-item-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-qty-mini {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    border: 1px solid var(--border-color);
    background: #fff;
    font-weight: 800;
    font-size: 0.9rem;
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
}

.btn-qty-mini:hover {
    background: var(--accent-light);
    border-color: var(--accent);
}

.cart-qty-display {
    font-weight: 800;
    font-size: 0.85rem;
    min-width: 18px;
    text-align: center;
}

.btn-note-mini {
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 3px;
}

.btn-note-mini:hover, .btn-note-mini.has-note {
    color: var(--accent-dark);
}

/* Footer Summary */
.pos-ticket-footer {
    padding: 16px;
    background: #faf8f5;
    border-top: 1px solid var(--border-color);
    border-radius: 0 0 var(--radius-md) var(--radius-md);
}

.pos-summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    margin-bottom: 6px;
    color: var(--text-muted);
}

.pos-total-highlight {
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--primary);
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1.5px dashed var(--border-color);
}

.btn-pay-now {
    margin-top: 12px;
    padding: 14px;
    font-size: 1rem;
    font-weight: 800;
    letter-spacing: 0.5px;
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
    max-width: 500px;
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

.btn-close-modal:hover {
    color: var(--danger);
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

/* Payment Modal Specific */
.payment-bill-banner {
    background: linear-gradient(135deg, #3c2a21 0%, #1e140e 100%);
    border-radius: 14px;
    padding: 18px;
    color: #ffffff;
    text-align: center;
    box-shadow: 0 4px 14px rgba(60, 42, 33, 0.3);
    margin-bottom: 16px;
}

.bill-amount {
    font-size: 2rem;
    font-weight: 800;
    color: var(--accent);
    letter-spacing: -0.5px;
    margin-top: 2px;
}

.payment-tabs-bar {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 16px;
}

.pay-tab-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 6px;
    background: #f7f3ee;
    border: 1.5px solid var(--border-color);
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.78rem;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.2s ease;
}

.pay-tab-btn:hover {
    border-color: var(--accent);
    color: var(--primary);
}

.pay-tab-btn.active {
    background: var(--primary);
    border-color: var(--primary);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(60, 42, 33, 0.2);
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

.receipt-preview-box {
    background: #fdfaf6;
    border: 1.5px dashed var(--border-color);
    border-radius: 12px;
    padding: 16px;
    margin-top: 14px;
}
</style>

@endsection

@push('scripts')
<script>
    // In-memory POS Cart State
    let cart = [];
    let selectedOrderType = 'dine_in';
    let currentCategory = 'all';
    let selectedPaymentMethod = 'cash';
    let lastCreatedOrderId = null;

    // Add product to cart
    function addToCart(id, name, price) {
        id = parseInt(id);
        price = parseFloat(price);
        const existingIndex = cart.findIndex(item => item.id === id);
        if (existingIndex > -1) {
            cart[existingIndex].qty += 1;
        } else {
            cart.push({
                id: id,
                name: name,
                price: price,
                qty: 1,
                notes: ''
            });
        }
        renderCart();
    }

    // Change quantity
    function changeQty(id, delta) {
        id = parseInt(id);
        const index = cart.findIndex(item => item.id === id);
        if (index > -1) {
            cart[index].qty += delta;
            if (cart[index].qty <= 0) {
                cart.splice(index, 1);
            }
        }
        renderCart();
    }

    // Clear entire cart
    function clearCart() {
        if (cart.length === 0) return;
        if (confirm('Kosongkan semua item dalam keranjang?')) {
            cart = [];
            renderCart();
        }
    }

    // Toggle Order Type (Dine-in / Takeaway)
    function setOrderType(type) {
        selectedOrderType = type;
        const btnDineIn = document.getElementById('btnTypeDineIn');
        const btnTakeaway = document.getElementById('btnTypeTakeaway');
        const tableWrapper = document.getElementById('tableSelectWrapper');

        if (type === 'dine_in') {
            btnDineIn.classList.add('active');
            btnTakeaway.classList.remove('active');
            tableWrapper.style.display = 'block';
        } else {
            btnTakeaway.classList.add('active');
            btnDineIn.classList.remove('active');
            tableWrapper.style.display = 'none';
        }
    }

    // Open Notes Modal
    function openNotesModal(id) {
        id = parseInt(id);
        const item = cart.find(i => i.id === id);
        if (!item) return;

        document.getElementById('editingNotesItemId').value = id;
        document.getElementById('notesModalItemTitle').innerText = 'Catatan: ' + item.name;
        document.getElementById('itemNotesInput').value = item.notes || '';
        document.getElementById('notesModal').classList.add('active');
    }

    function closeNotesModal() {
        document.getElementById('notesModal').classList.remove('active');
    }

    function saveItemNotes() {
        const id = parseInt(document.getElementById('editingNotesItemId').value);
        const notes = document.getElementById('itemNotesInput').value.trim();
        const item = cart.find(i => i.id === id);
        if (item) {
            item.notes = notes;
        }
        closeNotesModal();
        renderCart();
    }

    // Render cart items to DOM
    function renderCart() {
        const container = document.getElementById('posCartItemsList');
        const btnPay = document.getElementById('btnOpenPaymentModal');
        const totalQtyEl = document.getElementById('posSummaryTotalQty');
        const totalAmountEl = document.getElementById('posSummaryTotalAmount');

        // Reset badge counters on product cards
        document.querySelectorAll('.pos-in-cart-badge').forEach(el => {
            el.style.display = 'none';
            el.innerText = '0';
        });

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="pos-empty-cart" id="posEmptyCartNotice">
                    <div style="width: 50px; height: 50px; background: var(--accent-light); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px auto; color: var(--primary);">
                        <svg class="svg-icon svg-icon-lg" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    </div>
                    <div style="font-weight: 700; color: var(--text-dark); font-size: 0.95rem;">Keranjang Masih Kosong</div>
                    <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 4px;">Klik menu di sebelah kiri untuk menambahkan pesanan</div>
                </div>
            `;
            btnPay.disabled = true;
            totalQtyEl.innerText = '0 item';
            totalAmountEl.innerText = 'Rp 0';
            return;
        }

        btnPay.disabled = false;

        let totalQty = 0;
        let totalAmount = 0;
        let html = '';

        cart.forEach(item => {
            totalQty += item.qty;
            const subtotal = item.price * item.qty;
            totalAmount += subtotal;

            // Update badge on product card if visible
            const badge = document.getElementById('badge-qty-' + item.id);
            if (badge) {
                badge.innerText = item.qty;
                badge.style.display = 'flex';
            }

            html += `
                <div class="pos-cart-row">
                    <div class="pos-item-info">
                        <div class="pos-item-name">${escapeHtml(item.name)}</div>
                        <div class="pos-item-price">Rp${numberFormat(item.price)} x ${item.qty} = <strong>Rp${numberFormat(subtotal)}</strong></div>
                        ${item.notes ? `<div class="pos-item-note-text">"${escapeHtml(item.notes)}"</div>` : ''}
                    </div>
                    <div class="pos-item-actions">
                        <button type="button" class="btn-note-mini ${item.notes ? 'has-note' : ''}" onclick="openNotesModal(${item.id})" title="Tambah catatan">
                            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>
                        <button type="button" class="btn-qty-mini" onclick="changeQty(${item.id}, -1)" title="Kurangi">-</button>
                        <span class="cart-qty-display">${item.qty}</span>
                        <button type="button" class="btn-qty-mini" onclick="changeQty(${item.id}, 1)" title="Tambah">+</button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        totalQtyEl.innerText = `${totalQty} item`;
        totalAmountEl.innerText = `Rp ${numberFormat(totalAmount)}`;
    }

    // Category filter
    function selectCategory(catId, btnEl) {
        currentCategory = catId;
        document.querySelectorAll('.pos-cat-pill').forEach(b => b.classList.remove('active'));
        btnEl.classList.add('active');
        filterProducts();
    }

    // Filter products by category & search query
    function filterProducts() {
        const query = document.getElementById('posSearchInput').value.toLowerCase().trim();
        const clearBtn = document.getElementById('posClearSearch');
        if (query) {
            clearBtn.style.display = 'inline-block';
        } else {
            clearBtn.style.display = 'none';
        }

        const cards = document.querySelectorAll('.pos-product-card');
        cards.forEach(card => {
            const cat = card.getAttribute('data-category');
            const name = card.getAttribute('data-name').toLowerCase();

            const matchCat = (currentCategory === 'all' || cat === currentCategory);
            const matchQuery = (!query || name.includes(query));

            if (matchCat && matchQuery) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function clearSearch() {
        document.getElementById('posSearchInput').value = '';
        filterProducts();
    }

    // Calculate Cart Total
    function getCartTotal() {
        return cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    }

    // Payment Modal Functions
    function openPaymentModal() {
        if (cart.length === 0) return;

        const customerName = document.getElementById('posCustomerName').value.trim();
        if (!customerName) {
            alert('Mohon masukkan Nama Pelanggan.');
            document.getElementById('posCustomerName').focus();
            return;
        }

        const totalAmount = getCartTotal();
        const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);

        let tableText = 'Bungkus (Takeaway)';
        if (selectedOrderType === 'dine_in') {
            const tableSelect = document.getElementById('posTableId');
            tableText = tableSelect.options[tableSelect.selectedIndex].text;
        }

        document.getElementById('modalBillAmountDisplay').innerText = `Rp ${numberFormat(totalAmount)}`;
        document.getElementById('modalOrderSummaryText').innerText = `${customerName} • ${totalQty} Item • ${tableText}`;

        // Reset payment input to exact total
        document.getElementById('inputCashReceived').value = totalAmount;
        selectPaymentMethod('cash');
        calculateChange();

        document.getElementById('paymentModal').classList.add('active');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.remove('active');
    }

    function selectPaymentMethod(method) {
        selectedPaymentMethod = method;
        document.getElementById('tabCash').classList.toggle('active', method === 'cash');
        document.getElementById('tabQris').classList.toggle('active', method === 'qris');

        document.getElementById('sectionCashPayment').style.display = (method === 'cash') ? 'block' : 'none';
        document.getElementById('sectionQrisPayment').style.display = (method === 'qris') ? 'block' : 'none';

        if (method === 'cash') {
            calculateChange();
        }
    }

    function setCashExact() {
        document.getElementById('inputCashReceived').value = getCartTotal();
        calculateChange();
    }

    function setCashPreset(val) {
        document.getElementById('inputCashReceived').value = val;
        calculateChange();
    }

    function calculateChange() {
        const total = getCartTotal();
        const cash = parseFloat(document.getElementById('inputCashReceived').value) || 0;
        const box = document.getElementById('changeCalcBox');
        const label = document.getElementById('changeCalcLabel');
        const valEl = document.getElementById('changeCalcAmount');
        const btnSubmit = document.getElementById('btnSubmitPayment');

        if (selectedPaymentMethod !== 'cash') {
            btnSubmit.disabled = false;
            return;
        }

        if (cash < total) {
            box.classList.add('underpaid');
            label.innerText = 'UANG KURANG:';
            valEl.innerText = `- Rp ${numberFormat(total - cash)}`;
            btnSubmit.disabled = true;
        } else {
            box.classList.remove('underpaid');
            label.innerText = 'KEMBALIAN:';
            valEl.innerText = `Rp ${numberFormat(cash - total)}`;
            btnSubmit.disabled = false;
        }
    }

    // Submit Checkout via AJAX
    function submitPosPayment() {
        const btnSubmit = document.getElementById('btnSubmitPayment');
        btnSubmit.disabled = true;
        btnSubmit.innerText = 'Memproses...';

        const customerName = document.getElementById('posCustomerName').value.trim();
        const tableId = (selectedOrderType === 'dine_in') ? document.getElementById('posTableId').value : null;
        const cashReceived = (selectedPaymentMethod === 'cash') 
            ? parseFloat(document.getElementById('inputCashReceived').value) 
            : getCartTotal();
        const orderAction = document.querySelector('input[name="order_action_radio"]:checked').value;

        const payload = {
            customer_name: customerName,
            order_type: selectedOrderType,
            table_id: tableId,
            cart_items: cart,
            payment_method: selectedPaymentMethod,
            cash_received: cashReceived,
            order_action: orderAction
        };

        fetch('{{ route("cashier.pos.checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = `<span>KONFIRMASI BAYAR</span><svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>`;

            if (data.success) {
                closePaymentModal();
                showReceiptSuccess(data.order, data.receipt_url);
            } else {
                alert(data.message || 'Terjadi kesalahan saat memproses pesanan.');
            }
        })
        .catch(err => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = `<span>KONFIRMASI BAYAR</span><svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
            alert('Gagal menghubungi server. Silakan coba kembali.');
        });
    }

    // Show Receipt Success Modal
    function showReceiptSuccess(order, receiptUrl) {
        lastCreatedOrderId = order.id;
        document.getElementById('receiptOrderNumber').innerText = '#' + order.order_number;
        document.getElementById('receiptTotalAmount').innerText = 'Rp ' + numberFormat(order.total_amount);

        const changeRow = document.getElementById('receiptChangeRow');
        if (order.payment && order.payment.payload && order.payment.payload.cash_change > 0) {
            changeRow.style.display = 'flex';
            document.getElementById('receiptChangeAmount').innerText = 'Rp ' + numberFormat(order.payment.payload.cash_change);
        } else {
            changeRow.style.display = 'none';
        }

        document.getElementById('receiptModal').classList.add('active');
    }

    // Print Receipt via Popup Window
    function printReceiptDirectly() {
        if (!lastCreatedOrderId) return;
        const url = `/cashier/orders/${lastCreatedOrderId}/receipt?print=1`;
        const printWindow = window.open(url, '_blank', 'width=450,height=600,top=100,left=100');
        if (printWindow) {
            printWindow.focus();
        }
    }

    // Reset POS for next customer
    function resetPosForNewOrder() {
        document.getElementById('receiptModal').classList.remove('active');
        cart = [];
        document.getElementById('posCustomerName').value = 'Pelanggan Kasir';
        renderCart();
    }

    // Utility Helpers
    function numberFormat(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }
</script>
@endpush
