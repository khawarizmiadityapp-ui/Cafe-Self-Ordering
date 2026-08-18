<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Portal - Cafe Self-Ordering System')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        <main style="padding: 32px 36px;">
            @if(session('success'))
                <div class="alert alert-success">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-success" style="background: var(--info-bg); color: var(--info); border-color: #b2ebf2;">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
