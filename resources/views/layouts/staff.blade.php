<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Portal - Cafe Self-Ordering System')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fdfbf7',
                            100: '#f7f0e6',
                            200: '#eddcc4',
                            300: '#dfc29c',
                            400: '#d1a373',
                            500: '#c58851',
                            600: '#b87243',
                            700: '#995938',
                            800: '#7c4933',
                            900: '#653c2c',
                        }
                    }
                }
            }
        }
    </script>
    @stack('head')
</head>
<body>
    <div style="min-height: 100vh; background: var(--bg-main);">
        <!-- Top Navbar -->
        <header class="staff-navbar">
            <div class="staff-nav-left">
                <div class="staff-nav-brand">
                    <div class="staff-nav-icon">
                        <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path></svg>
                    </div>
                    <div>
                        <div class="staff-nav-title">CAFE STAFF PORTAL</div>
                        <div class="staff-nav-subtitle">System Operasional Cafe</div>
                    </div>
                </div>

                <!-- Portal Navigation Links -->
                <nav class="staff-nav-links">
                    @if(auth()->user() && (auth()->user()->isKasir() || auth()->user()->isAdmin()))
                        <a href="{{ route('cashier.pos') }}" class="nav-portal-btn {{ request()->routeIs('cashier.pos') ? 'active' : '' }}">
                            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                            <span>Kasir POS</span>
                        </a>
                        <a href="{{ route('cashier.dashboard') }}" class="nav-portal-btn {{ request()->routeIs('cashier.dashboard') ? 'active' : '' }}">
                            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                            <span>Daftar Order</span>
                        </a>
                    @endif

                    @if(auth()->user() && (auth()->user()->isDapur() || auth()->user()->isAdmin()))
                        <a href="{{ route('kitchen.dashboard') }}" class="nav-portal-btn {{ request()->routeIs('kitchen.dashboard') ? 'active-kitchen' : '' }}">
                            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path></svg>
                            <span>Kitchen (KDS)</span>
                        </a>
                    @endif
                </nav>
            </div>

            <div class="staff-nav-right">
                <div class="staff-user-info">
                    <div class="staff-user-name">{{ auth()->user()->name ?? 'Staff' }}</div>
                    <div class="staff-user-role">Role: {{ auth()->user()->role ?? 'kasir' }}</div>
                </div>
                @if(auth()->user() && auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-portal-btn">
                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        <span>Dashboard Admin</span>
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" style="font-weight: 700; border-radius: 8px; padding: 8px 16px;">
                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        <main style="padding: 28px 36px 40px 36px;">
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
