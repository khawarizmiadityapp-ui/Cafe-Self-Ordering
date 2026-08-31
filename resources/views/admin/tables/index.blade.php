@extends('layouts.admin')

@section('title', 'Kelola Meja & QR Code')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Kelola Meja & QR Code</h1>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 2px;">Generate QR Code unik untuk setiap meja pelanggan</p>
        </div>
    </div>

    <div class="admin-split-grid">
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
        <div style="background: #ffffff; border-radius: var(--radius-md); border: 1px solid var(--border-color); padding: 16px; box-shadow: var(--shadow-sm); min-width: 0;">
            <div class="table-responsive" style="border: none; box-shadow: none;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. Meja</th>
                            <th>Nama/Area</th>
                            <th>Link Scan QR</th>
                            <th>Total Order</th>
                            <th>Status</th>
                            <th style="text-align: center;">Aksi & QR Card</th>
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
                                <td style="text-align: center;">
                                    <div class="action-dropdown">
                                        <button type="button" class="btn-dots" onclick="toggleKebabMenu(this, event)" title="Aksi Meja">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                        </button>
                                        <div class="action-dropdown-menu">
                                            <a href="{{ route('admin.tables.qr', $table->id) }}" class="action-dropdown-item" target="_blank">
                                                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                                <span>Print QR Card</span>
                                            </a>
                                            <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus meja ini?')" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-dropdown-item item-danger">
                                                    <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                    <span>Hapus Meja</span>
                                                </button>
                                            </form>
                                        </div>
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

            <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid var(--border-color);">
                {{ $tables->links() }}
            </div>
        </div>
    </div>
@endsection
