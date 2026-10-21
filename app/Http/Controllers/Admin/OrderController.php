<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\AccountStock;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Fungsi untuk menyetujui pesanan
    public function approve($id)
    {
        $order = Order::findOrFail($id);

        // Cari stok akun (di gudang) yang belum terpakai untuk produk ini
        $stock = AccountStock::where('product_id', $order->product_id)
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