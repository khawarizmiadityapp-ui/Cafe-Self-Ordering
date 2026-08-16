@extends('layouts.customer')

@section('title', 'Digital Menu - Meja ' . $table->table_number)

@section('content')
    <!-- Customer Header -->
    <header class="customer-header">
        <div class="brand-row">
            <div class="brand-logo">
                <div class="brand-icon">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path></svg>
                </div>
                <div>
                    <div class="brand-title">KAFE DIGITAL</div>
                    <div class="brand-subtitle">Self-Ordering System</div>
                </div>
            </div>
            <div class="table-pill">
                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"></rect><rect x="9" y="9" width="6" height="6"></rect></svg>
                <span>Meja {{ $table->table_number }}</span>
            </div>
        </div>
    </header>

    @if(session('error'))
        <div style="padding: 12px 16px;">
            <div class="alert alert-error">{{ session('error') }}</div>
        </div>
    @endif

    <!-- Category Tabs -->
    <div class="category-tabs">
        <button type="button" class="cat-tab active" onclick="filterCategory('all', this)">Semua</button>
        @foreach($categories as $category)
            <button type="button" class="cat-tab" onclick="filterCategory('cat-{{ $category->id }}', this)">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    <!-- Menu Items List -->
    <div class="menu-section">
        @foreach($categories as $category)
            <div class="category-group cat-{{ $category->id }}">
                <div class="section-title">
                    <span>{{ $category->name }}</span>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">{{ $category->products->count() }} Menu</span>
                </div>

                @foreach($category->products as $product)
                    <div class="product-card">
                        <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=300' }}" alt="{{ $product->name }}" class="product-img">
                        <div class="product-info">
                            <div>
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-desc">{{ $product->description }}</div>
                            </div>
                            <div class="product-bottom">
                                <div class="product-price">Rp{{ number_format($product->price, 0, ',', '.') }}</div>

                                @if($product->is_available)
                                    <div class="qty-control" id="control-{{ $product->id }}">
                                        <button type="button" class="btn-qty" onclick="changeQty({{ $product->id }}, -1)">-</button>
                                        <span class="qty-num" id="qty-val-{{ $product->id }}">0</span>
                                        <button type="button" class="btn-qty" onclick="changeQty({{ $product->id }}, 1)">+</button>
                                    </div>
                                @else
                                    <span class="badge badge-danger">TIDAK TERSEDIA</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <!-- Floating Cart Bar -->
    <div class="floating-cart-bar" id="floatingCart" style="display: none;" onclick="openCartDrawer()">
        <div class="cart-summary">
            <span class="cart-count" id="cartCountText">0 Item dipilih</span>
            <span class="cart-total" id="cartTotalText">Rp0</span>
        </div>
        <button type="button" class="btn btn-accent btn-sm" style="display: flex; align-items: center; gap: 6px;">
            <span>Lihat Keranjang</span>
            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
        </button>
    </div>

    <!-- Cart / Checkout Drawer -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeCartDrawer()"></div>
    <div class="cart-drawer" id="cartDrawer">
        <div class="drawer-header">
            <div class="drawer-title">Pesanan Meja {{ $table->table_number }}</div>
            <button type="button" class="btn-close" onclick="closeCartDrawer()">&times;</button>
        </div>

        <form action="{{ route('customer.order.checkout') }}" method="POST" id="checkoutForm" onsubmit="return validateCheckout()">
            @csrf
            <input type="hidden" name="table_id" value="{{ $table->id }}">
            <input type="hidden" name="cart_items" id="cartItemsInput">

            <!-- Read Only Table Info -->
            <div class="form-group">
                <label class="form-label">Nomor Meja (Otomatis dari QR Code)</label>
                <input type="text" class="form-control" value="Meja {{ $table->table_number }}" readonly>
            </div>

            <!-- Customer Name Input -->
            <div class="form-group">
                <label class="form-label">Nama Pelanggan <span style="color: var(--danger);">*</span></label>
                <input type="text" name="customer_name" id="customerName" class="form-control" placeholder="Masukkan nama Anda (misal: Rizmi)" required>
            </div>

            <!-- Itemized Cart Summary -->
            <div class="form-group">
                <label class="form-label">Daftar Menu & Catatan</label>
                <div id="cartItemsList" style="background: #faf8f5; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 12px; max-height: 220px; overflow-y: auto;">
                    <!-- Rendered by JavaScript -->
                </div>
            </div>

            <!-- Total Payment Summary -->
            <div style="background: var(--accent-light); padding: 14px 16px; border-radius: var(--radius-md); margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; border: 1px solid var(--accent);">
                <div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">TOTAL PEMBAYARAN</div>
                    <div style="font-size: 1.25rem; font-weight: 800; color: var(--primary);" id="drawerTotalText">Rp0</div>
                </div>
                <span class="badge badge-primary">Dihitung Otomatis</span>
            </div>

            <!-- Payment Method Selection -->
            <div class="form-group">
                <label class="form-label">Pilih Metode Pembayaran</label>
                <div class="payment-options">
                    <label class="payment-card active" id="payCardCash" onclick="selectPayment('cash')">
                        <input type="radio" name="payment_method" value="cash" checked>
                        <div class="payment-card-icon">
                            <svg class="svg-icon svg-icon-lg" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                        <div class="payment-card-title">CASH</div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">Bayar di Kasir</div>
                    </label>
                    <label class="payment-card" id="payCardQris" onclick="selectPayment('qris')">
                        <input type="radio" name="payment_method" value="qris">
                        <div class="payment-card-icon">
                            <svg class="svg-icon svg-icon-lg" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                        </div>
                        <div class="payment-card-title">QRIS</div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">Bayar Instant</div>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="padding: 14px; font-size: 1.05rem; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <span>Konfirmasi & Buat Pesanan</span>
                <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Product catalog data from PHP
    const productsData = @json($categories->flatMap->products->keyBy('id'));
    let cart = {};

    function filterCategory(catClass, element) {
        document.querySelectorAll('.cat-tab').forEach(el => el.classList.remove('active'));
        element.classList.add('active');

        if (catClass === 'all') {
            document.querySelectorAll('.category-group').forEach(el => el.style.display = 'block');
        } else {
            document.querySelectorAll('.category-group').forEach(el => el.style.display = 'none');
            const target = document.querySelector('.' + catClass);
            if (target) target.style.display = 'block';
        }
    }

    function changeQty(productId, delta) {
        if (!cart[productId]) {
            cart[productId] = { qty: 0, notes: '' };
        }

        cart[productId].qty += delta;

        if (cart[productId].qty <= 0) {
            delete cart[productId];
            document.getElementById(`qty-val-${productId}`).innerText = '0';
        } else {
            document.getElementById(`qty-val-${productId}`).innerText = cart[productId].qty;
        }

        updateCartUI();
    }

    function updateCartUI() {
        let totalCount = 0;
        let totalAmount = 0;

        for (const [id, item] of Object.entries(cart)) {
            const product = productsData[id];
            if (product) {
                totalCount += item.qty;
                totalAmount += (product.price * item.qty);
            }
        }

        const floatingCart = document.getElementById('floatingCart');
        if (totalCount > 0) {
            floatingCart.style.display = 'flex';
            document.getElementById('cartCountText').innerText = `${totalCount} Item`;
            document.getElementById('cartTotalText').innerText = formatRupiah(totalAmount);
            document.getElementById('drawerTotalText').innerText = formatRupiah(totalAmount);
        } else {
            floatingCart.style.display = 'none';
            closeCartDrawer();
        }
    }

    function formatRupiah(number) {
        return 'Rp' + number.toLocaleString('id-ID');
    }

    function openCartDrawer() {
        renderDrawerItems();
        document.getElementById('modalOverlay').classList.add('active');
        document.getElementById('cartDrawer').classList.add('active');
    }

    function closeCartDrawer() {
        document.getElementById('modalOverlay').classList.remove('active');
        document.getElementById('cartDrawer').classList.remove('active');
    }

    function renderDrawerItems() {
        const container = document.getElementById('cartItemsList');
        container.innerHTML = '';

        let itemsPayload = [];

        for (const [id, item] of Object.entries(cart)) {
            const product = productsData[id];
            if (!product) continue;

            const subtotal = product.price * item.qty;

            itemsPayload.push({
                id: product.id,
                name: product.name,
                qty: item.qty,
                price: product.price,
                notes: item.notes || ''
            });

            const itemRow = document.createElement('div');
            itemRow.style.cssText = 'padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px dashed var(--border-color);';
            itemRow.innerHTML = `
                <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 0.9rem;">
                    <span>${product.name} × ${item.qty}</span>
                    <span style="color: var(--primary);">${formatRupiah(subtotal)}</span>
                </div>
                <input type="text" placeholder="Catatan (misal: Less ice, tanpa gula)"
                       value="${item.notes || ''}"
                       onchange="updateItemNotes(${product.id}, this.value)"
                       style="width: 100%; margin-top: 6px; padding: 6px 10px; font-size: 0.8rem; border: 1px solid var(--border-color); border-radius: var(--radius-sm); outline: none;">
            `;
            container.appendChild(itemRow);
        }

        document.getElementById('cartItemsInput').value = JSON.stringify(itemsPayload);
    }

    function updateItemNotes(productId, notesText) {
        if (cart[productId]) {
            cart[productId].notes = notesText;
            renderDrawerItems();
        }
    }

    function selectPayment(method) {
        document.querySelectorAll('.payment-card').forEach(el => el.classList.remove('active'));
        if (method === 'cash') {
            document.getElementById('payCardCash').classList.add('active');
            document.querySelector('input[value="cash"]').checked = true;
        } else {
            document.getElementById('payCardQris').classList.add('active');
            document.querySelector('input[value="qris"]').checked = true;
        }
    }

    function validateCheckout() {
        const customerName = document.getElementById('customerName').value.trim();
        if (!customerName) {
            alert('Silakan masukkan nama Anda sebelum memasang order.');
            return false;
        }
        if (Object.keys(cart).length === 0) {
            alert('Keranjang belanja Anda kosong.');
            return false;
        }
        renderDrawerItems(); // Ensure JSON payload is updated
        return true;
    }
</script>
@endpush
