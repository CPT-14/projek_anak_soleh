@extends('layouts.admin')
@section('title', 'Detail Pesanan')

@section('content')
<div class="welcome-section" style="margin-bottom: 24px;">
    <h1>Detail Pesanan: #ORD-{{ $order->id }} 🔍</h1>
    <p>.</p>
</div>

<a href="{{ route('admin.orders') }}" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; color: #790D16; font-weight: bold; text-decoration: none;">
    &larr; Kembali ke Daftar Pesanan
</a>

@if(session('success'))
    <div style="background: #d1e7dd; color: #0f5132; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; border: 1px solid #badbcc;">
        ✅ {{ session('success') }}
    </div>
@endif

<div style="display: flex; gap: 24px; flex-wrap: wrap;">
    
    <!-- KIRI: INFORMASI PESANAN & PEMBELI -->
    <div style="flex: 2; min-width: 350px;">
        
        <!-- Data Pembeli -->
        <div style="background: #fff; padding: 24px; border-radius: 12px; border: 1px solid #eee; margin-bottom: 24px; border-top: 4px solid #2563eb;">
            <h3 style="margin-bottom: 16px; font-size: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">👤 Informasi Pembeli</h3>
            <table style="width: 100%; font-size: 14px;">
                <tr><td style="padding: 8px 0; color: #64748b; width: 130px;">Nama Akun</td><td style="font-weight: 600;">: {{ $order->user->name ?? 'N/A' }}</td></tr>
                <tr><td style="padding: 8px 0; color: #64748b;">Email Akun</td><td style="font-weight: 600;">: {{ $order->user->email ?? 'N/A' }}</td></tr>
                <tr><td style="padding: 8px 0; color: #64748b;">No. WhatsApp</td><td style="font-weight: 900; color: #2563eb; font-size: 15px;">: {{ $order->whatsapp ?? 'Tidak diisi' }}</td></tr>
                <tr><td style="padding: 8px 0; color: #64748b;">Tanggal Pesan</td><td style="font-weight: 600;">: {{ $order->created_at->format('d M Y, H:i') }}</td></tr>
            </table>
        </div>

        <!-- Data Produk -->
        <div style="background: #fff; padding: 24px; border-radius: 12px; border: 1px solid #eee; border-top: 4px solid #10b981;">
            <h3 style="margin-bottom: 16px; font-size: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">📦 Rincian Produk & Pembayaran</h3>
            <table style="width: 100%; font-size: 14px;">
                <tr><td style="padding: 8px 0; color: #64748b; width: 130px;">Produk</td><td style="font-weight: bold; color: #1e293b;">: {{ $order->variant->product->name ?? 'Produk Dihapus' }}</td></tr>
                <tr><td style="padding: 8px 0; color: #64748b;">Tipe Paket</td><td style="font-weight: 600;">: {{ $order->variant->name ?? '-' }} ({{ $order->variant->duration ?? '-' }})</td></tr>
                <tr><td style="padding: 8px 0; color: #64748b;">Metode Bayar</td><td style="font-weight: 800; text-transform: uppercase; color: #f59e0b;">: {{ $order->payment_method ?? '-' }}</td></tr>
                <tr><td style="padding: 8px 0; color: #64748b;">Total Harga</td><td style="font-weight: 900; color: #10b981; font-size: 16px;">: Rp {{ number_format($order->total_price, 0, ',', '.') }}</td></tr>
            </table>
        </div>
    </div>

    <!-- KANAN: BUKTI PEMBAYARAN & KONFIRMASI STATUS -->
    <div style="flex: 1.5; min-width: 300px;">
        
        <!-- Update Status -->
        <div style="background: #fff; padding: 24px; border-radius: 12px; border: 1px solid #eee; margin-bottom: 24px; border-top: 4px solid #f59e0b;">
            <h3 style="margin-bottom: 16px; font-size: 16px;">⚙️ Aksi & Status Pesanan</h3>
            
            <div style="background: #f8fafc; padding: 12px; border-radius: 8px; margin-bottom: 16px; text-align: center;">
                <span style="display: block; font-size: 12px; color: #64748b; margin-bottom: 4px;">Status Saat Ini:</span>
                <span style="font-size: 18px; font-weight: 800; text-transform: uppercase; color: {{ strtolower($order->status) == 'selesai' ? '#10b981' : (strtolower($order->status) == 'dibatalkan' ? '#ef4444' : '#f59e0b') }};">{{ $order->status }}</span>
            </div>

            <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px;">Ubah Status Menjadi:</label>
                <select name="status" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; margin-bottom: 16px;" required>
                    <option value="menunggu pembayaran" {{ strtolower($order->status) == 'menunggu pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="diproses" {{ strtolower($order->status) == 'diproses' ? 'selected' : '' }}>Diproses (Sedang Dicek)</option>
                    <option value="selesai" {{ strtolower($order->status) == 'selesai' ? 'selected' : '' }}>Selesai (Akun Dikirim)</option>
                    <option value="dibatalkan" {{ strtolower($order->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit" style="background-color: #790D16; color: white; width: 100%; padding: 12px; font-size: 14px; border-radius: 8px; cursor: pointer; border: none; font-weight: bold; box-shadow: 0 4px 6px rgba(121, 13, 22, 0.2);">SIMPAN STATUS</button>
            </form>
        </div>

        <!-- Bukti Pembayaran -->
        <div style="background: #fff; padding: 24px; border-radius: 12px; border: 1px solid #eee;">
            <h3 style="margin-bottom: 16px; font-size: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">🧾 Bukti Pembayaran</h3>
            
            <?php if(!empty($order->payment_proof)): ?>
                <div style="text-align: center;">
                    <a href="{{ asset('storage/bukti_tf/' . $order->payment_proof) }}" target="_blank" style="display: inline-block;">
                        <img src="{{ asset('storage/bukti_tf/' . $order->payment_proof) }}" alt="Bukti Transfer" style="max-width: 100%; max-height: 250px; border-radius: 8px; border: 1px solid #ddd; object-fit: cover;">
                    </a>
                    <p style="font-size: 11px; text-align: center; color: #64748b; margin-top: 8px;">Klik gambar untuk melihat ukuran penuh</p>
                </div>
            <?php else: ?>
                <div style="background: #f8fafc; padding: 30px 20px; text-align: center; border-radius: 8px; border: 1px dashed #cbd5e1;">
                    <span style="font-size: 24px;">📄</span>
                    <p style="font-size: 13px; color: #64748b; margin-top: 8px;">Belum ada bukti pembayaran yang diunggah oleh pembeli.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
@endsection