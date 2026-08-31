@extends('layouts.admin')

@section('title', 'Kelola Produk Menu - Admin')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Kelola Menu Produk</h1>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 2px;">Tambah, edit harga, foto, atau ketersediaan menu</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary page-header-btn">
            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Tambah Menu</span>
        </a>
    </div>

    <!-- Category Filter Segmented Bar -->
    <div style="background: #ffffff; padding: 12px 14px; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 18px; box-shadow: var(--shadow-xs);">
        <div style="font-size: 0.72rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Filter Kategori Menu</div>
        <div style="display: flex; gap: 6px; overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 2px; scrollbar-width: none;">
            <a href="{{ route('admin.products.index') }}" class="btn {{ !$selectedCategory ? 'btn-primary' : 'btn-outline' }} btn-sm" style="white-space: nowrap; font-size: 0.78rem;">Semua Menu</a>
            @foreach($categories as $cat)
                <a href="{{ route('admin.products.index', ['category_id' => $cat->id]) }}" class="btn {{ $selectedCategory == $cat->id ? 'btn-primary' : 'btn-outline' }} btn-sm" style="white-space: nowrap; font-size: 0.78rem;">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>

    <div style="background: #ffffff; border-radius: var(--radius-md); border: 1px solid var(--border-color); padding: 16px; box-shadow: var(--shadow-sm); min-width: 0;">
        <div class="table-responsive" style="border: none; box-shadow: none;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status Ketersediaan</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 54px; height: 54px; border-radius: 10px; object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('images/coffee-default.svg') }}'">
                            </td>
                            <td style="font-weight: 700;">
                                <div style="color: var(--text-dark); font-size: 0.98rem;">{{ $product->name }}</div>
                                <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 400; max-width: 320px;">{{ $product->description }}</div>
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ $product->category->name ?? '-' }}</span>
                            </td>
                            <td style="font-weight: 800; color: var(--primary); font-size: 1rem;">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td>
                                <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $product->is_available ? 'btn-outline' : 'btn-danger' }} btn-sm" style="padding: 6px 12px; font-size: 0.78rem;">
                                        @if($product->is_available)
                                            <svg class="svg-icon svg-icon-sm" style="color: var(--success);" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                            <span>TERSEDIA (Klik matikan)</span>
                                        @else
                                            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                            <span>TIDAK TERSEDIA (Klik aktifkan)</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-dropdown">
                                    <button type="button" class="btn-dots" onclick="toggleKebabMenu(this, event)" title="Aksi Menu Produk">
                                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                    </button>
                                    <div class="action-dropdown-menu">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="action-dropdown-item">
                                            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            <span>Edit Menu</span>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu produk ini?')" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-dropdown-item item-danger">
                                                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                <span>Hapus Menu</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">Belum ada menu produk terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid var(--border-color);">
            {{ $products->links() }}
        </div>
    </div>
@endsection
