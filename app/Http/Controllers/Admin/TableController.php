<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::withCount('orders')->orderBy('table_number', 'asc')->paginate(7);
        return view('admin.tables.index', ['tables' => $tables]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|unique:tables,table_number|max:10',
            'name' => 'nullable|string|max:100',
        ]);

        $code = str_pad($validated['table_number'], 2, '0', STR_PAD_LEFT);

        Table::create([
            'table_number' => $code,
            'name' => $validated['name'] ?? "Meja {$code}",
            'code' => $code,
            'status' => 'active',
        ]);

        return redirect()->route('admin.tables.index')->with('success', 'Meja baru berhasil ditambahkan!');
    }

    public function showQr(Table $table)
    {
        $targetUrl = route('customer.menu', ['table' => $table->table_number]);
        $qrCodeUrl = QrCodeService::getQrImageUrl($targetUrl, 400);

        return view('admin.tables.qr_card', [
            'table' => $table,
            'targetUrl' => $targetUrl,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    public function toggleStatus(Table $table)
    {
        $newStatus = $table->status === 'active' ? 'inactive' : 'active';
        $table->update(['status' => $newStatus]);

        return back()->with('success', "Status Meja {$table->table_number} diubah menjadi {$newStatus}.");
    }

    public function destroy(Table $table)
    {
        if ($table->orders()->count() > 0) {
            return back()->with('error', 'Meja tidak dapat dihapus karena memiliki riwayat transaksi.');
        }

        $table->delete();
        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil dihapus!');
    }
}
