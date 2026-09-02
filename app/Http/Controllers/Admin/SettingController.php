<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Show store and receipt settings page
     */
    public function index()
    {
        $settings = [
            'store_name' => Setting::get('store_name', 'KAFE DIGITAL'),
            'store_address' => Setting::get('store_address', 'Jl. Coffee Boulevard No. 88, Jakarta'),
            'store_phone' => Setting::get('store_phone', '0812-3456-7890'),
            'receipt_footer_text' => Setting::get('receipt_footer_text', 'Terima Kasih Atas Kunjungan Anda!'),
            'receipt_wifi_info' => Setting::get('receipt_wifi_info', 'WiFi: CafeGuest / Pass: ngopidulu'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update store and receipt settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:100',
            'store_address' => 'required|string|max:255',
            'store_phone' => 'required|string|max:50',
            'receipt_footer_text' => 'nullable|string|max:255',
            'receipt_wifi_info' => 'nullable|string|max:100',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan Toko & Informasi Struk Berhasil Diperbarui!');
    }
}
