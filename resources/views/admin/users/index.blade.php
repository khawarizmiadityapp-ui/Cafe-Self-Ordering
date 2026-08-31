@extends('layouts.admin')

@section('title', 'Kelola User Staff')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Kelola User Staff</h1>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 2px;">Kelola hak akses login Admin, Kasir, dan Dapur</p>
        </div>
    </div>

    <div class="admin-split-grid">
        <!-- Create User Form -->
        <div style="background: #ffffff; padding: 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); height: fit-content; box-shadow: var(--shadow-sm);">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--primary); margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="17" y1="11" x2="23" y2="11"></line></svg>
                <span>Tambah Akun Staff</span>
            </h3>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Staff <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Misal: Andi Kasir" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span style="color: var(--danger);">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="andi@cafe.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password <span style="color: var(--danger);">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                </div>

                <div class="form-group">
                    <label class="form-label">Role / Hak Akses <span style="color: var(--danger);">*</span></label>
                    <select name="role" class="form-control" required>
                        <option value="kasir">KASIR</option>
                        <option value="dapur">DAPUR / BARISTA</option>
                        <option value="admin">ADMIN</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <span>Simpan User Staff</span>
                </button>
            </form>
        </div>

        <!-- Users List Table -->
        <div style="background: #ffffff; border-radius: var(--radius-md); border: 1px solid var(--border-color); padding: 16px; box-shadow: var(--shadow-sm); min-width: 0;">
            <div class="table-responsive" style="border: none; box-shadow: none;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Staff</th>
                            <th>Email</th>
                            <th>Role Hak Akses</th>
                            <th>Dibuat Pada</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $usr)
                            <tr>
                                <td style="font-weight: 700;">{{ $usr->name }}</td>
                                <td>{{ $usr->email }}</td>
                                <td>
                                    @php
                                        $roleCls = match($usr->role) {
                                            'admin' => 'badge-danger',
                                            'kasir' => 'badge-primary',
                                            'dapur' => 'badge-warning',
                                            default => 'badge-info',
                                        };
                                    @endphp
                                    <span class="badge {{ $roleCls }}">{{ strtoupper($usr->role) }}</span>
                                </td>
                                <td style="font-size: 0.85rem; color: var(--text-muted);">{{ $usr->created_at->format('d M Y, H:i') }}</td>
                                <td style="text-align: center;">
                                    @if($usr->id !== auth()->id())
                                        <div class="action-dropdown">
                                            <button type="button" class="btn-dots" onclick="toggleKebabMenu(this, event)" title="Aksi User Staff">
                                                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                            </button>
                                            <div class="action-dropdown-menu">
                                                <form action="{{ route('admin.users.destroy', $usr->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun staff ini?')" style="margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-dropdown-item item-danger">
                                                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                        <span>Hapus Akun</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <span style="font-size: 0.78rem; color: var(--text-muted); font-style: italic;">(Akun Anda Saat Ini)</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">Belum ada user terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid var(--border-color);">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
