<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Stock; 
use App\Models\ProductVariant; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * 1. Fungsi untuk menampilkan halaman utama (Katalog Produk / Home)
     */
    public function home()
    {
        $products = Product::with('variants')->latest()->get();
        $categories = Product::select('category')->distinct()->pluck('category');
        $recentViews = session()->get('recent_views', []);
        
        return view('home', compact('products', 'categories', 'recentViews'));
    }

    /**
     * 2. Halaman Detail Produk & Pilih Varian (Layer 1)
     */
    public function showProduct($id)
    {
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

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'product_variant_id' => $request->variant_id,
                'status' => 'menunggu pembayaran',
                'whatsapp' => $request->contact_wa,
                'payment_method' => 'transfer_manual',
                'total_price' => $variant->price, 
            ]);

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
     * 5. Halaman Detail Pesanan User & Ganti Bukti TF
     */
    public function showOrderDetail($id)
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
}   