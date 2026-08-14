@extends('layouts.admin')

@section('title', 'Kelola Meja & QR Code')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Kelola Meja Cafe & QR Code</h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">Generate QR Code unik untuk setiap meja pelanggan (/order?table=XX)</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 340px 1fr; gap: 24px;">
        <!-- Form Add Table -->
        <div style="background: #ffffff; padding: 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); height: fit-content; box-shadow: var(--shadow-sm);">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--primary); margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Tambah Meja Baru</span>
            </h3>

            <form action="{{ route('admin.tables.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nomor Meja <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="table_number" class="form-control" placeholder="Misal: 11" required>
                    <span style="font-size: 0.72rem; color: var(--text-muted);">Otomatis diformat 2 digit (01, 02, 11)</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama/Area Meja</label>
                    <input type="text" name="name" class="form-control" placeholder="Misal: Meja Outdoor 11">
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <span>Simpan Meja</span>
                </button>
            </form>
        </div>

        <!-- Tables Grid / List -->
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Meja</th>
                        <th>Nama/Area</th>
                        <th>Link Scan QR</th>
                        <th>Total Order</th>
                        <th>Status</th>
                        <th>Aksi & QR Card</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tables as $table)
                        @php
                            $targetUrl = route('customer.menu', ['table' => $table->table_number]);
                        @endphp
                        <tr>
                            <td style="font-weight: 800; font-size: 1.05rem; color: var(--primary);">
                                Meja {{ $table->table_number }}
                            </td>
                            <td style="font-weight: 600;">{{ $table->name ?: 'Meja ' . $table->table_number }}</td>
                            <td>
                                <a href="{{ $targetUrl }}" target="_blank" style="font-size: 0.82rem; color: var(--info); font-family: monospace; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                    <span>/order?table={{ $table->table_number }}</span>
                                    <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                </a>
                            </td>
                            <td style="font-weight: 700;">{{ $table->orders_count }} Transaksi</td>
                            <td>
                                <form action="{{ route('admin.tables.toggle', $table->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $table->status === 'active' ? 'btn-outline' : 'btn-danger' }} btn-sm" style="font-size: 0.75rem;">
                                        {{ strtoupper($table->status) }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.tables.qr', $table->id) }}" class="btn btn-accent btn-sm" target="_blank">
                                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                        <span>Print QR Card</span>
                                    </a>
                                    <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST" onsubmit="return confirm('Hapus meja ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">Belum ada meja terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
