@extends('layouts.admin')

@section('title', 'Kelola Kategori Menu')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Kelola Kategori Menu</h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">Kelola pengelompokan menu (Kopi, Non-Kopi, Makanan, Snack, dll)</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 340px 1fr; gap: 24px;">
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
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Produk</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
                            <td>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        <span>Hapus</span>
                                    </button>
                                </form>
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
    </div>
@endsection
