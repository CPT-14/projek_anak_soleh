<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Stock;
use App\Models\Order;
use App\Models\User;
use App\Models\ProductCategory;

class AdminController extends Controller
{
    // ==========================================
    // 1. DASHBOARD / STATISTIK PENJUALAN
    // ==========================================
    public function index(Request $request)
{
    // Mulai query pesanan yang sudah selesai (sesuaikan status sukses di sistem Anda)
    $query = Order::whereIn('status', ['selesai', 'success']);

    // Filter berdasarkan Bulan jika dipilih
    if ($request->filled('bulan')) {
        $query->whereMonth('created_at', $request->bulan);
    }

    // Filter berdasarkan Tahun jika dipilih
    if ($request->filled('tahun')) {
        $query->whereYear('created_at', $request->tahun);
    }

    // Ambil data untuk tabel dan hitung statistik berdasarkan filter
    $soldOrders = $query->latest()->get();
    $totalTerjual = $soldOrders->count();
    $totalPendapatan = $soldOrders->sum('total_price');

    return view('admin.dashboard', compact('soldOrders', 'totalTerjual', 'totalPendapatan'));
}

    // ==========================================
    // 2. KELOLA PRODUK 
    // ==========================================
    public function products()
    {
        $products = Product::with('variants')->latest()->get();
        
        // Ambil kategori dari database baru untuk dikirim ke view
        $product_categories = ProductCategory::latest()->get();
        
        return view('admin.products', compact('products', 'product_categories'));
    }

    // --- METHOD CRUD KATEGORI ---
    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:product_categories,name']);
        ProductCategory::create(['name' => $request->name]);
        return back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function destroyCategory($id)
    {
        ProductCategory::findOrFail($id)->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
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
        
        // Ambil data kategori dari database untuk dikirim ke view edit
        $product_categories = ProductCategory::latest()->get();
        
        return view('admin.products_edit', compact('product', 'product_categories'));
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
    public function orders(Request $request)
    {
        $filterStatus = $request->query('status');
        $searchQuery = $request->query('search'); // Tangkap input pencarian

        $query = Order::with(['user', 'variant.product'])->latest();

        // 1. Filter Status (Jika ada)
        if ($filterStatus && $filterStatus !== 'all') {
            $query->where('status', $filterStatus);
        }

        // 2. Filter Pencarian Nomor Transaksi (BARU)
        if ($searchQuery) {
            $query->where('order_number', 'like', '%' . $searchQuery . '%');
        }

        $orders = $query->get();
        return view('admin.orders', compact('orders', 'filterStatus', 'searchQuery'));
    }

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

    public function customers()
    {
        // Fitur: Hitung pesanan selesai & tarik 1 pesanan terbaru untuk cek WA pelanggan
        $customers = User::where('role', 'user')
            ->withCount(['orders' => function ($query) {
                $statusValid = ['selesai', 'Selesai', 'SELESAI', 'success', 'Success'];
                $query->whereIn('status', $statusValid);
            }])
            ->with(['orders' => function ($query) {
                $query->latest()->limit(1); 
            }])
            ->latest()
            ->get();
            
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

    // ==========================================
    // 5. FITUR AJAX POLLING (NOTIFIKASI REAL-TIME)
    // ==========================================
    public function checkNewOrders()
    {
        // Hitung pesanan yang berstatus pending atau diproses
        $count = Order::whereIn('status', ['pending', 'diproses'])->count();
        
        // Kembalikan hasilnya dalam bentuk JSON (agar mudah dibaca oleh JavaScript)
        return response()->json(['count' => $count]);
    }
}