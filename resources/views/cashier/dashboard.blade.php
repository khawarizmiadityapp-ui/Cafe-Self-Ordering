@extends('layouts.staff')

@section('title', 'Dashboard Kasir POS - Meja Kopi')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 font-sans antialiased text-stone-800">

    <!-- Top Header & Action Row (Industry Standard Clean POS Layout) -->
    <div class="bg-white p-4 sm:p-5 rounded-xl border border-stone-200 shadow-2xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-lg bg-stone-900 text-white flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="2" y="4" width="20" height="16" rx="2" stroke-width="2"></rect>
                    <path d="M6 10h12M6 14h8" stroke-width="2" stroke-linecap="round"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-bold tracking-tight text-stone-900">Dashboard Kasir POS</h1>
                <p class="text-xs text-stone-500 font-medium mt-0.5">Kelola pesanan masuk, pengiriman ke dapur & pelunasan kasir</p>
            </div>
        </div>

        <!-- Primary Action Buttons & Live Clock -->
        <div class="flex items-center gap-2.5 flex-wrap">
            <div class="px-3 py-2 rounded-lg bg-stone-50 text-stone-700 font-semibold text-xs flex items-center gap-2 border border-stone-200">
                <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span id="cashierLiveClock">00:00:00 WIB</span>
            </div>

            <button type="button" onclick="openHistoryModal()" class="px-3.5 py-2 rounded-lg bg-white hover:bg-stone-50 text-stone-700 font-semibold text-xs flex items-center gap-1.5 border border-stone-300 transition-colors shadow-2xs">
                <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Cetak Ulang / Riwayat</span>
            </button>

            <a href="{{ route('cashier.pos') }}" class="px-4 py-2 rounded-lg bg-amber-700 hover:bg-amber-800 text-white font-semibold text-xs flex items-center gap-1.5 shadow-2xs transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>+ Buka Kasir POS / Transaksi Baru</span>
            </a>
        </div>
    </div>

    <!-- 1. STATISTIC / SUMMARY CARDS (Clean POS Metric Cards) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
        <!-- a. Pesanan Baru -->
        <div onclick="filterCards('PENDING')" class="stat-filter-card cursor-pointer p-4 rounded-xl bg-white hover:bg-stone-50/80 border border-stone-200 shadow-2xs transition-all relative overflow-hidden active:scale-98">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-stone-500">Pesanan (Max 8)</span>
                <span id="badgeCountPending" class="px-2 py-0.5 rounded-md text-xs font-bold bg-sky-100 text-sky-800 border border-sky-200">0</span>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span id="statValPending" class="text-2xl font-bold text-stone-900 tracking-tight">0</span>
                <div class="w-7 h-7 rounded-lg bg-stone-100 flex items-center justify-center text-stone-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-stone-500 font-medium mt-1">Perlu Dikirim ke Dapur</p>
        </div>

        <!-- b. Dapur -->
        <div onclick="filterCards('WAITING_KITCHEN')" class="stat-filter-card cursor-pointer p-4 rounded-xl bg-white hover:bg-stone-50/80 border border-stone-200 shadow-2xs transition-all relative overflow-hidden active:scale-98">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-stone-500">Dapur</span>
                <span id="badgeCountWaiting" class="px-2 py-0.5 rounded-md text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200">0</span>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span id="statValWaiting" class="text-2xl font-bold text-stone-900 tracking-tight">0</span>
                <div class="w-7 h-7 rounded-lg bg-stone-100 flex items-center justify-center text-stone-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-stone-500 font-medium mt-1">Menunggu Koki</p>
        </div>

        <!-- c. Proses Dapur -->
        <div onclick="filterCards('PROCESSING')" class="stat-filter-card cursor-pointer p-4 rounded-xl bg-white hover:bg-stone-50/80 border border-stone-200 shadow-2xs transition-all relative overflow-hidden active:scale-98">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-stone-500">Proses</span>
                <span id="badgeCountProcessing" class="px-2 py-0.5 rounded-md text-xs font-bold bg-teal-100 text-teal-900 border border-teal-200">0</span>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span id="statValProcessing" class="text-2xl font-bold text-stone-900 tracking-tight">0</span>
                <div class="w-7 h-7 rounded-lg bg-stone-100 flex items-center justify-center text-stone-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-stone-500 font-medium mt-1">Sedang Dimasak</p>
        </div>

        <!-- d. Selesai -->
        <div onclick="filterCards('COMPLETED')" class="stat-filter-card cursor-pointer p-4 rounded-xl bg-white hover:bg-stone-50/80 border border-stone-200 shadow-2xs transition-all relative overflow-hidden active:scale-98">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider uppercase text-stone-500">Selesai</span>
                <span id="badgeCountCompleted" class="px-2 py-0.5 rounded-md text-xs font-bold bg-emerald-100 text-emerald-900 border border-emerald-200">0</span>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span id="statValCompleted" class="text-2xl font-bold text-stone-900 tracking-tight">0</span>
                <div class="w-7 h-7 rounded-lg bg-stone-100 flex items-center justify-center text-stone-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-[10px] text-stone-500 font-medium mt-1">Siap / Pembayaran</p>
        </div>

        <!-- e. Total Omset -->
        <div class="col-span-2 sm:col-span-1 p-4 rounded-xl bg-stone-900 text-white border border-stone-800 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold tracking-wider uppercase text-amber-400">Total Omset</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-stone-800 text-stone-300 border border-stone-700">Hari Ini</span>
            </div>
            <div class="mt-2.5 flex flex-col">
                <span id="statValTurnover" class="text-xl font-bold text-white tracking-tight">Rp0</span>
                <p class="text-[10px] text-stone-400 font-medium mt-0.5">Total Terbayar (Lunas)</p>
            </div>
        </div>
    </div>

    <!-- 2. FILTER & SEARCH BAR (Segmented Control Bar) -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white p-3 rounded-xl border border-stone-200 shadow-2xs">
        <!-- Filter Tabs (Segmented Control Strip) -->
        <div class="flex items-center gap-1 bg-stone-100 p-1 rounded-lg border border-stone-200 overflow-x-auto scrollbar-none">
            <button type="button" onclick="filterCards('PENDING')" id="tab-PENDING" class="filter-tab-btn active px-3.5 py-1.5 rounded-md text-xs font-bold transition-all whitespace-nowrap bg-stone-900 text-white shadow-xs cursor-pointer">
                Pesanan (Max 8)
            </button>
            <button type="button" onclick="filterCards('WAITING_KITCHEN')" id="tab-WAITING_KITCHEN" class="filter-tab-btn px-3.5 py-1.5 rounded-md text-xs font-medium transition-all whitespace-nowrap text-stone-700 hover:text-stone-900 hover:bg-stone-200/80 cursor-pointer">
                Dapur
            </button>
            <button type="button" onclick="filterCards('PROCESSING')" id="tab-PROCESSING" class="filter-tab-btn px-3.5 py-1.5 rounded-md text-xs font-medium transition-all whitespace-nowrap text-stone-700 hover:text-stone-900 hover:bg-stone-200/80 cursor-pointer">
                Proses
            </button>
            <button type="button" onclick="filterCards('COMPLETED')" id="tab-COMPLETED" class="filter-tab-btn px-3.5 py-1.5 rounded-md text-xs font-medium transition-all whitespace-nowrap text-stone-700 hover:text-stone-900 hover:bg-stone-200/80 cursor-pointer">
                Selesai
            </button>
        </div>

        <!-- Right Side: Bulk Clear Button & Search Bar -->
        <div class="flex items-center gap-2.5 w-full sm:w-auto">
            <!-- Tombol Hapus Pesanan Selesai & Lunas -->
            <button type="button" id="btnClearCompleted" onclick="handleClearAllCompletedAndPaid()" class="px-3.5 py-2 rounded-lg text-xs font-semibold transition-all whitespace-nowrap bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                <span>Hapus Selesai & Lunas</span>
            </button>

            <!-- Search Input -->
            <div class="relative w-full sm:w-60">
                <input type="text" id="searchInput" oninput="handleSearch(this.value)" placeholder="Cari nama / meja / ID..." class="w-full pl-9 pr-3 py-2 bg-stone-50 border border-stone-200 rounded-lg text-xs font-medium text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-stone-900/10 focus:bg-white focus:border-stone-400 transition-all">
                <svg class="w-4 h-4 text-stone-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- 3. MAIN CONTENT (Order Grid Cards) -->
    <div id="ordersGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Rendered dynamically by JavaScript -->
    </div>

    <!-- Empty State Container -->
    <div id="emptyState" class="hidden text-center py-16 bg-white/70 rounded-2xl border border-dashed border-stone-300 p-8">
        <div class="w-16 h-16 rounded-full bg-stone-100 flex items-center justify-center mx-auto text-stone-400 mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <h3 class="text-sm font-bold text-stone-700">Tidak ada pesanan ditemukan</h3>
        <p class="text-xs text-stone-500 mt-1">Coba ubah kata kunci pencarian atau filter status pesanan</p>
    </div>

    <!-- Pagination Container (Max 8 kartu per halaman, 4 kolom x 2 baris) -->
    <div id="paginationContainer" class="hidden flex flex-col sm:flex-row items-center justify-between gap-3 bg-white p-3.5 rounded-xl border border-stone-200 shadow-2xs">
        <div id="paginationInfo" class="text-xs text-stone-500 font-medium"></div>
        <div id="paginationButtons" class="flex items-center gap-1.5 flex-wrap"></div>
    </div>

</div>

<!-- Payment Checkout Modal -->
<div id="paymentModal" class="fixed inset-0 bg-stone-900/75 backdrop-blur-sm z-[999] flex items-center justify-center p-4 overflow-y-auto opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white rounded-3xl max-w-md w-full max-h-[88vh] overflow-y-auto p-5 sm:p-6 shadow-2xl border border-stone-100 transform scale-95 transition-transform duration-200 my-auto scrollbar-thin" id="paymentModalContent">
        <div class="flex items-center justify-between pb-3 border-b border-stone-100">
            <div>
                <h3 class="text-base sm:text-lg font-black text-stone-900">Pelunasan Pembayaran</h3>
                <p class="text-[11px] sm:text-xs font-semibold text-amber-800" id="payModalSubtitle">Order #0001 • Meja 01</p>
            </div>
            <button type="button" onclick="closePaymentModal()" class="w-7 h-7 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-500 flex items-center justify-center font-bold text-sm">
                &times;
            </button>
        </div>

        <div class="py-3 space-y-3">
            <!-- Rincian Menu Pesanan Pelanggan -->
            <div class="bg-amber-50/60 p-3 rounded-2xl border border-amber-200/70 space-y-1.5">
                <div class="flex items-center justify-between text-[10px] font-extrabold text-amber-900 uppercase tracking-wider">
                    <span>Rincian Menu Pesanan</span>
                    <span id="payModalItemCount" class="text-amber-800 font-bold">0 Menu</span>
                </div>
                <div id="payModalItemsContainer" class="max-h-28 overflow-y-auto space-y-1 pr-1 divide-y divide-amber-100/80 scrollbar-thin">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <!-- Total Bill Card -->
            <div class="bg-stone-900 text-white p-3.5 rounded-2xl flex items-center justify-between shadow-md">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-amber-400">TOTAL TAGIHAN</span>
                <span class="text-xl sm:text-2xl font-black tracking-tight" id="payModalTotal">Rp0</span>
            </div>

            <!-- Payment Method Selection -->
            <div>
                <label class="block text-[11px] font-bold text-stone-700 mb-1.5">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" onclick="selectPayMethod('cash')" id="btnPayCash" class="pay-method-btn p-2.5 rounded-xl border-2 border-emerald-500 bg-emerald-50 text-emerald-950 font-bold text-xs flex flex-col items-center gap-1 transition-all">
                        <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>TUNAI / CASH</span>
                    </button>
                    <button type="button" onclick="selectPayMethod('qris')" id="btnPayQris" class="pay-method-btn p-2.5 rounded-xl border-2 border-stone-200 bg-stone-50 text-stone-600 font-bold text-xs flex flex-col items-center gap-1 hover:border-stone-300 transition-all">
                        <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        <span>QRIS INSTAN</span>
                    </button>
                </div>
            </div>

            <!-- Cash Input Fields -->
            <div id="cashInputGroup" class="space-y-2.5">
                <div>
                    <label class="block text-[11px] font-bold text-stone-700 mb-1">Uang Diterima (Rp)</label>
                    <input type="number" id="cashReceivedInput" oninput="calculateChange()" placeholder="Masukkan nominal..." class="w-full p-2.5 bg-stone-50 border border-stone-300 rounded-xl text-sm font-extrabold text-stone-900 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                </div>

                <!-- Quick Cash Presets -->
                <div class="flex gap-1.5 flex-wrap">
                    <button type="button" onclick="setCashPreset('exact')" class="px-2.5 py-1 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-[11px]">Uang Pas</button>
                    <button type="button" onclick="setCashPreset(50000)" class="px-2.5 py-1 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-[11px]">Rp50.000</button>
                    <button type="button" onclick="setCashPreset(100000)" class="px-2.5 py-1 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-[11px]">Rp100.000</button>
                </div>

                <!-- Kembalian Display -->
                <div class="p-2.5 bg-stone-100 rounded-xl flex items-center justify-between">
                    <span class="text-[11px] font-bold text-stone-600">Kembalian:</span>
                    <span class="text-sm sm:text-base font-black text-emerald-700" id="changeDisplay">Rp0</span>
                </div>
            </div>
        </div>

        <div class="pt-1 flex gap-2">
            <button type="button" onclick="closePaymentModal()" class="w-1/3 py-2.5 rounded-xl border border-stone-300 font-bold text-xs text-stone-600 hover:bg-stone-100">Batal</button>
            <button type="button" onclick="submitPayment()" class="w-2/3 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md shadow-emerald-600/20 active:scale-95 transition-all">Konfirmasi Lunas</button>
        </div>
    </div>
</div>

<!-- AUTO POP-UP THERMAL RECEIPT MODAL -->
<div id="receiptModal" class="fixed inset-0 bg-stone-900/75 backdrop-blur-sm z-[999] flex items-center justify-center p-4 sm:p-6 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white rounded-3xl max-w-md w-full p-4 sm:p-5 shadow-2xl border border-stone-100 transform scale-95 transition-transform duration-200 flex flex-col max-h-[80vh] my-auto">
        <!-- Pinned Header -->
        <div class="flex items-center justify-between pb-3 border-b border-stone-100 shrink-0">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-900 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                </div>
                <h3 class="text-base font-black text-stone-900">Struk Pembayaran</h3>
            </div>
            <button type="button" onclick="closeReceiptModal()" class="w-7 h-7 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-500 flex items-center justify-center font-bold text-sm">
                &times;
            </button>
        </div>
        
        <!-- Scrollable Receipt Body -->
        <div class="flex-1 overflow-y-auto my-3 pr-1 scrollbar-thin space-y-2">
            <!-- Thermal Receipt Box -->
            <div id="printableReceiptBox" class="font-mono text-xs text-stone-900 space-y-2.5 bg-stone-50/90 p-4 rounded-2xl border border-stone-200/90 shadow-2xs">
                <!-- Header Logo & Cafe Name -->
                <div class="text-center space-y-0.5 pb-2.5 border-b border-dashed border-stone-300">
                    <div class="font-black text-base tracking-wider text-stone-900">MEJA KOPI</div>
                    <div class="text-[10px] text-stone-500 font-sans">Jl. Kopi Digital No. 88, Indonesia</div>
                    <div class="text-[10px] text-stone-500 font-sans">Telp: 0812-3456-7890</div>
                </div>
                
                <!-- Order Metadata -->
                <div class="text-[11px] py-1 border-b border-dashed border-stone-300 space-y-1">
                    <div class="flex justify-between"><span class="text-stone-500">No. Order:</span><strong id="rcpOrderNum" class="font-black text-stone-900">#0001</strong></div>
                    <div class="flex justify-between"><span class="text-stone-500">Tipe / Meja:</span><strong id="rcpTable" class="font-bold text-stone-800">Meja 01</strong></div>
                    <div class="flex justify-between"><span class="text-stone-500">Pelanggan:</span><strong id="rcpCustomer" class="font-bold text-stone-800">apep</strong></div>
                    <div class="flex justify-between"><span class="text-stone-500">Waktu:</span><strong id="rcpRealtimeDate" class="font-semibold text-stone-700">28/08/2026 07:37:05</strong></div>
                    <div class="flex justify-between"><span class="text-stone-500">Kasir:</span><strong id="rcpCashierName" class="font-semibold text-stone-700">{{ auth()->user()->name ?? 'Kasir' }}</strong></div>
                </div>

                <!-- Items List -->
                <div class="py-1 border-b border-dashed border-stone-300 space-y-1.5" id="rcpItemsList">
                    <!-- Populated dynamically via JS -->
                </div>

                <!-- Payment Summary -->
                <div class="text-[11px] pt-1 space-y-1">
                    <div class="flex justify-between font-black text-xs text-stone-900 pt-0.5">
                        <span>Total Tagihan:</span>
                        <span id="rcpTotalAmount">Rp0</span>
                    </div>
                    <div class="flex justify-between text-stone-600">
                        <span>Metode Pay:</span>
                        <span id="rcpPayMethod" class="font-bold">TUNAI</span>
                    </div>
                    <div class="flex justify-between text-stone-600">
                        <span>Uang Diterima:</span>
                        <span id="rcpCashReceived">Rp0</span>
                    </div>
                    <div class="flex justify-between font-bold text-emerald-700 pt-0.5">
                        <span>Kembalian:</span>
                        <span id="rcpCashChange">Rp0</span>
                    </div>
                </div>

                <!-- Receipt Footer Message -->
                <div class="text-center text-[10px] text-stone-400 pt-2 border-t border-dashed border-stone-300 font-sans">
                    <div>*** Terima Kasih Atas Kunjungan Anda ***</div>
                    <div class="text-[9px] text-stone-400 mt-0.5">WiFi: MejaKopiGuest / Pass: ngopidulu</div>
                </div>
            </div>
        </div>

        <!-- Pinned Footer Buttons -->
        <div class="flex gap-2.5 pt-2 border-t border-stone-100 shrink-0">
            <button type="button" onclick="closeReceiptModal()" class="w-1/3 py-2.5 rounded-xl border border-stone-300 font-bold text-xs text-stone-600 hover:bg-stone-100 active:scale-95 transition-all">
                Tutup
            </button>
            <button type="button" onclick="triggerPrintReceipt()" class="w-2/3 py-2.5 rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-extrabold text-xs shadow-md shadow-amber-600/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Sekarang</span>
            </button>
        </div>
    </div>
</div>

<!-- History & Reprint Modal -->
<div id="historyModal" class="fixed inset-0 bg-stone-900/75 backdrop-blur-sm z-[999] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-stone-100 transform scale-95 transition-transform duration-200">
        <div class="flex items-center justify-between pb-3 border-b border-stone-100">
            <div>
                <h3 class="text-lg font-black text-stone-900">Riwayat & Cetak Ulang</h3>
                <p class="text-xs text-stone-500">Daftar transaksi kasir hari ini</p>
            </div>
            <button type="button" onclick="closeHistoryModal()" class="w-8 h-8 rounded-full bg-stone-100 text-stone-500 flex items-center justify-center font-bold text-sm">&times;</button>
        </div>
        <div class="py-4 max-h-96 overflow-y-auto space-y-2.5" id="historyListContainer">
            <!-- Populated via JS -->
        </div>
    </div>
</div>

<!-- Toast Feedback Banner -->
<div id="toast" class="fixed bottom-6 right-6 z-50 bg-stone-900 text-white px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 text-xs font-bold transition-all duration-300 translate-y-20 opacity-0">
    <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center text-stone-900 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
    </div>
    <span id="toastMessage">Pesan Toast</span>
</div>
@endsection

@push('scripts')
<script>
    // State Store
    let allRawOrdersData = @json($allOrdersToday ?? []);
    let ordersData = [];
    let archivedOrderIds = new Set();
    let latestTotalTurnover = Number(@json($stats['total_pendapatan'] ?? 0));
    let isSyncingPolling = false;

    // Load archived order IDs from localStorage so deletion persists across refresh
    try {
        const savedArchived = localStorage.getItem('cashier_archived_orders');
        if (savedArchived) {
            archivedOrderIds = new Set(JSON.parse(savedArchived));
        }
    } catch (e) {}

    function saveArchivedOrderIds() {
        try {
            localStorage.setItem('cashier_archived_orders', JSON.stringify(Array.from(archivedOrderIds)));
        } catch (e) {}
    }

    let currentFilter = 'PENDING'; // Default Tab: Pesanan
    let currentSearch = '';
    let currentPage = 1;
    const itemsPerPage = 10; // Max 10 items per page
    let activePayOrderId = null;
    let activeReceiptOrderId = null;
    let selectedPayMethod = 'cash';

    // Helper parser for raw orders from backend DB
    function parseRawOrdersData(rawList) {
        if (!Array.isArray(rawList)) return [];

        return rawList
            .filter(o => !o.is_archived && !archivedOrderIds.has(o.id))
            .map(o => ({
                id: o.id,
                order_number: o.order_number,
                table_number: o.table ? o.table.table_number : null,
                order_type: o.table ? 'table' : 'takeaway',
                customer_name: o.customer_name || 'Pelanggan',
                order_status: o.order_status,
                payment_status: o.payment_status,
                payment_method: o.payment_method || 'cash',
                total_amount: o.total_amount,
                created_at_formatted: o.created_at ? (o.created_at.length >= 16 ? o.created_at.substring(11, 16) : o.created_at) : 'Baru',
                elapsed_time: 'Baru',
                items: (o.items || []).map(i => ({
                    qty: i.quantity,
                    price: i.price,
                    subtotal: i.subtotal || (i.price * i.quantity),
                    name: i.product ? i.product.name : (i.name || 'Menu'),
                    notes: i.notes || ''
                }))
            }));
    }

    // Initialize application state
    document.addEventListener('DOMContentLoaded', () => {
        // Hydrate directly from Laravel backend DB orders
        const serverOrders = @json($orders ?? []);
        ordersData = parseRawOrdersData(serverOrders);

        renderDashboard();

        // Live Poll Sync with Server DB (Every 3 seconds)
        setInterval(() => {
            if (isSyncingPolling) return;
            isSyncingPolling = true;

            fetch('/cashier/dashboard', { headers: { 'Accept': 'application/json' } })
                .then(res => res.json())
                .then(data => {
                    if (data.stats && data.stats.total_pendapatan !== undefined) {
                        latestTotalTurnover = Number(data.stats.total_pendapatan);
                    }
                    if (Array.isArray(data.all_orders)) {
                        allRawOrdersData = data.all_orders;
                    }
                    const rawList = data.orders && data.orders.data ? data.orders.data : (data.orders || []);
                    ordersData = parseRawOrdersData(rawList);
                    renderDashboard();
                })
                .catch(() => {})
                .finally(() => {
                    isSyncingPolling = false;
                });
        }, 3000);
    });

    /**
     * Render Statistics & Order Cards
     */
    function renderDashboard() {
        updateStatSummaryCards();
        renderOrderGridCards();
    }

    function updateStatSummaryCards() {
        const pendingCount = ordersData.filter(o => o.order_status === 'PENDING').length;
        const waitingCount = ordersData.filter(o => o.order_status === 'WAITING_KITCHEN').length;
        const processingCount = ordersData.filter(o => o.order_status === 'PROCESSING').length;
        const completedCount = ordersData.filter(o => o.order_status === 'COMPLETED').length;

        // Total Turnover calculated from ALL raw database orders today where payment_status === 'PAID'
        // Ensures Omset DOES NOT DECREASE or drop to 0 when completed orders are archived/cleared from active view!
        const calculatedTurnover = allRawOrdersData
            .filter(o => o.payment_status === 'PAID')
            .reduce((sum, o) => sum + Number(o.total_amount), 0);

        const finalTurnover = Math.max(latestTotalTurnover, calculatedTurnover);

        document.getElementById('badgeCountPending').innerText = pendingCount;
        document.getElementById('statValPending').innerText = pendingCount;

        document.getElementById('badgeCountWaiting').innerText = waitingCount;
        document.getElementById('statValWaiting').innerText = waitingCount;

        document.getElementById('badgeCountProcessing').innerText = processingCount;
        document.getElementById('statValProcessing').innerText = processingCount;

        document.getElementById('badgeCountCompleted').innerText = completedCount;
        document.getElementById('statValCompleted').innerText = completedCount;

        document.getElementById('statValTurnover').innerText = formatRupiah(finalTurnover);
    }

    function changePage(page) {
        currentPage = page;
        renderOrderGridCards();
        document.getElementById('ordersGrid')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function renderOrderGridCards() {
        const container = document.getElementById('ordersGrid');
        const emptyState = document.getElementById('emptyState');
        const paginationContainer = document.getElementById('paginationContainer');

        // Filter Logic
        let filtered = ordersData.filter(order => {
            const matchStatus = (order.order_status === currentFilter);
            const searchLower = currentSearch.toLowerCase();
            const matchSearch = !currentSearch ||
                order.customer_name.toLowerCase().includes(searchLower) ||
                order.order_number.toLowerCase().includes(searchLower) ||
                (order.table_number && order.table_number.toLowerCase().includes(searchLower));
            return matchStatus && matchSearch;
        });

        const totalItems = filtered.length;

        if (totalItems === 0) {
            container.innerHTML = '';
            emptyState.classList.remove('hidden');
            if (paginationContainer) paginationContainer.classList.add('hidden');
            return;
        }

        emptyState.classList.add('hidden');

        // Pagination Logic (Max 8 items per page)
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        if (currentPage > totalPages) currentPage = Math.max(1, totalPages);
        if (currentPage < 1) currentPage = 1;

        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
        const pageItems = filtered.slice(startIndex, endIndex);

        container.innerHTML = pageItems.map(order => createOrderCardHTML(order)).join('');

        // Render Pagination UI
        renderPaginationUI(totalItems, totalPages, startIndex, endIndex);
    }

    function renderPaginationUI(totalItems, totalPages, startIndex, endIndex) {
        const paginationContainer = document.getElementById('paginationContainer');
        const paginationInfo = document.getElementById('paginationInfo');
        const paginationButtons = document.getElementById('paginationButtons');

        if (!paginationContainer) return;

        if (totalPages <= 1) {
            paginationContainer.classList.add('hidden');
            return;
        }

        paginationContainer.classList.remove('hidden');

        paginationInfo.innerHTML = `Menampilkan <span class="font-bold text-stone-900">${startIndex + 1}</span> - <span class="font-bold text-stone-900">${endIndex}</span> dari <span class="font-bold text-stone-900">${totalItems}</span> pesanan (Halaman <span class="font-bold text-stone-900">${currentPage}</span> dari <span class="font-bold text-stone-900">${totalPages}</span>)`;

        let btnHTML = '';

        // Previous Button
        const isPrevDisabled = currentPage === 1;
        btnHTML += `
            <button type="button" 
                onclick="changePage(${currentPage - 1})" 
                ${isPrevDisabled ? 'disabled' : ''} 
                class="px-3 py-1.5 rounded-lg text-xs font-semibold border flex items-center gap-1 transition-all ${isPrevDisabled ? 'bg-stone-50 text-stone-300 cursor-not-allowed border-stone-100' : 'bg-white hover:bg-stone-50 text-stone-700 cursor-pointer shadow-2xs border-stone-200'}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                <span>Sebelumnya</span>
            </button>
        `;

        // Page Numbers (1, 2, 3, ...)
        for (let i = 1; i <= totalPages; i++) {
            const isActive = i === currentPage;
            if (isActive) {
                btnHTML += `
                    <button type="button" class="w-8 h-8 rounded-lg text-xs font-bold bg-stone-900 text-white shadow-xs flex items-center justify-center">
                        ${i}
                    </button>
                `;
            } else {
                btnHTML += `
                    <button type="button" onclick="changePage(${i})" class="w-8 h-8 rounded-lg text-xs font-semibold bg-white hover:bg-stone-100 text-stone-700 border border-stone-200 shadow-2xs flex items-center justify-center cursor-pointer transition-all">
                        ${i}
                    </button>
                `;
            }
        }

        // Next Button
        const isNextDisabled = currentPage === totalPages;
        btnHTML += `
            <button type="button" 
                onclick="changePage(${currentPage + 1})" 
                ${isNextDisabled ? 'disabled' : ''} 
                class="px-3 py-1.5 rounded-lg text-xs font-semibold border flex items-center gap-1 transition-all ${isNextDisabled ? 'bg-stone-50 text-stone-300 cursor-not-allowed border-stone-100' : 'bg-white hover:bg-stone-50 text-stone-700 cursor-pointer shadow-2xs border-stone-200'}">
                <span>Berikutnya</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        `;

        paginationButtons.innerHTML = btnHTML;
    }

    /**
     * MAIN CONTENT: Order Grid Card Template
     */
    function createOrderCardHTML(order) {
        // Status Badge Style Mapping with subtle accent colors & glows
        const statusConfigs = {
            'PENDING': { label: 'Pesanan Baru', bg: 'bg-sky-50 text-sky-800 border-sky-200', accentBar: 'bg-sky-500', iconColor: 'text-sky-600' },
            'WAITING_KITCHEN': { label: 'Di Dapur', bg: 'bg-amber-50 text-amber-900 border-amber-200', accentBar: 'bg-amber-500', iconColor: 'text-amber-600' },
            'PROCESSING': { label: 'Proses Dapur', bg: 'bg-teal-50 text-teal-900 border-teal-200', accentBar: 'bg-teal-500', iconColor: 'text-teal-600' },
            'COMPLETED': { label: 'Selesai', bg: 'bg-emerald-50 text-emerald-900 border-emerald-200', accentBar: 'bg-emerald-500', iconColor: 'text-emerald-600' }
        };

        const config = statusConfigs[order.order_status] || { label: order.order_status, bg: 'bg-stone-50 text-stone-800 border-stone-200', accentBar: 'bg-stone-400', iconColor: 'text-stone-500' };
        const isPaid = String(order.payment_status || '').toUpperCase() === 'PAID';
        const payBadgeClass = isPaid 
            ? 'bg-emerald-50 text-emerald-800 border-emerald-200/80 font-extrabold' 
            : 'bg-rose-50 text-rose-800 border-rose-200/80 font-extrabold';
        const payLabel = isPaid ? 'LUNAS' : 'BELUM BAYAR';

        const tableBadge = order.table_number
            ? `<span class="px-2.5 py-1 bg-stone-100/80 text-stone-800 font-extrabold text-[11px] rounded-lg border border-stone-200/80 flex items-center gap-1 shadow-2xs">
                <svg class="w-3 h-3 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Meja ${order.table_number}
               </span>`
            : `<span class="px-2.5 py-1 bg-amber-50/80 text-amber-900 font-extrabold text-[11px] rounded-lg border border-amber-200/80 flex items-center gap-1 shadow-2xs">
                <svg class="w-3 h-3 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Takeaway
               </span>`;

        // Render Item List HTML on Card Grid
        const itemsListHTML = order.items.map(item => {
            const itemSubtotal = item.subtotal || (item.price ? item.price * item.qty : 0);
            return `
                <div class="py-1.5 border-b border-dashed border-stone-200/80 last:border-0 group-hover:border-stone-300 transition-colors">
                    <div class="flex items-start justify-between text-xs font-bold text-stone-800">
                        <div class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded bg-stone-200/70 text-stone-800 text-[10px] font-black flex items-center justify-center">${item.qty}x</span>
                            <span>${escapeHtml(item.name)}</span>
                        </div>
                        ${itemSubtotal > 0 ? `<span class="text-[11px] font-extrabold text-stone-700">${formatRupiah(itemSubtotal)}</span>` : ''}
                    </div>
                    ${item.notes ? `<p class="text-[10px] text-amber-800 italic mt-0.5 font-medium pl-5">Catatan: ${escapeHtml(item.notes)}</p>` : ''}
                </div>
            `;
        }).join('');

        let leftActionButtonHTML = '';
        if (order.order_status === 'PENDING') {
            leftActionButtonHTML = `
                <button type="button" onclick="handleSendToKitchen(${order.id})" class="col-span-2 py-2 px-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs flex items-center justify-center gap-1 shadow-xs hover:shadow transition-all active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    <span>Ke Dapur</span>
                </button>
            `;
        } else if (order.order_status === 'WAITING_KITCHEN') {
            leftActionButtonHTML = `
                <div class="col-span-2 py-2 px-2 rounded-xl bg-amber-50 text-amber-900 font-bold text-xs flex items-center justify-center gap-1 border border-amber-200 shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Di Dapur</span>
                </div>
            `;
        } else if (order.order_status === 'PROCESSING') {
            leftActionButtonHTML = `
                <div class="col-span-2 py-2 px-2 rounded-xl bg-teal-50 text-teal-900 font-bold text-xs flex items-center justify-center gap-1 border border-teal-200 shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                    <span>Proses</span>
                </div>
            `;
        } else {
            leftActionButtonHTML = `
                <button type="button" onclick="handleArchiveCompletedOrder(${order.id})" title="Hapus pesanan ini dari daftar aktif" class="col-span-2 py-2 px-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs flex items-center justify-center gap-1 shadow-xs hover:shadow transition-all active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>${isPaid ? 'Selesai & Hapus' : 'Hapus'}</span>
                </button>
            `;
        }

        return `
            <div class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs hover:shadow-xl hover:-translate-y-1 hover:border-amber-500/40 transition-all duration-300 flex flex-col justify-between overflow-hidden group relative backdrop-blur-xs hover:ring-2 hover:ring-amber-500/10">
                <!-- Status Top Accent Indicator Line -->
                <div class="h-1 w-full ${config.accentBar} opacity-80 group-hover:opacity-100 transition-opacity"></div>

                <!-- Header Kartu -->
                <div class="p-3.5 sm:p-4 bg-gradient-to-b from-stone-50/90 to-white border-b border-stone-100 space-y-2.5">
                    <div class="flex items-center justify-between">
                        ${tableBadge}
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold ${config.bg} border ${config.border} shadow-2xs flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full ${config.accentBar} animate-pulse"></span>
                            ${config.label}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-0.5">
                        <div class="flex items-center gap-1.5">
                            <span class="font-black text-stone-900 tracking-tight text-sm">${order.order_number}</span>
                            <span class="text-stone-300">•</span>
                            <span class="text-stone-500 font-semibold text-[11px]">${order.created_at_formatted}</span>
                        </div>
                        <span class="text-[10px] font-bold text-stone-600 bg-stone-100 border border-stone-200/70 px-2 py-0.5 rounded-md shadow-2xs">${order.elapsed_time}</span>
                    </div>
                </div>

                <!-- Customer Info & Item List -->
                <div class="p-3.5 sm:p-4 space-y-3 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-1 text-xs mb-2.5">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <div class="w-5 h-5 rounded-full bg-stone-100 text-stone-600 flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <span class="text-stone-500 text-[11px] font-medium">Pelanggan:</span>
                                <span class="font-extrabold text-stone-900 bg-stone-100/90 border border-stone-200/60 px-2 py-0.5 rounded-lg text-xs truncate max-w-[130px]">${escapeHtml(order.customer_name)}</span>
                            </div>
                        </div>

                        <!-- Item List -->
                        <div class="bg-stone-50/80 p-2.5 rounded-xl border border-stone-200/70 max-h-36 overflow-y-auto scrollbar-thin group-hover:border-stone-300 transition-colors">
                            ${itemsListHTML}
                        </div>
                    </div>

                    <!-- Footer Info: Total & Payment Badge -->
                    <div class="pt-2.5 flex items-center justify-between border-t border-stone-100">
                        <div>
                            <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wider block">Total Tagihan</span>
                            <span class="text-base font-black text-stone-900 tracking-tight">${formatRupiah(order.total_amount)}</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black border ${payBadgeClass} shadow-2xs">
                            ${order.payment_method.toUpperCase()} • ${payLabel}
                        </span>
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="p-3 sm:p-3.5 bg-stone-50/80 border-t border-stone-200/80 grid grid-cols-5 gap-2">
                    <!-- 1. Left Status Action Button -->
                    ${leftActionButtonHTML}

                    <!-- 2. Tombol Pembayaran / Bayar -->
                    <button type="button" onclick="handleProcessPayment(${order.id})" class="col-span-2 py-2 px-2 rounded-xl ${isPaid ? 'bg-stone-200 hover:bg-stone-300 text-stone-800 font-bold' : 'bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold shadow-xs hover:shadow'} text-xs flex items-center justify-center gap-1 transition-all active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>${isPaid ? 'Lunas' : 'Bayar'}</span>
                    </button>

                    <!-- 3. Tombol Icon Cetak Bill / Struk -->
                    <button type="button" onclick="handlePrintBill(${order.id})" title="Cetak Struk" class="col-span-1 py-2 rounded-xl bg-white hover:bg-stone-100 text-stone-700 font-bold border border-stone-200 shadow-2xs hover:shadow-xs flex items-center justify-center transition-all active:scale-95">
                        <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </button>
                </div>
            </div>
        `;
    }

    /**
     * Action Handlers
     */

    // REQ: Action Kirim ke Dapur -> Hanya berlaku untuk status PENDING
    function handleSendToKitchen(orderId) {
        const order = ordersData.find(o => o.id === orderId);
        if (!order) return;

        if (order.order_status !== 'PENDING') {
            showToast(`Pesanan ${order.order_number} sudah pernah dikirim ke Dapur/Proses/Selesai.`);
            return;
        }

        fetch(`/cashier/orders/${orderId}/send-kitchen`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(res => res.json())
        .catch(() => {})
        .finally(() => {
            // Status berubah ke WAITING_KITCHEN
            order.order_status = 'WAITING_KITCHEN';
            renderDashboard(); // Re-render: otomatis hilang dari Tab 'Pesanan'!
            showToast(`Pesanan ${order.order_number} (${order.table_number ? 'Meja ' + order.table_number : 'Takeaway'}) berhasil dikirim ke Dapur!`);
        });
    }

    // Action 1: Hapus satu per satu pesanan selesai & lunas (Simpan di Rekap Transaksi, Hapus dari Tampilan Aktif Kasir)
    async function handleArchiveCompletedOrder(orderId) {
        const order = ordersData.find(o => o.id === orderId);
        if (!order) return;

        if (confirm(`Apakah Anda yakin ingin menyelesaikan & menghapus Pesanan ${order.order_number} (${order.customer_name}) dari daftar aktif Selesai?\n(Pesanan tersimpan permanen di Rekap Transaksi & Total Omset Hari Ini tidak berkurang)`)) {
            archivedOrderIds.add(orderId);
            saveArchivedOrderIds();
            ordersData = ordersData.filter(o => o.id !== orderId);
            renderDashboard();

            try {
                await fetch(`/cashier/orders/${orderId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
            } catch (e) {}

            showToast(`Pesanan ${order.order_number} diselesaikan & dihapus dari daftar aktif.`);
        }
    }

    // Action 2: HAPUS SEMUA pesanan selesai & lunas (Bulk Clear & Archive in DB)
    async function handleClearAllCompletedAndPaid() {
        const targetOrders = ordersData.filter(o => 
            o.order_status === 'COMPLETED' && 
            o.payment_status === 'PAID'
        );

        if (targetOrders.length === 0) {
            alert('Tidak ada pesanan Selesai & Lunas yang perlu dihapus.');
            return;
        }

        if (confirm(`Apakah Anda yakin ingin menghapus ${targetOrders.length} pesanan Selesai & Lunas dari daftar aktif Selesai?\n(Semua transaksi tetap tersimpan utuh & permanen di Rekap Transaksi)`)) {
            targetOrders.forEach(o => archivedOrderIds.add(o.id));
            saveArchivedOrderIds();
            ordersData = ordersData.filter(o => !(o.order_status === 'COMPLETED' && o.payment_status === 'PAID'));
            renderDashboard();

            try {
                await fetch(`/cashier/orders/clear-completed`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
            } catch (e) {}

            showToast(`${targetOrders.length} pesanan Selesai & Lunas berhasil dihapus dari daftar aktif.`);
        }
    }

    // Tab Filter Switcher
    const tabStatuses = ['PENDING', 'WAITING_KITCHEN', 'PROCESSING', 'COMPLETED'];

    function filterCards(status) {
        currentFilter = status;
        currentPage = 1;

        tabStatuses.forEach(s => {
            const tabBtn = document.getElementById(`tab-${s}`);
            if (tabBtn) {
                if (s === status) {
                    tabBtn.className = 'filter-tab-btn active px-3.5 py-1.5 rounded-md text-xs font-bold transition-all whitespace-nowrap bg-stone-900 text-white shadow-xs cursor-pointer';
                } else {
                    tabBtn.className = 'filter-tab-btn px-3.5 py-1.5 rounded-md text-xs font-medium transition-all whitespace-nowrap text-stone-700 hover:text-stone-900 hover:bg-stone-200/80 cursor-pointer';
                }
            }
        });

        renderOrderGridCards();
    }

    // Action 2: Proses Pembayaran (Buka Modal Checkout)
    function handleProcessPayment(orderId) {
        const order = ordersData.find(o => o.id === orderId);
        if (!order) return;

        activePayOrderId = orderId;
        document.getElementById('payModalSubtitle').innerText = `${order.order_number} • ${order.table_number ? 'Meja ' + order.table_number : 'Takeaway'} (${order.customer_name})`;
        document.getElementById('payModalTotal').innerText = formatRupiah(order.total_amount);

        // Render Itemized Breakdown List in Payment Modal
        const itemsContainer = document.getElementById('payModalItemsContainer');
        const itemCount = document.getElementById('payModalItemCount');
        
        itemCount.innerText = `${order.items.length} Menu`;
        itemsContainer.innerHTML = order.items.map(item => {
            const itemSubtotal = item.subtotal || (item.price ? item.price * item.qty : 0);
            return `
                <div class="pt-1.5 first:pt-0">
                    <div class="flex items-center justify-between text-xs font-bold text-stone-800">
                        <span>${item.qty}x ${escapeHtml(item.name)}</span>
                        <span class="text-amber-950 font-black">${itemSubtotal > 0 ? formatRupiah(itemSubtotal) : ''}</span>
                    </div>
                    ${item.notes ? `<p class="text-[11px] text-amber-800 italic font-semibold mt-0.5">Catatan: ${escapeHtml(item.notes)}</p>` : ''}
                </div>
            `;
        }).join('');

        document.getElementById('cashReceivedInput').value = '';
        document.getElementById('changeDisplay').innerText = 'Rp0';

        selectPayMethod('cash');
        openPaymentModal();
    }

    // REQ 2 & 3: Buka Modal Struk Thermal Pop-up dengan Waktu Realtime
    function handlePrintBill(orderId) {
        openReceiptModal(orderId);
    }

    /**
     * Filter & Search Handlers
     */
    function handleSearch(query) {
        currentSearch = query;
        currentPage = 1;
        renderOrderGridCards();
    }

    /**
     * Modal Payment Helpers
     */
    function openPaymentModal() {
        const modal = document.getElementById('paymentModal');
        const content = document.getElementById('paymentModalContent');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }

    function closePaymentModal() {
        const modal = document.getElementById('paymentModal');
        const content = document.getElementById('paymentModalContent');
        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
    }

    function selectPayMethod(method) {
        selectedPayMethod = method;
        const btnCash = document.getElementById('btnPayCash');
        const btnQris = document.getElementById('btnPayQris');
        const cashGroup = document.getElementById('cashInputGroup');

        if (method === 'cash') {
            btnCash.className = 'pay-method-btn p-2.5 rounded-xl border-2 border-emerald-500 bg-emerald-50 text-emerald-950 font-bold text-xs flex flex-col items-center gap-1 transition-all';
            btnQris.className = 'pay-method-btn p-2.5 rounded-xl border-2 border-stone-200 bg-stone-50 text-stone-600 font-bold text-xs flex flex-col items-center gap-1 hover:border-stone-300 transition-all';
            cashGroup.classList.remove('hidden');
        } else {
            btnQris.className = 'pay-method-btn p-2.5 rounded-xl border-2 border-emerald-500 bg-emerald-50 text-emerald-950 font-bold text-xs flex flex-col items-center gap-1 transition-all';
            btnCash.className = 'pay-method-btn p-2.5 rounded-xl border-2 border-stone-200 bg-stone-50 text-stone-600 font-bold text-xs flex flex-col items-center gap-1 hover:border-stone-300 transition-all';
            cashGroup.classList.add('hidden');
        }
    }

    function setCashPreset(val) {
        const order = ordersData.find(o => o.id === activePayOrderId);
        if (!order) return;

        if (val === 'exact') {
            document.getElementById('cashReceivedInput').value = order.total_amount;
        } else {
            document.getElementById('cashReceivedInput').value = val;
        }
        calculateChange();
    }

    function calculateChange() {
        const order = ordersData.find(o => o.id === activePayOrderId);
        if (!order) return;

        const received = Number(document.getElementById('cashReceivedInput').value) || 0;
        const change = received - order.total_amount;
        document.getElementById('changeDisplay').innerText = formatRupiah(Math.max(0, change));
    }

    // REQ 1 & 2: Dikonfirmasi LUNAS -> Auto Buka POP-UP STRUK MODAL!
    function submitPayment() {
        const order = ordersData.find(o => o.id === activePayOrderId);
        if (!order) return;

        let cashReceived = Number(document.getElementById('cashReceivedInput').value) || order.total_amount;
        if (selectedPayMethod === 'cash' && cashReceived < order.total_amount) {
            alert('Nominal uang tunai diterima kurang dari total tagihan!');
            return;
        }

        const cashChange = Math.max(0, cashReceived - order.total_amount);

        // Send payment confirmation to backend
        fetch(`/cashier/orders/${order.id}/confirm-cash`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ cash_received: cashReceived })
        }).catch(() => {});

        order.payment_status = 'PAID';
        order.payment_method = selectedPayMethod;
        order.cash_received = cashReceived;
        order.cash_change = cashChange;

        closePaymentModal();
        renderDashboard();
        showToast(`Pembayaran pesanan ${order.order_number} dikonfirmasi LUNAS!`);

        // REQ 1 & 2: AUTO CETAK STRUK POP-UP MODAL!
        setTimeout(() => {
            openReceiptModal(order.id);
        }, 300);
    }

    /**
     * REQ 2 & 3: POP-UP THERMAL RECEIPT MODAL & REALTIME TIMESTAMP
     */
    function openReceiptModal(orderId) {
        const order = ordersData.find(o => o.id === orderId);
        if (!order) return;

        activeReceiptOrderId = orderId;

        document.getElementById('rcpOrderNum').innerText = order.order_number;
        document.getElementById('rcpTable').innerText = order.table_number ? 'Meja ' + order.table_number : 'Takeaway';
        document.getElementById('rcpCustomer').innerText = order.customer_name;
        
        // REQ 3: Waktu transaksi realtime
        const now = new Date();
        const realtimeStr = now.toLocaleDateString('id-ID') + ' ' + now.toLocaleTimeString('id-ID');
        document.getElementById('rcpRealtimeDate').innerText = realtimeStr;

        document.getElementById('rcpTotalAmount').innerText = formatRupiah(order.total_amount);
        document.getElementById('rcpPayMethod').innerText = (order.payment_method || 'CASH').toUpperCase();
        
        const cashRec = order.cash_received || order.total_amount;
        const cashChg = order.cash_change || 0;
        document.getElementById('rcpCashReceived').innerText = formatRupiah(cashRec);
        document.getElementById('rcpCashChange').innerText = formatRupiah(cashChg);

        // Render Itemized breakdown inside thermal receipt box
        const rcpItemsContainer = document.getElementById('rcpItemsList');
        rcpItemsContainer.innerHTML = order.items.map(item => {
            const sub = item.subtotal || (item.price ? item.price * item.qty : 0);
            return `
                <div class="text-[11px] space-y-0.5">
                    <div class="flex justify-between font-bold">
                        <span>${item.qty}x ${escapeHtml(item.name)}</span>
                        <span>${sub > 0 ? formatRupiah(sub) : ''}</span>
                    </div>
                    ${item.notes ? `<div class="text-[10px] text-stone-500 italic">catatan: ${escapeHtml(item.notes)}</div>` : ''}
                </div>
            `;
        }).join('');

        const modal = document.getElementById('receiptModal');
        modal.classList.remove('opacity-0', 'pointer-events-none');
    }

    function closeReceiptModal() {
        const modal = document.getElementById('receiptModal');
        modal.classList.add('opacity-0', 'pointer-events-none');
    }

    function triggerPrintReceipt() {
        if (!activeReceiptOrderId) return;
        const receiptUrl = `{{ url('cashier/orders') }}/${activeReceiptOrderId}/receipt?print=1`;

        let iframe = document.getElementById('silentPrintIframeDashboard');
        if (!iframe) {
            iframe = document.createElement('iframe');
            iframe.id = 'silentPrintIframeDashboard';
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = '0';
            document.body.appendChild(iframe);
        }

        iframe.onload = function() {
            try {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            } catch (e) {
                console.error('Silent print iframe error:', e);
            }
        };

        iframe.src = receiptUrl;
    }

    /**
     * History & Reprint Modal
     */
    function openHistoryModal() {
        const container = document.getElementById('historyListContainer');
        container.innerHTML = ordersData.map(o => {
            const itemSummary = o.items.map(i => `${i.qty}x ${i.name}`).join(', ');
            return `
                <div class="p-3.5 bg-stone-50 rounded-2xl border border-stone-200/80 flex items-center justify-between gap-3">
                    <div class="space-y-0.5">
                        <div class="font-bold text-xs text-stone-900">${o.order_number} • ${o.table_number ? 'Meja ' + o.table_number : 'Takeaway'} (${escapeHtml(o.customer_name)})</div>
                        <div class="text-[11px] text-amber-900 font-semibold truncate max-w-xs">${escapeHtml(itemSummary)}</div>
                        <div class="text-[10px] text-stone-500 font-medium">Total: ${formatRupiah(o.total_amount)} • ${o.payment_status}</div>
                    </div>
                    <button type="button" onclick="openReceiptModal(${o.id})" class="px-3.5 py-2 rounded-xl bg-stone-900 text-white font-bold text-xs hover:bg-stone-800 transition-all shrink-0 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        <span>Struk</span>
                    </button>
                </div>
            `;
        }).join('');

        const modal = document.getElementById('historyModal');
        modal.classList.remove('opacity-0', 'pointer-events-none');
    }

    function closeHistoryModal() {
        const modal = document.getElementById('historyModal');
        modal.classList.add('opacity-0', 'pointer-events-none');
    }

    /**
     * Toast Helper
     */
    function showToast(msg) {
        const toast = document.getElementById('toast');
        document.getElementById('toastMessage').innerText = msg;
        toast.classList.remove('translate-y-20', 'opacity-0');

        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3500);
    }

    /**
     * Utility Helpers
     */
    function formatRupiah(num) {
        return 'Rp' + Number(num).toLocaleString('id-ID');
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, function(m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
    }

    // Live Real-Time Digital Clock Kasir
    function updateCashierClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour12: false }) + ' WIB';
        const clockEl = document.getElementById('cashierLiveClock');
        if (clockEl) clockEl.innerText = timeStr;
    }
    setInterval(updateCashierClock, 1000);
    updateCashierClock();
</script>
@endpush
