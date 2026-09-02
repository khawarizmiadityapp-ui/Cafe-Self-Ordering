@extends('layouts.admin')

@section('title', 'Pengaturan Toko & Informasi Struk')

@section('content')
    <!-- Rich Settings Hero Header Banner -->
    <div class="settings-hero-header">
        <div class="settings-hero-content">
            <div class="settings-hero-badge">
                <span class="pulse-dot"></span>
                <span>SYSTEM CONFIGURATION</span>
            </div>
            <h1 class="settings-hero-title">Pengaturan Toko & Struk Cetak</h1>
            <p class="settings-hero-sub">Kelola nama restoran, alamat, kontak, dan teks footer yang dicetak otomatis pada struk kasir</p>
        </div>
        <div class="settings-hero-chip">
            <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            <span>POS Receipt Engine</span>
        </div>
    </div>

    <div class="admin-split-2col">
        <!-- Settings Form Panel -->
        <div class="dashboard-panel settings-form-panel">
            <div class="panel-header-clean">
                <div class="panel-title-wrap">
                    <div class="panel-icon-badge icon-badge-brown">
                        <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="panel-title-text">Identitas Restoran & Struk</h3>
                        <p class="panel-subtitle-text">Informasi ini akan tercetak otomatis pada struk belanja pelanggan</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                <div class="settings-section-divider">
                    <span>1. HEADER STRUK (IDENTITAS CAFE)</span>
                </div>

                <div class="form-group">
                    <label class="form-label-rich" for="store_name">
                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Nama Toko / Restoran</span> <span class="required-star">*</span>
                    </label>
                    <input type="text" id="store_name" name="store_name" class="form-control field-styled-rich" value="{{ old('store_name', $settings['store_name']) }}" required placeholder="Contoh: KAFE DIGITAL" oninput="updateLivePreview()">
                    @error('store_name')
                        <div class="field-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label-rich" for="store_address">
                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span>Alamat Lengkap</span> <span class="required-star">*</span>
                    </label>
                    <textarea id="store_address" name="store_address" class="form-control field-styled-rich" rows="2" required placeholder="Contoh: Jl. Coffee Boulevard No. 88, Jakarta" oninput="updateLivePreview()">{{ old('store_address', $settings['store_address']) }}</textarea>
                    @error('store_address')
                        <div class="field-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label-rich" for="store_phone">
                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <span>Nomor Telepon / WA</span> <span class="required-star">*</span>
                    </label>
                    <input type="text" id="store_phone" name="store_phone" class="form-control field-styled-rich" value="{{ old('store_phone', $settings['store_phone']) }}" required placeholder="Contoh: 0812-3456-7890" oninput="updateLivePreview()">
                    @error('store_phone')
                        <div class="field-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="settings-section-divider" style="margin-top: 24px;">
                    <span>2. FOOTER STRUK & INFORMASI TAMBAHAN</span>
                </div>

                <div class="form-group">
                    <label class="form-label-rich" for="receipt_footer_text">
                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        <span>Pesan Terima Kasih (Footer)</span>
                    </label>
                    <input type="text" id="receipt_footer_text" name="receipt_footer_text" class="form-control field-styled-rich" value="{{ old('receipt_footer_text', $settings['receipt_footer_text']) }}" placeholder="Contoh: Terima Kasih Atas Kunjungan Anda!" oninput="updateLivePreview()">
                    @error('receipt_footer_text')
                        <div class="field-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label-rich" for="receipt_wifi_info">
                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M5 12.55a11 11 0 0 1 14.08 0"></path><path d="M1.42 9a16 16 0 0 1 21.16 0"></path><path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path><line x1="12" y1="20" x2="12.01" y2="20"></line></svg>
                        <span>Informasi WiFi Kafe</span>
                    </label>
                    <input type="text" id="receipt_wifi_info" name="receipt_wifi_info" class="form-control field-styled-rich" value="{{ old('receipt_wifi_info', $settings['receipt_wifi_info']) }}" placeholder="Contoh: WiFi: CafeGuest / Pass: ngopidulu" oninput="updateLivePreview()">
                    @error('receipt_wifi_info')
                        <div class="field-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-top: 28px;">
                    <button type="submit" class="btn-save-settings-hero">
                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        <span>Simpan Perubahan Struk</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- POS Thermal Printer Device Mockup Panel -->
        <div class="dashboard-panel preview-panel-rich">
            <div class="panel-header-clean">
                <div class="panel-title-wrap">
                    <div class="panel-icon-badge icon-badge-coffee">
                        <svg class="svg-icon svg-icon-md" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    </div>
                    <div>
                        <h3 class="panel-title-text">Pratinjau Cetakan Struk</h3>
                        <p class="panel-subtitle-text">Simulasi printer thermal POS real-time</p>
                    </div>
                </div>
                <span class="chip-badge chip-gold">Thermal 80mm</span>
            </div>

            <!-- POS Printer Device Visual -->
            <div class="pos-device-mockup">
                <!-- Printer Head Slot Top Bar -->
                <div class="pos-printer-head">
                    <div class="pos-printer-lights">
                        <span class="printer-led led-green"></span>
                        <span class="printer-led led-blue"></span>
                    </div>
                    <div class="printer-model-text">POS THERMAL PRINTER • READY</div>
                </div>

                <!-- Paper Output Slot -->
                <div class="pos-paper-slot"></div>

                <!-- Thermal Paper Receipt -->
                <div class="thermal-receipt-paper">
                    <div class="receipt-header-content">
                        <div id="preview_store_name" class="receipt-store-title">{{ $settings['store_name'] }}</div>
                        <div id="preview_store_address" class="receipt-store-sub">{{ $settings['store_address'] }}</div>
                        <div id="preview_store_phone" class="receipt-store-sub">Telp: {{ $settings['store_phone'] }}</div>
                    </div>

                    <div class="receipt-row">
                        <span>No. Order:</span>
                        <strong>#ORD-20260902-0001</strong>
                    </div>
                    <div class="receipt-row">
                        <span>Tanggal:</span>
                        <span>02/09/2026 14:30</span>
                    </div>
                    <div class="receipt-row">
                        <span>Kasir:</span>
                        <span>Admin</span>
                    </div>

                    <div class="receipt-dash"></div>

                    <div class="receipt-row-bold">
                        <span>1x Caffe Latte</span>
                        <span>Rp28.000</span>
                    </div>
                    <div class="receipt-row-bold" style="margin-top: 4px;">
                        <span>1x Croissant Butter</span>
                        <span>Rp22.000</span>
                    </div>

                    <div class="receipt-dash"></div>

                    <div class="receipt-row-total">
                        <span>TOTAL:</span>
                        <span>Rp50.000</span>
                    </div>

                    <div class="receipt-dash"></div>

                    <div class="receipt-footer-content">
                        <div id="preview_footer_text" class="receipt-footer-title">{{ $settings['receipt_footer_text'] }}</div>
                        <div id="preview_wifi_info" class="receipt-footer-wifi">{{ $settings['receipt_wifi_info'] }}</div>
                        <div class="receipt-footer-note">Simpan struk ini sebagai bukti pembayaran.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function updateLivePreview() {
                const name = document.getElementById('store_name').value || 'KAFE DIGITAL';
                const address = document.getElementById('store_address').value || 'Jl. Coffee Boulevard No. 88, Jakarta';
                const phone = document.getElementById('store_phone').value || '0812-3456-7890';
                const footer = document.getElementById('receipt_footer_text').value || 'Terima Kasih Atas Kunjungan Anda!';
                const wifi = document.getElementById('receipt_wifi_info').value || 'WiFi: CafeGuest / Pass: ngopidulu';

                document.getElementById('preview_store_name').textContent = name;
                document.getElementById('preview_store_address').textContent = address;
                document.getElementById('preview_store_phone').textContent = 'Telp: ' + phone;
                document.getElementById('preview_footer_text').textContent = footer;
                document.getElementById('preview_wifi_info').textContent = wifi;
            }
        </script>
    @endpush
@endsection


