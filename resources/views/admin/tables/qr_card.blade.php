<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>QR Code Card - Meja {{ $table->table_number }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .card-wrapper { box-shadow: none !important; border: 2px solid #000 !important; }
        }
    </style>
</head>
<body style="background: var(--bg-main); display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">
    <div class="no-print" style="margin-bottom: 24px; display: flex; gap: 12px;">
        <button onclick="window.print()" class="btn btn-primary" style="padding: 12px 24px;">
            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            <span>Cetak Kartu QR Code Meja</span>
        </button>
        <a href="{{ route('admin.tables.index') }}" class="btn btn-outline">
            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Standee / Table Card Mockup -->
    <div class="card-wrapper" style="width: 370px; background: #ffffff; border-radius: 28px; padding: 36px 28px; text-align: center; border: 2px solid var(--accent); box-shadow: 0 16px 40px rgba(0,0,0,0.15); position: relative; overflow: hidden;">
        <div style="background: linear-gradient(135deg, #1e140e 0%, #3c2a21 100%); color: white; padding: 18px; border-radius: 20px; margin-bottom: 22px;">
            <div style="display: flex; justify-content: center; margin-bottom: 6px;">
                <svg class="svg-icon svg-icon-lg" style="color: var(--accent);" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"></path></svg>
            </div>
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--accent); letter-spacing: -0.5px;">KAFE DIGITAL</div>
            <div style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1.2px; font-weight: 600; opacity: 0.9;">Self-Ordering System</div>
        </div>

        <div style="font-size: 1.6rem; font-weight: 800; color: var(--primary); margin-bottom: 14px; letter-spacing: -0.5px;">
            MEJA {{ $table->table_number }}
        </div>

        <!-- Generated QR Code Image -->
        <div style="background: var(--accent-light); padding: 18px; border-radius: 20px; display: inline-block; border: 1.5px solid var(--accent); margin-bottom: 18px;">
            <img src="{{ $qrCodeUrl }}" alt="QR Code Meja {{ $table->table_number }}" style="width: 210px; height: 210px; display: block; border-radius: 10px;">
        </div>

        <div style="font-size: 0.95rem; font-weight: 800; color: var(--text-dark); margin-bottom: 6px;">
            SCAN QR UNTUK MEMESAN
        </div>
        <p style="font-size: 0.78rem; color: var(--text-muted); line-height: 1.4;">
            Arahkan kamera smartphone Anda ke QR Code ini untuk memilih menu dan melakukan pemesanan tanpa perlu antre di kasir.
        </p>

        <div style="margin-top: 18px; padding-top: 14px; border-top: 1px dashed var(--border-color); font-size: 0.72rem; color: var(--text-muted); font-family: monospace;">
            URL: {{ $targetUrl }}
        </div>
    </div>
</body>
</html>
