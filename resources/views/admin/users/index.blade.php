@extends('layouts.admin')

@section('title', 'Kelola User Staff')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Kelola Akun Pengguna Staff</h1>
            <p style="font-size: 0.9rem; color: var(--text-muted);">Kelola hak akses login untuk Admin, Kasir, dan Barista/Dapur</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 340px 1fr; gap: 24px;">
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
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Staff</th>
                        <th>Email</th>
                        <th>Role Hak Akses</th>
                        <th>Dibuat Pada</th>
                        <th>Aksi</th>
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
                            <td>
                                @if($usr->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $usr->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
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
    </div>
@endsection
