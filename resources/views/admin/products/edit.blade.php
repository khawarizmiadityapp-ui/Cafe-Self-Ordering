@extends('layouts.admin')

@section('title', 'Edit Menu - ' . $product->name)

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Menu Produk</h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">Ubah informasi, harga, atau ketersediaan menu</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline">
            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            <span>Kembali</span>
        </a>
    </div>

    <div style="background: #ffffff; padding: 28px; border-radius: var(--radius-md); border: 1px solid var(--border-color); max-width: 680px; box-shadow: var(--shadow-sm);">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Kategori <span style="color: var(--danger);">*</span></label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Menu <span style="color: var(--danger);">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Harga (Rp) <span style="color: var(--danger);">*</span></label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" min="0" step="500" required>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Menu</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">URL Foto Menu</label>
                <input type="url" name="image_url" class="form-control" value="{{ old('image_url', $product->image) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Atau Upload Foto Baru</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px; font-weight: 700; cursor: pointer;">
                    <input type="checkbox" name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }} style="width: 18px; height: 18px;">
                    <span>Tersedia untuk Dipesan (Available)</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 1rem;">
                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                <span>Perbarui Menu</span>
            </button>
        </form>
    </div>
@endsection
