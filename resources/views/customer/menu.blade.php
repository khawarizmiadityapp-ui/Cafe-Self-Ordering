@extends('layouts.customer')

@section('title', 'Menu Meja ' . $table->table_number . ' - Meja Kopi')

@section('content')
    <!-- Sticky Top Header & Filter Navigation Bar -->
    <div class="sticky-top-header" style="position: sticky; top: 0; z-index: 999; background: var(--bg-main, #faf7f2); box-shadow: 0 4px 18px rgba(30, 20, 14, 0.12);">
        <!-- Top Header -->
        <div class="customer-header" style="background: linear-gradient(135deg, #1e140e 0%, #3c2a21 100%); color: #ffffff; padding: 18px 16px 16px 16px; border-radius: 0 0 24px 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 44px; height: 44px; border-radius: 14px; background: rgba(212, 163, 115, 0.15); border: 1px solid rgba(212, 163, 115, 0.3); display: flex; align-items: center; justify-content: center; shrink: 0;">
                        <svg style="width: 24px; height: 24px; min-width: 24px; min-height: 24px; color: var(--accent); stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;" viewBox="0 0 24 24">
                            <path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="brand-title" style="font-size: 1.25rem; font-weight: 900; color: #ffffff; margin: 0; line-height: 1.1; letter-spacing: -0.02em;">MEJA KOPI</h1>
                        <p style="font-size: 0.75rem; color: var(--accent); margin-top: 2px; font-weight: 600;">Digital Self-Ordering Cafe</p>
                    </div>
                </div>
                <div>
                    <span style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.25); color: #ffffff; padding: 6px 14px; border-radius: 20px; font-weight: 800; font-size: 0.8rem; display: flex; align-items: center; gap: 6px;">
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="3"></rect><rect x="14" y="7" width="3" height="3"></rect><rect x="7" y="14" width="3" height="3"></rect></svg>
                        <span>Meja {{ $table->table_number }}</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="category-tabs" style="padding: 14px 16px 12px 16px; gap: 8px;">
            <button type="button" class="cat-tab active" onclick="filterCategory('all', this)">Semua</button>
            @foreach($categories as $category)
                <button type="button" class="cat-tab" onclick="filterCategory('cat-{{ $category->id }}', this)">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>
    @if(session('error'))
        <div style="background: #ffebee; border: 1.5px solid #ffcdd2; color: #c62828; padding: 12px 16px; border-radius: 14px; font-size: 0.85rem; font-weight: 800; margin: 12px 16px; text-align: center;">
            ⚠️ {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background: #ffebee; border: 1.5px solid #ffcdd2; color: #c62828; padding: 12px 16px; border-radius: 14px; font-size: 0.85rem; font-weight: 800; margin: 12px 16px; text-align: center;">
            ⚠️ {{ $errors->first() }}
        </div>
    @endif

    <!-- Menu Section (Products Grid) -->
    <div class="menu-section">
        @foreach($categories as $category)
            <div class="category-group cat-{{ $category->id }} mb-6">
                <div class="flex items-center justify-between mb-3 px-1">
                    <h2 class="text-lg font-black text-stone-950 tracking-tight">{{ $category->name }}</h2>
                    <span class="text-xs text-stone-400 font-extrabold">{{ $category->products->count() }} Menu</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
                    @foreach($category->products as $product)
                        @php
                            $isAvailable = (bool) $product->is_available;
                        @endphp
                        <div class="product-card {{ !$isAvailable ? 'opacity-60 grayscale select-none pointer-events-none' : '' }}" style="{{ !$isAvailable ? 'filter: grayscale(100%); opacity: 0.6;' : '' }}">
                            <img src="{{ $product->image_url ? asset($product->image_url) : 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=400&q=80' }}"
                                 alt="{{ $product->name }}"
                                 class="product-img object-cover">
                            
                            <div class="product-info flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between gap-1">
                                        <h3 class="product-name font-bold text-stone-900 text-sm leading-snug">{{ $product->name }}</h3>
                                        @if(!$isAvailable)
                                            <span class="px-2 py-0.5 bg-stone-200 text-stone-700 text-[10px] font-extrabold rounded-md border border-stone-300 shrink-0">Habis</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-stone-500 line-clamp-2 mt-0.5 font-normal">{{ $product->description ?? 'Sensasi kenikmatan kopi khas pilihan' }}</p>
                                </div>

                                <div class="product-bottom mt-2">
                                    <span class="product-price">Rp{{ number_format($product->price, 0, ',', '.') }}</span>

                                    <!-- Quantity Stepper or Unavailable Badge -->
                                    @if($isAvailable)
                                        <div class="qty-control">
                                            <button type="button" class="btn-qty" onclick="changeQty({{ $product->id }}, -1)">-</button>
                                            <span class="qty-num" id="qty-val-{{ $product->id }}">0</span>
                                            <button type="button" class="btn-qty" onclick="changeQty({{ $product->id }}, 1)">+</button>
                                        </div>
                                    @else
                                        <span class="px-2.5 py-1 bg-stone-200 text-stone-700 font-extrabold text-[11px] rounded-xl border border-stone-300">
                                            Stok Tidak Tersedia
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Floating Cart Bar (Bottom Menu Trigger) -->
    <div class="floating-cart-bar" id="floatingCart" style="display: none;" onclick="openCartDrawer()">
        <div class="cart-summary">
            <span class="cart-count" id="cartCountText">0 Item</span>
            <span class="cart-total" id="cartTotalText">Rp0</span>
        </div>
        <button type="button" class="btn btn-accent btn-sm" style="display: flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: 12px; font-weight: 800;">
            <span>Lihat Keranjang</span>
            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
        </button>
    </div>

    <!-- Cart / Checkout Drawer / Modal Popup -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeCartDrawer()"></div>
    <div class="cart-drawer" id="cartDrawer">
        <!-- Modal Header -->
        <div class="drawer-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1c140e; margin: 0;">Pesanan Meja {{ $table->table_number }}</h3>
            <button type="button" class="btn-close" onclick="closeCartDrawer()" style="background: none; border: none; font-size: 1.4rem; font-weight: 400; cursor: pointer; color: #777777; padding: 4px; display: flex; align-items: center; justify-content: center; line-height: 1;" aria-label="Tutup">&times;</button>
        </div>
        <div style="height: 1px; background-color: #eee5dc; margin-bottom: 16px;"></div>

        <form action="{{ route('customer.order.checkout') }}" method="POST" id="checkoutForm" onsubmit="return validateCheckout()">
            @csrf
            <input type="hidden" name="table_id" value="{{ $table->id }}">
            <input type="hidden" name="cart_items" id="cartItemsInput">

            <!-- Table Number Info Field -->
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 600; font-size: 0.8rem; color: #5a4b41; margin-bottom: 6px; display: block;">Nomor Meja (Otomatis dari QR Code)</label>
                <div style="width: 100%; padding: 12px 16px; border-radius: 12px; background: #f5efe6; border: 1px solid #eae1d5; font-size: 0.9rem; font-weight: 600; color: #1c140e;">
                    Meja {{ $table->table_number }}
                </div>
            </div>

            <!-- Customer Name Input -->
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 600; font-size: 0.8rem; color: #5a4b41; margin-bottom: 6px; display: block;">Nama Pelanggan <span style="color: #d32f2f;">*</span></label>
                <input type="text" name="customer_name" id="customerName" class="form-control" placeholder="Masukkan nama Anda (misal: Rizmi)" required style="width: 100%; padding: 12px 16px; border: 1px solid #ebdcd0; border-radius: 12px; font-size: 0.9rem; font-weight: 500; background: #faf7f2; outline: none; transition: border-color 0.2s; color: #1c140e;">
            </div>

            <!-- Itemized Cart & Notes List Box -->
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 600; font-size: 0.8rem; color: #5a4b41; margin-bottom: 6px; display: block;">Daftar Menu & Catatan</label>
                <div style="background: #faf7f2; border: 1px solid #ebdcd0; border-radius: 16px; padding: 14px;">
                    <div id="cartItemsList" style="max-height: 250px; overflow-y: auto; display: flex; flex-direction: column;">
                        <!-- Rendered cleanly by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Total Payment Summary Card -->
            <div style="background: linear-gradient(135deg, #1e140e 0%, #3c2a21 100%); color: #ffffff; padding: 14px 18px; border-radius: 16px; margin: 16px 0; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 6px 20px rgba(30,20,14,0.15);">
                <div>
                    <div style="font-size: 0.7rem; color: #d4a373; font-weight: 600; letter-spacing: 0.05em;">TOTAL PEMBAYARAN</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: #ffffff;" id="drawerTotalText">Rp0</div>
                </div>
                <span style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: #ffffff; padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 500;">Dihitung Otomatis</span>
            </div>

            <!-- Payment Method Selection -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label" style="font-weight: 600; font-size: 0.8rem; color: #5a4b41; margin-bottom: 6px; display: block;">Pilih Metode Pembayaran</label>
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

            <button type="submit" id="btnSubmitOrder" class="btn btn-primary btn-block" style="width: 100%; padding: 14px; font-size: 0.95rem; font-weight: 700; background: linear-gradient(135deg, #4a3427 0%, #3c2a21 100%); color: #ffffff; border: none; border-radius: 16px; box-shadow: 0 6px 18px rgba(60,42,33,0.3); display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer;">
                <span>Konfirmasi & Buat Pesanan</span>
                <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Product catalog data from PHP
    const productsData = @json($productsData ?? []);
    let cart = {};

    document.addEventListener('DOMContentLoaded', () => {
        updateCartUI();
    });

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
        const cartDrawer = document.getElementById('cartDrawer');
        const isDrawerActive = cartDrawer && cartDrawer.classList.contains('active');
        const menuSection = document.querySelector('.menu-section');

        if (totalCount > 0 && !isDrawerActive) {
            if (floatingCart) floatingCart.style.display = 'flex';
            if (menuSection) menuSection.style.paddingBottom = '140px';
            document.getElementById('cartCountText').innerText = `${totalCount} Item`;
            document.getElementById('cartTotalText').innerText = formatRupiah(totalAmount);
            document.getElementById('drawerTotalText').innerText = formatRupiah(totalAmount);
        } else {
            if (floatingCart) floatingCart.style.display = 'none';
            if (menuSection) menuSection.style.paddingBottom = '80px';
            if (totalCount === 0) closeCartDrawer();
        }
    }

    function formatRupiah(number) {
        return 'Rp' + Number(number).toLocaleString('id-ID');
    }

    function openCartDrawer() {
        renderDrawerItems();
        document.getElementById('modalOverlay').classList.add('active');
        document.getElementById('cartDrawer').classList.add('active');

        // Hide floating cart bar when cart drawer is active
        const floatingCart = document.getElementById('floatingCart');
        if (floatingCart) {
            floatingCart.style.display = 'none';
        }
    }

    function closeCartDrawer() {
        document.getElementById('modalOverlay').classList.remove('active');
        document.getElementById('cartDrawer').classList.remove('active');

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
        if (floatingCart && totalCount > 0) {
            floatingCart.style.display = 'flex';
            document.getElementById('cartCountText').innerText = `${totalCount} Item`;
            document.getElementById('cartTotalText').innerText = formatRupiah(totalAmount);
            document.getElementById('drawerTotalText').innerText = formatRupiah(totalAmount);
        }
    }

    function getItemsPayload() {
        let itemsPayload = [];
        for (const [id, item] of Object.entries(cart)) {
            const product = productsData[id];
            if (!product) continue;
            itemsPayload.push({
                id: product.id,
                name: product.name,
                qty: item.qty,
                price: product.price,
                notes: item.notes || ''
            });
        }
        return itemsPayload;
    }

    function renderDrawerItems() {
        const container = document.getElementById('cartItemsList');
        container.innerHTML = '';

        const itemsPayload = getItemsPayload();

        itemsPayload.forEach((item, index) => {
            const product = productsData[item.id];
            if (!product) return;

            const subtotal = item.price * item.qty;
            const itemBlock = document.createElement('div');
            itemBlock.className = 'cart-item-entry';

            let itemHtml = '';
            if (index > 0) {
                itemHtml += `<div style="border-bottom: 1.5px dotted #e2d7ca; margin: 14px 0;"></div>`;
            }

            itemHtml += `
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                    <span style="font-weight: 500; font-size: 0.875rem; color: #1c140e;">${escapeHtml(product.name)} × ${item.qty}</span>
                    <span style="font-weight: 600; font-size: 0.875rem; color: #1c140e;">${formatRupiah(subtotal)}</span>
                </div>
                <div style="margin-top: 8px;">
                    <input type="text" placeholder="Catatan (misal: Less ice, tanpa gula)"
                           value="${escapeHtml(item.notes || '')}"
                           oninput="updateItemNotesDirect(${product.id}, this.value)"
                           style="width: 100%; padding: 8px 12px; font-size: 0.8rem; border: 1px solid #e5dbce; border-radius: 10px; background: #ffffff; outline: none; font-weight: 400; color: #1c140e; transition: border-color 0.2s;">
                </div>
            `;
            itemBlock.innerHTML = itemHtml;
            container.appendChild(itemBlock);
        });

        document.getElementById('cartItemsInput').value = JSON.stringify(itemsPayload);
    }

    function updateItemNotesDirect(productId, notesText) {
        if (cart[productId]) {
            cart[productId].notes = notesText;
            document.getElementById('cartItemsInput').value = JSON.stringify(getItemsPayload());
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
        document.getElementById('cartItemsInput').value = JSON.stringify(getItemsPayload());

        const btn = document.getElementById('btnSubmitOrder');
        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.75';
            btn.innerHTML = '<span>Memproses Pesanan...</span>';
        }
        return true;
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, function(m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
    }
</script>
@endpush
