<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Staff - Cafe Self-Ordering System</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="background: linear-gradient(135deg, #160e0a 0%, #3c2a21 100%); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">
    <div style="width: 100%; max-width: 420px; background: #ffffff; border-radius: var(--radius-lg); padding: 40px 32px; box-shadow: 0 24px 60px rgba(0,0,0,0.45); border: 1px solid rgba(255,255,255,0.1);">
        <div style="text-align: center; margin-bottom: 32px;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #d4a373 0%, #b88252 100%); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; color: #fff; margin-bottom: 12px; box-shadow: 0 8px 20px rgba(212, 163, 115, 0.4);">
                <svg class="svg-icon svg-icon-lg" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path></svg>
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--primary); letter-spacing: -0.5px;">Portal Staff Kafe</h1>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">Masuk ke sistem Admin, Kasir, atau Dapur</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Alamat Email Staff</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@cafe.com / kasir@cafe.com" required autofocus>
                @error('email')
                    <span style="font-size: 0.78rem; color: var(--danger); font-weight: 600; display: block; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 28px;">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="padding: 14px; font-size: 1rem;">
                <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <span>Masuk Sistem</span>
            </button>
        </form>
    </div>
</body>
</html>
