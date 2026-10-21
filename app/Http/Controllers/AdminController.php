<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Stock; // <-- PERUBAHAN: Menggunakan Model Stock yang baru
use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
{
    // ==========================================
    // 1. DASHBOARD / GUDANG
    // ==========================================
    public function index()
    {
        $stocks = Stock::with('variant.product')->latest()->get();
        $variants = ProductVariant::with('product')->get();
        
        // --- LOGIKA DASHBOARD STATISTIK ---
        // Asumsi: Pesanan dianggap berhasil dan jadi uang jika statusnya 'selesai'
        $totalPesananMasuk = Order::count();
        $totalTerjual = Order::where('status', 'selesai')->count(); 
        $totalPendapatan = Order::where('status', 'selesai')->sum('total_price');
        
        return view('admin.dashboard', compact(
            'stocks', 'variants', 'totalPesananMasuk', 'totalTerjual', 'totalPendapatan'
        ));
    }

    public function storeStock(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'email' => 'nullable|email',
            'password' => 'nullable|string'
        ]);

        // <-- PERUBAHAN: Menggunakan Stock::create dan nama kolom yang baru
        Stock::create([
            'product_variant_id' => $request->product_variant_id,
            'account_email' => $request->email,
            'account_password' => $request->password,
            'is_available' => true,
        ]);

        return back()->with('success', 'Akun berhasil ditambahkan ke gudang.');
    }

    public function destroyStock($id)
    {
        // <-- PERUBAHAN: Menggunakan Model Stock
        Stock::findOrFail($id)->delete();
        return back()->with('success', 'Akun dihapus dari gudang.');
    }

    // ==========================================
    // 2. KELOLA PRODUK (ETALASE)
    // ==========================================
    public function products()
    {
        $products = Product::with('variants')->latest()->get();
        return view('admin.products', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'image' => 'nullable|url',
            'description' => 'required|string',
            'variant_name' => 'required|array|min:1',
            'variant_duration' => 'required|array|min:1',
            'variant_price' => 'required|array|min:1',
        ]);

        // Simpan Data Produk Utama
        $product = Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'image' => $request->image,
            'description' => $request->description,
        ]);

        // Simpan Semua Tipe/Paket secara dinamis (Looping)
        foreach ($request->variant_name as $index => $vName) {
            ProductVariant::create([
                'product_id' => $product->id,
                'name' => $vName, // Misal: Ultra HD 4K
                'duration' => $request->variant_duration[$index], // Misal: 1 Bulan
                'price' => $request->variant_price[$index], // Misal: 50000
            ]);
        }

        return back()->with('success', 'Produk dan Paket berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete(); // Akan otomatis menghapus varian dan stok berkat Cascade
        return back()->with('success', 'Produk beserta semua paketnya dihapus.');
    }

    // ==========================================
    // 3. KELOLA PESANAN & CUSTOMER
    // ==========================================
    public function orders()
    {
        $orders = Order::with(['user', 'variant.product'])->latest()->get();
        return view('admin.orders', compact('orders'));
    }

    public function customers()
    {
        $customers = User::where('role', 'user')->latest()->get();
        return view('admin.customers', compact('customers'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diubah menjadi ' . $request->status . '!');
    }
    
    // Tampilan Form Edit Produk
    public function editProduct($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        return view('admin.products_edit', compact('product'));
    }

    // Proses Update Produk & Varian
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'image' => 'nullable|url',
            'description' => 'required|string',
            'variant_name' => 'required|array|min:1',
            'variant_duration' => 'required|array|min:1',
            'variant_price' => 'required|array|min:1',
        ]);

        $product = Product::findOrFail($id);
        
        // 1. Update data produk utama
        $product->update([
            'name' => $request->name,
            'category' => $request->category,
            'image' => $request->image,
            'description' => $request->description,
        ]);

        // 2. Update atau Tambah Varian
        $existingVariantIds = [];
        foreach ($request->variant_name as $index => $vName) {
            $vId = $request->variant_id[$index] ?? null;
            if ($vId) {
                $variant = ProductVariant::find($vId);
                if ($variant) {
                    $variant->update([
                        'name' => $vName,
                        'duration' => $request->variant_duration[$index],
                        'price' => $request->variant_price[$index],
                    ]);
                    $existingVariantIds[] = $variant->id;
                }
            } else {
                $newVariant = ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $vName,
                    'duration' => $request->variant_duration[$index],
                    'price' => $request->variant_price[$index],
                ]);
                $existingVariantIds[] = $newVariant->id;
            }
        }

        // Hapus varian yang dibuang oleh admin saat edit
        ProductVariant::where('product_id', $product->id)
            ->whereNotIn('id', $existingVariantIds)
            ->delete();

        return redirect()->route('admin.products')->with('success', 'Produk & Paket berhasil diperbarui!');
    }
}