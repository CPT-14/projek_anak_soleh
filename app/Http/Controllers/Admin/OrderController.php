<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
// 1. BUG FIXED: Ubah AccountStock menjadi Stock
use App\Models\Stock; 
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Fungsi untuk menyetujui pesanan
    public function approve($id)
    {
        $order = Order::findOrFail($id);

        // 2. BUG FIXED: Panggil Stock::where, bukan AccountStock
        // Catatan: Jika di tabel orders Anda tidak ada product_id (hanya ada product_variant_id), 
        // Anda mungkin perlu menyesuaikannya menjadi $order->product_variant_id
        $stock = Stock::where('product_id', $order->product_id ?? $order->product_variant_id)
            ->where('status', 'belum terpakai')
            ->first();

        if (!$stock) {
            return redirect()->back()->with('error', 'Gagal! Stok akun di gudang kosong.');
        }

        // Update pesanan menjadi SELESAI dan berikan ID stok akunnya
        $order->update([
            'status' => 'selesai', // <-- Sesuai enum migration
            'account_stock_id' => $stock->id,
        ]);

        // Ubah status akun di gudang menjadi terpakai
        $stock->update([
            'status' => 'terpakai',
        ]);

        return redirect()->back()->with('success', 'Pesanan disetujui! Akun otomatis dikirim ke pembeli.');
    }

    // Fungsi untuk menolak pesanan
    public function reject($id)
    {
        $order = Order::findOrFail($id);
        
        $order->update([
            'status' => 'dibatalkan', // <-- Sesuai enum migration
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil ditolak.');
    }
}