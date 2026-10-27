@extends('layouts.admin')
@section('title', 'Detail Pesanan #' . ($order->order_number ?? $order->id))

@section('styles')
<style>
    /* Layout Flexbox Utama: Menumpuk di HP, Berdampingan di Laptop */
    .detail-grid {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    @media (min-width: 768px) {
        .detail-grid {
            flex-direction: row;
            align-items: flex-start;
        }
    }
    
    .detail-card {
        background: #fff;
        padding: 24px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
    }
    .info-label { color: #64748b; font-weight: 500; }
    .info-value { color: #1e293b; font-weight: 700; text-align: right; }
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('admin.orders') }}" style="color: #790D16; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
        &larr; Kembali ke Kelola Pesanan
    </a>
    <!-- MENGUBAH JUDUL AGAR MENAMPILKAN ORDER NUMBER -->
    <h1 style="font-size: 24px; font-weight: 800; color: #1e293b;">Detail Pesanan #{{ $order->order_number ?? 'ORD-'.$order->id }} 🧾</h1>
    <p style="color: #64748b; font-size: 14px;">Validasi bukti pembayaran dan tentukan akun untuk pelanggan.</p>
</div>

@if(session('success'))
    <div style="background: #d1e7dd; color: #0f5132; padding: 14px 18px; border-radius: 12px; margin-bottom: 24px; font-weight: bold; border: 1px solid #badbcc;">
        ✅ {{ session('success') }}
    </div>
@endif

<!-- GRID UTAMA: KE BAWAH DI HP, BERDAMPINGAN DI DESKTOP -->
<div class="detail-grid">

    <!-- KOLOM KIRI: RINCIAN PRODUK & PEMBELI -->
    <div style="flex: 1; display: flex; flex-direction: column; gap: 24px; min-width: 300px;">
        
        <!-- Info Pembeli -->
        <div class="detail-card" style="border-top: 4px solid #2563eb;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 16px;">👤 Informasi Pembeli</h3>
            <div class="info-row">
                <span class="info-label">Nama Akun</span>
                <span class="info-value">{{ $order->user->name ?? 'User Dihapus' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $order->user->email ?? '-' }}</span>
            </div>
            
            <!-- NOMOR WHATSAPP REDIRECT START -->
            <div class="info-row" style="border-bottom: none;">
                <span class="info-label">No WhatsApp</span>
                <span class="info-value">
                    @if(!empty($order->whatsapp))
                        @php
                            // Format nomor WA agar kompatibel dengan tautan (wa.me)
                            $waClean = preg_replace('/[^0-9]/', '', $order->whatsapp);
                            if (substr($waClean, 0, 1) === '0') {
                                $waClean = '62' . substr($waClean, 1);
                            }
                            
                            // Pesan otomatis (opsional)
                            $waMessage = urlencode("Halo Kak " . ($order->user->name ?? 'Pelanggan') . ", ini terkait pesanan #" . $order->order_number . " untuk paket " . ($order->variant->product->name ?? '') . ".");
                        @endphp
                        
                        <a href="https://wa.me/{{ $waClean }}?text={{ $waMessage }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; background: #25d366; color: white; padding: 6px 12px; border-radius: 20px; font-size: 13px; text-decoration: none; font-weight: bold; box-shadow: 0 2px 4px rgba(37,211,102,0.2);">
                            💬 {{ $order->whatsapp }} &rarr;
                        </a>
                    @else
                        -
                    @endif
                </span>
            </div>
            <!-- NOMOR WHATSAPP REDIRECT END -->
            
        </div>

        <!-- Rincian Produk -->
        <div class="detail-card" style="border-top: 4px solid #10b981;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 16px;">📦 Rincian Pesanan</h3>
            
            <!-- === TAMBAHAN KODE TRANSAKSI DI SINI === -->
            <div class="info-row">
                <span class="info-label">Kode Transaksi</span>
                <span class="info-value" style="font-family: monospace; font-size: 15px; color: #0f172a;">
                    #{{ $order->order_number ?? '-' }}
                </span>
            </div>
            <!-- ======================================= -->
            
            <div class="info-row">
                <span class="info-label">Produk</span>
                <span class="info-value" style="color: #790D16;">{{ $order->variant->product->name ?? 'Produk Dihapus' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Paket / Durasi</span>
                <span class="info-value">{{ $order->variant->name ?? '-' }} ({{ $order->variant->duration ?? '-' }})</span>
            </div>
            <div class="info-row">
                <span class="info-label">Metode Bayar</span>
                <span class="info-value" style="text-transform: uppercase;">{{ $order->payment_method ?? 'QRIS / Transfer' }}</span>
            </div>
            <div class="info-row" style="border-bottom: none;">
                <span class="info-label">Total Harga</span>
                <span class="info-value" style="color: #10b981; font-size: 16px;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

    </div>

    <!-- KOLOM KANAN: BUKTI PEMBAYARAN & UBAH STATUS -->
    <div style="flex: 1.2; display: flex; flex-direction: column; gap: 24px; min-width: 300px;">
        
        <!-- Bukti Transfer -->
        <div class="detail-card" style="border-top: 4px solid #f59e0b;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 16px;">🖼️ Bukti Pembayaran</h3>
            @if($order->payment_proof)
                <div style="text-align: center; background: #f8fafc; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <a href="{{ asset('storage/bukti_tf/' . $order->payment_proof) }}" target="_blank">
                        <!-- Mengasumsikan folder bukti_tf sama seperti di halaman user -->
                        <img src="{{ asset('storage/bukti_tf/' . $order->payment_proof) }}" alt="Bukti Transfer" style="max-width: 100%; max-height: 250px; border-radius: 8px; object-fit: contain; cursor: zoom-in;">
                    </a>
                    <p style="font-size: 12px; color: #64748b; margin-top: 8px;">Klik gambar untuk memperbesar</p>
                </div>
            @else
                <div style="background: #fef2f2; color: #dc2626; padding: 20px; border-radius: 12px; text-align: center; font-size: 14px; font-weight: 600;">
                    ❌ Pelanggan belum mengunggah bukti pembayaran.
                </div>
            @endif
        </div>

        <!-- Form Update Status -->
        <div class="detail-card" style="border-top: 4px solid #790D16;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 16px;">⚙️ Kelola Status Pesanan</h3>
            
            <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Ubah Status Pesanan</label>
                    <select name="status" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 14px; font-weight: bold; outline: none;">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                        <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>Diproses (Cek Bukti)</option>
                        <option value="selesai" {{ $order->status == 'selesai' || $order->status == 'success' ? 'selected' : '' }}>Selesai / Berhasil</option>
                        <option value="dibatalkan" {{ $order->status == 'dibatalkan' || $order->status == 'failed' ? 'selected' : '' }}>Dibatalkan / Gagal</option>
                    </select>
                </div>

                <button type="submit" style="width: 100%; background: #790D16; color: white; border: none; padding: 14px; border-radius: 10px; font-weight: bold; font-size: 15px; cursor: pointer; transition: 0.2s;">
                    Simpan Perubahan Status
                </button>
            </form>
        </div>

    </div>

</div>
@endsection