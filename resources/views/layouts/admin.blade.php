<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Admin Panel - Cafe Self-Ordering System')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Mobile Navigation Top Bar (< 1024px) -->
    <header class="admin-mobile-header">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button type="button" onclick="toggleAdminSidebar()" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #ffffff; padding: 8px 10px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #d4a373 0%, #b88252 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ffffff;">
                    <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path></svg>
                </div>
                <div>
                    <div style="font-size: 0.85rem; font-weight: 800; color: #ffffff; line-height: 1.1;">KAFE DIGITAL</div>
                    <div style="font-size: 0.65rem; color: var(--accent); font-weight: 600; text-transform: uppercase;">Admin Portal</div>
                </div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--accent); color: #ffffff; font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; justify-content: center;">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
        </div>
    </header>

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="adminSidebarOverlay" class="admin-sidebar-overlay" onclick="toggleAdminSidebar()"></div>

    <div class="staff-wrapper">
        <!-- Sidebar Navigation (Desktop Fixed & Mobile Slide-out Drawer) -->
        <aside class="sidebar" id="adminSidebar">
            <div class="sidebar-nav-body">
                <div class="sidebar-brand" style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="sidebar-brand-icon">
                            <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24">
                                <path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path>
                            </svg>
                        </div>
                        <div>
                            <div style="line-height: 1.1; font-weight: 800;">KAFE DIGITAL</div>
                            <div style="font-size: 0.72rem; color: var(--accent); font-weight: 600; text-transform: uppercase;">Admin Portal</div>
                        </div>
                    </div>
                    <button type="button" onclick="toggleAdminSidebar()" class="admin-drawer-close-btn" style="background: none; border: none; color: #a09083; font-size: 1.5rem; cursor: pointer; padding: 4px;" aria-label="Tutup Menu">
                        &times;
                    </button>
                </div>

                <div class="sidebar-label">Menu Utama</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            <span>Kelola Menu</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                            <span>Kelola Kategori</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tables.index') }}" class="sidebar-link {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                            <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24">
                                <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                                <rect x="9" y="9" width="6" height="6"></rect>
                                <line x1="9" y1="1" x2="9" y2="4"></line>
                                <line x1="15" y1="1" x2="15" y2="4"></line>
                            </svg>
                            <span>Meja & QR Code</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Kelola User Staff</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px; min-width: 20px;">
                                <path d="M3 3v18h18M18 17V9M13 17V5M8 17v-3"></path>
                            </svg>
                            <span>Rekap & Laporan Transaksi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px; min-width: 20px;">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            <span>Pengaturan Toko & Struk</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-label">Akses Portal Staff</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('cashier.dashboard') }}" class="sidebar-link">
                            <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            <span>Portal Kasir</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kitchen.dashboard') }}" class="sidebar-link">
                            <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24">
                                <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path>
                            </svg>
                            <span>Portal Dapur</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <div class="sidebar-user-card">
                    <div class="sidebar-user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name">{{ auth()->user()->name ?? 'Administrator' }}</div>
                        <div class="sidebar-user-role">System Admin</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm btn-block" style="font-weight: 700; border-radius: 8px;">
                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Keluar System</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content View -->
        <main class="staff-content">
            <!-- Global Top Right Toast Notification Container -->
            <div class="toast-popup-container">
                @if(session('success'))
                    <div class="toast-popup toast-success">
                        <div class="toast-icon-wrap">
                            <svg class="checkmark-animated" viewBox="0 0 52 52">
                                <circle class="checkmark-circle" cx="26" cy="26" r="23" fill="none"/>
                                <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                            </svg>
                        </div>
                        <div class="toast-body">
                            <div class="toast-title">BERHASIL</div>
                            <div class="toast-message">{{ session('success') }}</div>
                        </div>
                        <button type="button" class="toast-close" onclick="closeFlashToast(this)">✕</button>
                        <div class="toast-progress-bar"></div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="toast-popup toast-error">
                        <div class="toast-icon-wrap">
                            <svg class="svg-icon" style="color: #ef4444; width: 28px; height: 28px;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        </div>
                        <div class="toast-body">
                            <div class="toast-title" style="color: #ef4444;">GAGAL / ERROR</div>
                            <div class="toast-message">{{ session('error') }}</div>
                        </div>
                        <button type="button" class="toast-close" onclick="closeFlashToast(this)">✕</button>
                        <div class="toast-progress-bar" style="background: linear-gradient(90deg, #ef4444, #f87171);"></div>
                    </div>
                @endif
                @if(session('info'))
                    <div class="toast-popup toast-info">
                        <div class="toast-icon-wrap">
                            <svg class="svg-icon" style="color: #3b82f6; width: 28px; height: 28px;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        </div>
                        <div class="toast-body">
                            <div class="toast-title" style="color: #3b82f6;">INFORMASI</div>
                            <div class="toast-message">{{ session('info') }}</div>
                        </div>
                        <button type="button" class="toast-close" onclick="closeFlashToast(this)">✕</button>
                        <div class="toast-progress-bar" style="background: linear-gradient(90deg, #3b82f6, #60a5fa);"></div>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>
    </div>

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('adminSidebarOverlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
            }
        }

        function toggleKebabMenu(btn, e) {
            e.stopPropagation();
            const dropdown = btn.closest('.action-dropdown');
            const isOpen = dropdown.classList.contains('open');

            document.querySelectorAll('.action-dropdown.open').forEach(d => {
                if (d !== dropdown) d.classList.remove('open');
            });

            if (isOpen) {
                dropdown.classList.remove('open');
            } else {
                dropdown.classList.add('open');
            }
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.action-dropdown')) {
                document.querySelectorAll('.action-dropdown.open').forEach(d => d.classList.remove('open'));
            }
        });

        function closeFlashToast(btn) {
            const toast = btn.closest('.toast-popup');
            if (toast) {
                toast.style.animation = 'toastSlideOutRight 0.4s ease forwards';
                setTimeout(function() { toast.remove(); }, 400);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const toasts = document.querySelectorAll('.toast-popup');
                toasts.forEach(function(t) {
                    t.style.animation = 'toastSlideOutRight 0.4s ease forwards';
                    setTimeout(function() { t.remove(); }, 400);
                });
            }, 4500);
        });
    </script>
    @stack('scripts')
</body>
</html>
