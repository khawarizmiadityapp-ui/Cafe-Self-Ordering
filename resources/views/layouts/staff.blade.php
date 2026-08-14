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
        <header style="background: var(--sidebar-bg); border-bottom: 1px solid var(--border-dark); color: #fff; padding: 18px 36px; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-sm);">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 38px; height: 38px; background: linear-gradient(135deg, #d4a373 0%, #b88252 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff;">
                    <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path></svg>
                </div>
                <div>
                    <div style="font-size: 1.2rem; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">CAFE STAFF PORTAL</div>
                    <div style="font-size: 0.75rem; color: var(--accent); font-weight: 600;">System Operasional Cafe</div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 24px;">
                <div style="text-align: right;">
                    <div style="font-weight: 700; font-size: 0.95rem; color: #fff;">{{ auth()->user()->name ?? 'Staff' }}</div>
                    <div style="font-size: 0.75rem; color: var(--accent); text-transform: uppercase; font-weight: 700;">Role: {{ auth()->user()->role ?? 'kasir' }}</div>
                </div>
                @if(auth()->user() && auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm" style="color: #fff; border-color: rgba(255,255,255,0.2);">
                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        <span>Dashboard Admin</span>
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
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
