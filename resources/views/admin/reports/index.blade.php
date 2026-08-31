@extends('layouts.admin')

@section('title', 'Rekap Transaksi & Laporan Keuangan - Admin')

@section('content')
<div class="space-y-6 font-sans text-stone-800 antialiased">

    <!-- 1. Modern Header Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-stone-200/90 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-stone-900 text-amber-400 flex items-center justify-center shrink-0 shadow-xs">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-base sm:text-xl font-extrabold tracking-tight text-stone-900">Rekap Transaksi & Laporan Keuangan</h1>
                <p class="text-xs text-stone-500 font-medium mt-0.5">
                    Periode: <span class="font-bold text-stone-800">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</span> s/d <span class="font-bold text-stone-800">{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
                </p>
            </div>
        </div>

        <a href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="px-4 py-2.5 rounded-xl bg-stone-900 hover:bg-stone-800 active:scale-95 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-xs transition-all w-full sm:w-auto">
            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Export Excel (.csv)</span>
        </a>
    </div>

    <!-- 2. Clean Segmented Filter & Custom Date Range Bar -->
    <div class="bg-white p-4 rounded-2xl border border-stone-200/90 shadow-2xs space-y-3.5">
        <!-- Quick Preset Pills -->
        <div class="flex items-center justify-between flex-wrap gap-2 pb-3 border-b border-stone-100">
            <span class="text-[11px] font-extrabold uppercase tracking-wider text-stone-400">Filter Cepat Periode:</span>
            <div class="flex items-center gap-1.5 overflow-x-auto scrollbar-none max-w-full">
                <a href="{{ route('admin.reports.index', ['preset' => 'today']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request('preset') === 'today' || ($startDate === now()->toDateString() && $endDate === now()->toDateString() && !request('preset')) ? 'bg-stone-900 text-white shadow-2xs' : 'bg-stone-100 hover:bg-stone-200 text-stone-700' }}">
                    Hari Ini
                </a>
                <a href="{{ route('admin.reports.index', ['preset' => 'yesterday']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request('preset') === 'yesterday' ? 'bg-stone-900 text-white shadow-2xs' : 'bg-stone-100 hover:bg-stone-200 text-stone-700' }}">
                    Kemarin
                </a>
                <a href="{{ route('admin.reports.index', ['preset' => '7days']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request('preset') === '7days' ? 'bg-stone-900 text-white shadow-2xs' : 'bg-stone-100 hover:bg-stone-200 text-stone-700' }}">
                    7 Hari Terakhir
                </a>
                <a href="{{ route('admin.reports.index', ['preset' => 'month']) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request('preset') === 'month' ? 'bg-stone-900 text-white shadow-2xs' : 'bg-stone-100 hover:bg-stone-200 text-stone-700' }}">
                    Bulan Ini
                </a>
            </div>
        </div>

        <!-- Custom Date Range Form -->
        <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 items-center">
            <div class="flex items-center gap-2 bg-stone-50 px-3 py-2 rounded-xl border border-stone-200 text-xs">
                <span class="text-stone-400 font-medium">Dari:</span>
                <input type="date" name="start_date" value="{{ $startDate }}" class="bg-transparent font-bold text-stone-900 focus:outline-none cursor-pointer w-full">
            </div>

            <div class="flex items-center gap-2 bg-stone-50 px-3 py-2 rounded-xl border border-stone-200 text-xs">
                <span class="text-stone-400 font-medium">S/D:</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="bg-transparent font-bold text-stone-900 focus:outline-none cursor-pointer w-full">
            </div>

            <button type="submit" class="w-full py-2 rounded-xl bg-amber-700 hover:bg-amber-800 active:scale-95 text-white text-xs font-bold transition-all shadow-2xs flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <span>Terapkan Filter</span>
            </button>
        </form>
    </div>

    <!-- 3. Metric Summary Cards (2-Columns on Mobile/Tablet for Maximum Neatness) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Card 1: Total Omset -->
        <div class="p-4 sm:p-5 rounded-2xl bg-stone-900 text-white border border-stone-800 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider text-amber-400">Total Omset</span>
                <span class="px-2 py-0.5 rounded text-[9px] sm:text-[10px] font-black bg-stone-800 text-stone-300 border border-stone-700">LUNAS</span>
            </div>
            <div class="mt-3">
                <div class="text-xl sm:text-2xl font-black tracking-tight text-white">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <p class="text-[10px] sm:text-[11px] text-stone-400 font-medium mt-1 truncate">Pembayaran berhasil</p>
            </div>
        </div>

        <!-- Card 2: Total Transaksi -->
        <div class="p-4 sm:p-5 rounded-2xl bg-white border border-stone-200/90 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider text-stone-500">Total Transaksi</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-stone-100 text-stone-800 border border-stone-200">{{ number_format($totalOrders) }}</span>
            </div>
            <div class="mt-3">
                <div class="text-xl sm:text-2xl font-black tracking-tight text-stone-900">{{ number_format($totalOrders) }} <span class="text-xs sm:text-sm font-bold text-stone-400">Order</span></div>
                <p class="text-[10px] sm:text-[11px] text-stone-500 font-medium mt-1 truncate">Rata-rata: <strong class="text-stone-800">Rp{{ number_format($avgOrderValue, 0, ',', '.') }}</strong></p>
            </div>
        </div>

        <!-- Card 3: Cash Revenue -->
        <div class="p-4 sm:p-5 rounded-2xl bg-white border border-stone-200/90 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider text-emerald-700">Omset Cash</span>
                <span class="px-2 py-0.5 rounded text-[9px] sm:text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">TUNAI</span>
            </div>
            <div class="mt-3">
                <div class="text-xl sm:text-2xl font-black tracking-tight text-emerald-950">Rp{{ number_format($cashRevenue, 0, ',', '.') }}</div>
                <p class="text-[10px] sm:text-[11px] text-emerald-700 font-bold mt-1">
                    {{ $totalRevenue > 0 ? number_format(($cashRevenue / $totalRevenue) * 100, 1) : 0 }}% dari omset
                </p>
            </div>
        </div>

        <!-- Card 4: QRIS Revenue -->
        <div class="p-4 sm:p-5 rounded-2xl bg-white border border-stone-200/90 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider text-sky-700">Omset QRIS</span>
                <span class="px-2 py-0.5 rounded text-[9px] sm:text-[10px] font-bold bg-sky-50 text-sky-800 border border-sky-200">QRIS</span>
            </div>
            <div class="mt-3">
                <div class="text-xl sm:text-2xl font-black tracking-tight text-sky-950">Rp{{ number_format($qrisRevenue, 0, ',', '.') }}</div>
                <p class="text-[10px] sm:text-[11px] text-sky-700 font-bold mt-1">
                    {{ $totalRevenue > 0 ? number_format(($qrisRevenue / $totalRevenue) * 100, 1) : 0 }}% dari omset
                </p>
            </div>
        </div>
    </div>

    <!-- 4. Rekap Penjualan Per Menu -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-2xs p-5 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-stone-100">
            <div>
                <h2 class="text-base font-bold text-stone-900">Top 8 Rekap Penjualan Menu Terlaris</h2>
                <p class="text-xs text-stone-500">Peringkat 8 porsi terjual terbanyak dan total omset per produk</p>
            </div>
            <span class="text-xs font-bold text-amber-900 bg-amber-100/80 px-2.5 py-1 rounded-lg border border-amber-200">Top {{ $itemReport->count() }} Menu</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[550px] text-left text-xs">
                <thead>
                    <tr class="bg-stone-50 text-stone-500 font-bold uppercase text-[10px] tracking-wider border-b border-stone-200/70">
                        <th class="py-3 px-3">Rank / Menu</th>
                        <th class="py-3 px-3">Kategori</th>
                        <th class="py-3 px-3 text-center">Porsi Terjual</th>
                        <th class="py-3 px-3 text-right">Total Sales</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($itemReport as $index => $item)
                        <tr class="hover:bg-stone-50/60 transition-colors">
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-5 h-5 rounded-full {{ $index === 0 ? 'bg-amber-500 text-white font-black' : 'bg-stone-100 text-stone-600 font-bold' }} text-[10px] flex items-center justify-center shrink-0">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="font-bold text-stone-900">{{ $item->product->name ?? 'Menu' }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-stone-100 text-stone-700 border border-stone-200">
                                    {{ $item->product->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center font-extrabold text-stone-800">
                                {{ number_format($item->total_qty) }} porsi
                            </td>
                            <td class="py-3 px-3 text-right font-black text-stone-900">
                                Rp{{ number_format($item->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-stone-400 font-medium">Belum ada penjualan menu pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 5. Main Table: Rekap Log Transaksi Lunas -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-2xs p-5 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-stone-100">
            <div>
                <h2 class="text-base font-bold text-stone-900">Log Rekap Transaksi Lunas</h2>
                <p class="text-xs text-stone-500">Daftar semua transaksi berhasil (Menampilkan 10 data per halaman)</p>
            </div>
            <div class="text-xs font-medium text-stone-500">
                Menampilkan <span class="font-bold text-stone-900">{{ $orders->firstItem() ?? 0 }}</span> - <span class="font-bold text-stone-900">{{ $orders->lastItem() ?? 0 }}</span> dari <span class="font-bold text-stone-900">{{ $orders->total() }}</span> Transaksi
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[680px] text-left text-xs">
                <thead>
                    <tr class="bg-stone-50 text-stone-500 font-bold uppercase text-[10px] tracking-wider border-b border-stone-200/70">
                        <th class="py-3.5 px-3">No. Order / Waktu</th>
                        <th class="py-3.5 px-3">Pelanggan & Tipe</th>
                        <th class="py-3.5 px-3">Rincian Menu Pesanan</th>
                        <th class="py-3.5 px-3">Metode & Status</th>
                        <th class="py-3.5 px-3 text-right">Total Pembayaran</th>
                        <th class="py-3.5 px-3 text-center">Detail Struk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-stone-50/80 transition-colors">
                            <!-- Order & Timestamp -->
                            <td class="py-3.5 px-3 align-top">
                                <div class="font-black text-stone-900 text-sm">{{ $ord->order_number }}</div>
                                <div class="text-[11px] text-stone-500 font-medium mt-0.5">{{ $ord->created_at->format('d/m/Y, H:i') }} WIB</div>
                            </td>

                            <!-- Pelanggan & Tipe Meja -->
                            <td class="py-3.5 px-3 align-top space-y-1">
                                <div class="font-bold text-stone-900">{{ $ord->customer_name }}</div>
                                <div>
                                    @if($ord->table)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-stone-100 text-stone-800 border border-stone-200">
                                            Meja {{ $ord->table->table_number }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-900 border border-amber-200">
                                            Takeaway
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Items Summary (Compact Sleek Summary) -->
                            <td class="py-3 px-4 align-middle">
                                @php
                                    $itemCount = $ord->items->count();
                                    $firstItem = $ord->items->first();
                                    $totalQty = $ord->items->sum('quantity');
                                @endphp
                                <div class="bg-stone-50/90 p-2 rounded-lg border border-stone-200/70 max-w-xs space-y-1 group-hover:bg-white transition-colors shadow-2xs">
                                    @if($firstItem)
                                        <div class="flex items-center justify-between text-[11px] font-bold text-stone-900">
                                            <div class="flex items-center gap-1.5 truncate">
                                                <span class="w-4 h-4 rounded bg-stone-200/80 text-stone-800 text-[10px] font-black flex items-center justify-center shrink-0">{{ $firstItem->quantity }}x</span>
                                                <span class="truncate font-bold text-stone-900">{{ $firstItem->product->name ?? 'Menu' }}</span>
                                            </div>
                                            <span class="text-stone-600 font-extrabold text-[10px] shrink-0 ml-2">Rp{{ number_format($firstItem->subtotal ?? ($firstItem->price * $firstItem->quantity), 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @if($itemCount > 1)
                                        <div class="pt-1 border-t border-stone-200/60 flex items-center justify-between text-[10px]">
                                            <span class="font-extrabold text-amber-900 bg-amber-100/90 px-2 py-0.5 rounded-md border border-amber-200/80">
                                                +{{ $itemCount - 1 }} menu lainnya ({{ $totalQty }} porsi)
                                            </span>
                                            <span class="text-[10px] text-stone-400 font-medium italic">Lihat Struk</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Payment Method & Status -->
                            <td class="py-3.5 px-3 align-top space-y-1">
                                <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-extrabold bg-stone-900 text-white shadow-2xs">
                                    {{ strtoupper($ord->payment_method) }}
                                </span>
                                <div>
                                    <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-900 border border-emerald-200">
                                        LUNAS
                                    </span>
                                </div>
                            </td>

                            <!-- Total Amount -->
                            <td class="py-3.5 px-3 align-top text-right">
                                <div class="text-base font-black text-stone-900 tracking-tight">Rp{{ number_format($ord->total_amount, 0, ',', '.') }}</div>
                            </td>

                            <!-- Struk Action Modal Trigger (3-Dots Kebab Icon) -->
                            <td class="py-3 px-3 align-middle text-center">
                                <button type="button" onclick="showReceiptDetail({{ json_encode($ord) }})" title="Detail Struk" class="w-8 h-8 rounded-lg bg-stone-100 hover:bg-stone-900 text-stone-600 hover:text-amber-400 border border-stone-200/80 flex items-center justify-center mx-auto transition-all shadow-2xs group-hover:border-stone-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-stone-400 font-medium">
                                Tidak ada transaksi lunas ditemukan pada rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination Link Bar (Max 10 data per page) -->
        <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
            <div class="text-xs text-stone-500 font-medium">
                Halaman <span class="font-bold text-stone-900">{{ $orders->currentPage() }}</span> dari <span class="font-bold text-stone-900">{{ $orders->lastPage() }}</span>
            </div>
            <div>
                {{ $orders->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

</div>

<!-- Thermal Receipt Preview Modal for Admin -->
<div id="adminReceiptModal" class="fixed inset-0 bg-stone-900/75 backdrop-blur-sm z-[999] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white rounded-3xl max-w-sm w-full p-5 shadow-2xl border border-stone-100 transform scale-95 transition-transform duration-200 flex flex-col max-h-[85vh] my-auto">
        <div class="flex items-center justify-between pb-3 border-b border-stone-100 shrink-0">
            <h3 class="text-base font-black text-stone-900">Detail Struk Pembayaran</h3>
            <button type="button" onclick="closeAdminReceiptModal()" class="w-7 h-7 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-500 flex items-center justify-center font-bold text-sm">
                &times;
            </button>
        </div>

        <div class="flex-1 overflow-y-auto my-3 pr-1 space-y-2 font-mono text-xs text-stone-900 bg-stone-50 p-4 rounded-2xl border border-stone-200">
            <div class="text-center space-y-0.5 pb-2 border-b border-dashed border-stone-300">
                <div class="font-black text-sm">MEJA KOPI</div>
                <div class="text-[10px] text-stone-500 font-sans">Rekap Transaksi Lunas</div>
            </div>
            <div class="text-[11px] py-1 border-b border-dashed border-stone-300 space-y-1 font-sans">
                <div class="flex justify-between"><span class="text-stone-500">Order:</span><strong id="admRcpOrder">#0001</strong></div>
                <div class="flex justify-between"><span class="text-stone-500">Tipe / Meja:</span><strong id="admRcpTable">Meja 01</strong></div>
                <div class="flex justify-between"><span class="text-stone-500">Pelanggan:</span><strong id="admRcpCustomer">apep</strong></div>
                <div class="flex justify-between"><span class="text-stone-500">Waktu:</span><strong id="admRcpDate">31/08/2026</strong></div>
            </div>
            <div class="py-1 border-b border-dashed border-stone-300 space-y-1 font-sans" id="admRcpItems">
                <!-- JS populated -->
            </div>
            <div class="text-[11px] pt-1 space-y-1 font-sans">
                <div class="flex justify-between font-black text-xs text-stone-900">
                    <span>Total Tagihan:</span>
                    <span id="admRcpTotal">Rp0</span>
                </div>
                <div class="flex justify-between text-stone-600">
                    <span>Metode Pay:</span>
                    <span id="admRcpMethod" class="font-bold">CASH</span>
                </div>
            </div>
        </div>

        <button type="button" onclick="closeAdminReceiptModal()" class="w-full py-2.5 rounded-xl bg-stone-900 text-white font-bold text-xs hover:bg-stone-800 transition-all">
            Tutup Preview
        </button>
    </div>
</div>

<script>
    function showReceiptDetail(order) {
        document.getElementById('admRcpOrder').innerText = order.order_number;
        document.getElementById('admRcpTable').innerText = order.table ? 'Meja ' + order.table.table_number : 'Takeaway';
        document.getElementById('admRcpCustomer').innerText = order.customer_name;
        document.getElementById('admRcpDate').innerText = order.created_at ? order.created_at.substring(0, 16).replace('T', ' ') : '-';
        document.getElementById('admRcpTotal').innerText = 'Rp ' + Number(order.total_amount).toLocaleString('id-ID');
        document.getElementById('admRcpMethod').innerText = (order.payment_method || 'CASH').toUpperCase();

        const itemsContainer = document.getElementById('admRcpItems');
        itemsContainer.innerHTML = (order.items || []).map(i => `
            <div class="flex justify-between text-xs py-0.5">
                <span>${i.quantity}x ${i.product ? i.product.name : 'Menu'}</span>
                <span class="font-bold">Rp ${(i.subtotal || (i.price * i.quantity)).toLocaleString('id-ID')}</span>
            </div>
        `).join('');

        const modal = document.getElementById('adminReceiptModal');
        modal.classList.remove('opacity-0', 'pointer-events-none');
    }

    function closeAdminReceiptModal() {
        const modal = document.getElementById('adminReceiptModal');
        modal.classList.add('opacity-0', 'pointer-events-none');
    }
</script>
@endsection
