@extends('layouts.staff')

@section('title', 'Layar Dapur & Barista (KDS) - Meja Kopi')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 font-sans antialiased text-stone-800">

    <!-- Header Bar Dapur -->
    <div class="bg-stone-900 text-white p-5 rounded-2xl shadow-md flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border border-stone-800">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-amber-600 flex items-center justify-center text-white shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-white tracking-tight">Antrean Dapur & Barista</h1>
                <p class="text-xs text-stone-400 font-medium mt-0.5">Daftar pesanan masuk yang perlu dimasak dan disajikan</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-stone-800 px-3.5 py-1.5 rounded-xl border border-stone-700 text-xs font-bold text-amber-400">
                <span id="activeKitchenCount">{{ $orders->count() }}</span> Pesanan Masuk
            </div>
        </div>
    </div>

    <!-- Active Orders Grid for Kitchen (Max 4 Columns Across) -->
    <div id="kitchenGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($orders as $order)
            @php
                $isProcessing = $order->order_status === 'PROCESSING';
                $cardBorder = $isProcessing ? 'border-amber-400 ring-2 ring-amber-400/20' : 'border-stone-200';
            @endphp

            <div class="bg-white rounded-2xl border {{ $cardBorder }} order-card-hover flex flex-col justify-between overflow-hidden">
                <!-- Header Tiket Dapur (Simple Clean Layout) -->
                <div class="p-4 bg-stone-50/80 border-b border-stone-200/80 space-y-1">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-stone-900 tracking-tight">
                            {{ $order->table ? 'MEJA ' . sprintf('%02d', $order->table->table_number) : 'TAKEAWAY' }}
                        </h2>
                        @if($isProcessing)
                            <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full font-bold text-xs border border-amber-200">
                                PROSES
                            </span>
                        @else
                            <span class="px-3 py-1 bg-sky-50 text-sky-500 rounded-full font-bold text-xs border border-sky-200">
                                BARU MASUK
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between text-xs text-stone-500 font-medium pt-0.5">
                        <div class="truncate">
                            <span>{{ $order->customer_name }}</span>
                            <span class="mx-1">•</span>
                            <span>{{ $order->order_number }}</span>
                        </div>
                        <span class="shrink-0 ml-2">
                            {{ $order->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>

                <!-- Body Tiket (Daftar Menu & Catatan) -->
                <div class="p-4 space-y-3 flex-1">
                    <div class="space-y-2 max-h-[220px] overflow-y-auto pr-1 scrollbar-thin">
                        @foreach($order->items as $item)
                            <div class="p-2.5 rounded-xl bg-stone-50 border border-stone-200/80 space-y-1">
                                <div class="flex items-start gap-2">
                                    <span class="px-2 py-0.5 bg-stone-900 text-white font-bold text-xs rounded-md shrink-0">
                                        {{ $item->quantity }}x
                                    </span>
                                    <span class="font-semibold text-sm text-stone-900 leading-snug">
                                        {{ $item->product->name ?? 'Menu Produk' }}
                                    </span>
                                </div>
                                @if($item->notes)
                                    <div class="mt-1 text-xs text-amber-900 bg-amber-50 border border-amber-200 p-1.5 rounded-lg font-medium">
                                        Catatan: {{ $item->notes }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tombol Aksi Dapur -->
                <div class="p-3 bg-stone-50 border-t border-stone-200/80">
                    @if($order->order_status === 'WAITING_KITCHEN')
                        <form action="{{ route('kitchen.orders.process', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs uppercase flex items-center justify-center gap-2 shadow-xs transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                                <span>Mulai Proses Masak</span>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('kitchen.orders.complete', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase flex items-center justify-center gap-2 shadow-xs transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Selesai & Sajikan</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-dashed border-stone-300 p-8">
                <div class="w-12 h-12 rounded-xl bg-stone-100 text-stone-500 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-stone-800">Tidak ada antrean pesanan di Dapur</h3>
                <p class="text-xs text-stone-500 mt-1 font-medium">Pesanan yang dikirim dari kasir/pelanggan akan otomatis muncul di sini</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>

    // Auto Refresh Feed Periodically
    setInterval(function() {
        fetch(window.location.href, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            const currentCount = Number(document.getElementById('activeKitchenCount')?.innerText || 0);
            if (data.orders && data.orders.length !== currentCount) {
                window.location.reload();
            }
        }).catch(err => {});
    }, 4000);
</script>
@endpush
