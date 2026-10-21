<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Stock; // WAJIB DITAMBAHKAN
use App\Models\ProductVariant; // WAJIB DITAMBAHKAN
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // WAJIB DITAMBAHKAN UNTUK TRANSAKSI
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * 1. Fungsi untuk menampilkan halaman utama (Katalog Produk / Home)
     */
    public function home()
    {
        // Memanggil with('variants') agar logika "Start from" di halaman home berjalan
        $products = Product::with('variants')->latest()->get();
        
        // Ambil daftar kategori unik untuk tombol filter
        $categories = Product::select('category')->distinct()->pluck('category');
        
        // --- AMBIL DATA RECENT VIEWS DARI SESSION (UNTUK FITUR HISTORI/CHIPS) ---
        $recentViews = session()->get('recent_views', []);
        
        return view('home', compact('products', 'categories', 'recentViews'));
    }

   /**
     * 2. Halaman Detail Produk & Pilih Varian (Layer Baru)
     */
    public function showProduct($id)
    {
        // FILTER VARIAN BERDASARKAN STOK
        // Kita hanya mengambil varian yang terhubung dengan tabel 'stocks' 
        // di mana status 'is_available' bernilai true.
        $product = Product::with(['variants' => function($query) {
            $query->whereHas('stocks', function($q) {
                $q->where('is_available', true);
            });
        }])->findOrFail($id);

        // --- LOGIKA MENYIMPAN RECENT VIEWS ---
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
     * 3. Memproses klik "Lanjut ke Pembayaran" dengan KUNCI STOK OTOMATIS
     */
    public function createOrder(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'contact_info' => 'nullable|numeric'
        ]);

        try {
            // 2. Mulai Kunci Database Transaksi (Pessimistic Locking)
            DB::beginTransaction();

            // 3. Cari 1 Stok Tersedia & Langsung Kunci (Mencegah Rebutan)
            $stock = Stock::where('product_variant_id', $request->variant_id)
                          ->where('is_available', true)
                          ->lockForUpdate() 
                          ->first();

            // 4. Jika stok tiba-tiba habis (misal baru saja ditekan orang lain sepersekian detik lalu)
            if (!$stock) {
                DB::rollBack(); // Batalkan semua proses
                return back()->with('error', 'Maaf, stok paket ini baru saja habis. Silakan pilih paket lain.');
            }

            // 5. Ambil harga dari varian yang dipilih
            $variant = ProductVariant::findOrFail($request->variant_id);
            $orderNumber = 'TRX-QITS-' . strtoupper(Str::random(7));

            // 6. Buat Pesanan (Order)
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'product_variant_id' => $request->variant_id,
                'status' => 'pending', 
                'whatsapp' => $request->contact_info ?? null,
                'payment_method' => 'transfer_manual',
                'total_price' => $variant->price, 
            ]);

            // 7. Tandai Stok Tersebut Sudah Laku (Tautkan ke Order ID)
            $stock->is_available = false;
            $stock->order_id = $order->id;
            $stock->save();

            // 8. Simpan Semua Perubahan Secara Permanen ke Database
            DB::commit();

            // 9. Arahkan ke halaman upload bukti pembayaran
            return redirect()->route('payment', $order->id)->with('success', 'Akun berhasil diamankan! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            // Jika ada error internal server, kembalikan seperti semula
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }   

    /**
     * 4. Halaman Menampilkan Instruksi Bayar & Form Upload Bukti
     */
    public function payment($id)
    {
        $order = Order::with('variant.product')->where('user_id', Auth::id())->findOrFail($id);
        
        return view('payment', compact('order'));
    }

    /**
     * 5. Memproses Upload / Update Bukti Transfer
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
            
            // Simpan ke folder storage/app/public/bukti_tf
            $file->storeAs('bukti_tf', $filename, 'public');

            // Perbarui database
            $order->update([
                'payment_proof' => $filename,
                'status' => 'menunggu_konfirmasi'
            ]);
        }

        return back()->with('success', 'Bukti transfer berhasil diunggah! Mohon tunggu konfirmasi admin.');
    }

    /**
     * 6. Halaman Dashboard Pembeli (Riwayat Pesanan)
     */
    public function myOrders()
    {
        $orders = Order::with('variant.product')->where('user_id', Auth::id())->latest()->get();
        
        return view('dashboard', compact('orders'));
    }
}