<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Stock;
use App\Models\ProductVariant;
use App\Models\ProductCategory; // Tambahan untuk mengambil Kategori
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * 1. Fungsi untuk menampilkan halaman utama (Katalog Produk / Home)
     * SEKARANG SUDAH DILENGKAPI FITUR PENCARIAN
     */
    public function home(Request $request)
    {
        // 1. Tangkap kata kunci pencarian (jika ada)
        $searchQuery = $request->input('search');

        // 2. Ambil produk. Jika ada pencarian, filter berdasarkan nama atau deskripsi.
        if ($searchQuery) {
            $products = Product::with('variants')
                ->where('name', 'LIKE', "%{$searchQuery}%")
                ->orWhere('description', 'LIKE', "%{$searchQuery}%")
                ->latest()
                ->get();
        } else {
            $products = Product::with('variants')->latest()->get();
        }
        
        // 3. Ambil kategori dari tabel Product_Categories (Update dari fitur CRUD Kategori)
        $categories = ProductCategory::pluck('name'); 
        
        // 4. Riwayat pesanan (recent views)
        $recentViews = session()->get('recent_views', []);

        // 5. Lempar semua data (termasuk searchQuery) ke view
        return view('home', compact('products', 'categories', 'recentViews', 'searchQuery'));
    }

    /**
     * 2. Halaman Detail Produk & Pilih Varian
     */
    public function showProduct($id)
    {
        // Hanya ambil varian yang memiliki stok tersedia
        $product = Product::with(['variants' => function($query) {
            $query->whereHas('stocks', function($q) {
                $q->where('is_available', true);
            });
        }])->findOrFail($id);

        $recentViews = session()->get('recent_views', []);
        
        $recentViews = array_filter($recentViews, function($val) use ($id) {
            return $val['id'] != $id;
        });
        
        array_unshift($recentViews, [
            'id' => $product->id,
            'name' => $product->name
        ]);
        
        $recentViews = array_slice($recentViews, 0, 5);
        session()->put('recent_views', $recentViews);

        return view('product_detail', compact('product'));
    }

    /**
     * 3. Memproses klik "Lanjutkan Pembayaran"
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'contact_wa' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();

            // Mengunci row stok (Pencegahan bentrok transaksi)
            $stock = Stock::where('product_variant_id', $request->variant_id)
                          ->where('is_available', true)
                          ->lockForUpdate() 
                          ->first();

            if (!$stock) {
                DB::rollBack();
                return back()->with('error', 'Maaf, stok paket ini baru saja habis. Silakan pilih paket lain.');
            }

            $variant = ProductVariant::findOrFail($request->variant_id);
            $orderNumber = 'TRX-QITS-' . strtoupper(Str::random(7));

            // Buat Pesanan
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'product_variant_id' => $request->variant_id,
                'status' => 'menunggu pembayaran',
                'whatsapp' => $request->contact_wa,
                'payment_method' => 'transfer_manual',
                'total_price' => $variant->price, 
            ]);

            // Tandai stok sudah terjual
            $stock->is_available = false;
            $stock->save();

            DB::commit();

            return redirect()->route('order.detail.user', $order->id)->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }   

    /**
     * 4. Halaman Dashboard Pembeli (Riwayat Pesanan)
     */
    public function myOrders()
    {
        $orders = Order::with('variant.product')->where('user_id', Auth::id())->latest()->get();
        return view('dashboard', compact('orders'));
    }

    /**
     * 5. Halaman Detail Pesanan User (Order Detail)
     */
    public function orderDetail($id)
    {
        $order = Order::with('variant.product')->where('user_id', Auth::id())->findOrFail($id);
        return view('order_detail_user', compact('order'));
    }

    /**
     * 6. Memproses Upload / Ganti Bukti Transfer
     */
    public function uploadPayment(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            // Simpan ke storage/app/public/bukti_tf
            $file->storeAs('bukti_tf', $filename, 'public');

            // Update nama file & ubah status pesanan menjadi diproses
            $order->update([
                'payment_proof' => $filename,
                'status' => 'diproses'
            ]);
        }

        return back()->with('success', 'Bukti transfer berhasil diperbarui! Mohon tunggu konfirmasi admin.');
    }
    /**
     * Menampilkan Halaman Pusat Bantuan
     */
    public function bantuan()
    {
        return view('bantuan');
    }
}