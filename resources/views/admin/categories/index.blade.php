@extends('layouts.admin')

@section('title', 'Kelola Kategori Menu')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Kelola Kategori Menu</h1>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 2px;">Kelola pengelompokan menu (Kopi, Makanan, Snack, dll)</p>
        </div>
    </div>

    <div class="admin-split-grid">
        <!-- Form Create Category -->
        <div style="background: #ffffff; padding: 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); height: fit-content; box-shadow: var(--shadow-sm);">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--primary); margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Tambah Kategori</span>
            </h3>

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Kategori <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Misal: Dessert" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Urutan Tampil (Sort Order)</label>
                    <input type="number" name="sort_order" class="form-control" value="0">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Kategori</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Keterangan singkat"></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <span>Simpan Kategori</span>
                </button>
            </form>
        </div>

        <!-- Categories Table -->
        <div style="background: #ffffff; border-radius: var(--radius-md); border: 1px solid var(--border-color); padding: 16px; box-shadow: var(--shadow-sm); min-width: 0;">
            <div class="table-responsive" style="border: none; box-shadow: none;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Urutan</th>
                            <th>Nama Kategori</th>
                            <th>Jumlah Produk</th>
                            <th>Status</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td style="font-weight: 800;">#{{ $category->sort_order }}</td>
                                <td style="font-weight: 700;">
                                    <div style="color: var(--text-dark); font-size: 0.98rem;">{{ $category->name }}</div>
                                    <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 400;">{{ $category->description }}</div>
                                </td>
                                <td style="font-weight: 800; color: var(--accent-dark);">
                                    {{ $category->products_count }} Menu
                                </td>
                                <td>
                                    <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $category->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <div class="action-dropdown">
                                        <button type="button" class="btn-dots" onclick="toggleKebabMenu(this, event)" title="Aksi Kategori">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                        </button>
                                        <div class="action-dropdown-menu">
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori menu ini?')" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-dropdown-item item-danger">
                                                    <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                    <span>Hapus Kategori</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">Belum ada kategori terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid var(--border-color);">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@endsection
