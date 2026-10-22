<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Stock;
use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
{
    // ==========================================
    // 1. DASHBOARD / STATISTIK PENJUALAN
    // ==========================================
    public function index()
    {
        // Hitung Total Akun Terjual dari Gudang (Pasti Muncul)
        $totalTerjual = Stock::where('is_available', false)->count(); 
        
        // Logika tahan banting agar sistem tidak peduli huruf besar/kecil
        $statusValid = ['selesai', 'Selesai', 'SELESAI', 'success', 'Success', 'berhasil', 'Lunas'];
        
        // Hitung Pendapatan
        $totalPendapatan = Order::whereIn('status', $statusValid)->sum('total_price');
        
        // Tarik Kuitansi
        $soldOrders = Order::with(['user', 'variant.product'])
                           ->whereIn('status', $statusValid)
                           ->latest()
                           ->get();
        
        return view('admin.dashboard', compact('totalTerjual', 'totalPendapatan', 'soldOrders'));
    }

    // ==========================================
    // 2. KELOLA PRODUK 
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

        $product = Product::create($request->only('name', 'category', 'image', 'description'));

        foreach ($request->variant_name as $index => $vName) {
            ProductVariant::create([
                'product_id' => $product->id,
                'name' => $vName,
                'duration' => $request->variant_duration[$index],
                'price' => $request->variant_price[$index],
            ]);
        }

        return back()->with('success', 'Produk dan Paket berhasil ditambahkan!');
    }

    public function editProduct($id)
    {
        $product = Product::with(['variants'])->findOrFail($id);
        return view('admin.products_edit', compact('product'));
    }

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
        $product->update($request->only('name', 'category', 'image', 'description'));

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

        ProductVariant::where('product_id', $product->id)->whereNotIn('id', $existingVariantIds)->delete();

        return redirect()->route('admin.products')->with('success', 'Detail Produk & Paket berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
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

    // --- FUNGSI BARU UNTUK HALAMAN DETAIL PESANAN ---
    public function showOrder($id)
    {
        $order = Order::with(['user', 'variant.product'])->findOrFail($id);
        return view('admin.order_detail', compact('order'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        Order::findOrFail($id)->update(['status' => $request->status]);
        
        return back()->with('success', 'Status pesanan berhasil diubah!');
    }

    // --- INI FUNGSI CUSTOMERS YANG SEBELUMNYA HILANG ---
    public function customers()
    {
        $customers = User::where('role', 'user')->latest()->get();
        return view('admin.customers', compact('customers'));
    }

    // ==========================================
    // 4. HALAMAN KHUSUS KELOLA STOK VARIAN
    // ==========================================
    public function manageStocks($variantId)
    {
        $variant = ProductVariant::with('product')->findOrFail($variantId);
        
        // HANYA AMBIL STOK YANG MASIH TERSEDIA SAJA (Sembunyikan yang terjual)
        $availableStocks = Stock::where('product_variant_id', $variantId)
                          ->where('is_available', true)
                          ->latest()
                          ->get();
                          
        return view('admin.stocks_manage', compact('variant', 'availableStocks'));
    }

    public function storeVariantStock(Request $request, $variantId)
    {
        $request->validate([
            'account_email' => 'nullable|string',
            'account_password' => 'nullable|string',
        ]);

        $emailFinal = empty($request->account_email) ? 'hubungi admin' : $request->account_email;
        $passwordFinal = empty($request->account_password) ? 'no admin' : $request->account_password;

        Stock::create([
            'product_variant_id' => $variantId,
            'account_email' => $emailFinal,
            'account_password' => $passwordFinal,
            'is_available' => true,
        ]);

        return back()->with('success', 'Stok akun baru berhasil ditambahkan!');
    }

    public function destroyStock($id)
    {
        Stock::findOrFail($id)->delete();
        return back()->with('success', 'Stok akun berhasil dihapus!');
    }
}