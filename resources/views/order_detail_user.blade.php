@extends('layouts.user')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    
    <a href="{{ route('dashboard') }}" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; color: #790D16; font-weight: bold; text-decoration: none;">
        &larr; Kembali ke Riwayat Pesanan
    </a>

    @if(session('success'))
        <div style="background: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; gap: 24px; flex-wrap: wrap;">
        
        <!-- KOLOM KIRI: RINCIAN PESANAN -->
        <div style="flex: 1.2; min-width: 300px; background: #fff; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0;">
            <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; color: #1e293b;">
                📄 Rincian Pesanan
            </h3>

            <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #64748b;">No Transaksi</td>
                    <td style="font-weight: 800; color: #1e293b;">: #{{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #64748b;">Status Pesanan</td>
                    <td>: 
                        @php
                            $status = strtolower($order->status);
                            $badgeBg = $status == 'selesai' ? '#d1e7dd' : ($status == 'dibatalkan' ? '#f8d7da' : '#fff3cd');
                            $badgeColor = $status == 'selesai' ? '#0f5132' : ($status == 'dibatalkan' ? '#721c24' : '#664d03');
                        @endphp
                        <span style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; font-size: 11px; font-weight: bold; padding: 3px 8px; border-radius: 12px; text-transform: uppercase;">
                            {{ $order->status }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #64748b;">Produk</td>
                    <td style="font-weight: bold;">: {{ $order->variant->product->name ?? 'Produk' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #64748b;">Paket & Durasi</td>
                    <td style="font-weight: 600;">: {{ $order->variant->name ?? '-' }} ({{ $order->variant->duration ?? '-' }})</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #64748b;">No. WhatsApp</td>
                    <td style="font-weight: 600;">: {{ $order->whatsapp }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #64748b;">Tanggal Order</td>
                    <td style="font-weight: 600;">: {{ $order->created_at->format('d M Y, H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #64748b;">Total Bayar</td>
                    <td style="font-weight: 900; color: #10b981; font-size: 16px;">: Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            </table>

            <div style="margin-top: 20px; background: #f8fafc; padding: 14px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 12px; color: #475569;">
                💡 <strong>Catatan:</strong> Jika status pesanan sudah <strong>Selesai</strong>, detail akun akun premium telah dikirimkan ke WhatsApp Anda.
            </div>
        </div>

        <!-- KOLOM KANAN: BUKTI TF & UPLOAD REPEAT -->
        <div style="flex: 1; min-width: 300px; background: #fff; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0;">
            <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; color: #1e293b;">
                🧾 Bukti Pembayaran
            </h3>

            <!-- Tampilkan Bukti Transfer jika sudah pernah diunggah -->
            @if(!empty($order->payment_proof))
                <div style="margin-bottom: 20px; text-align: center;">
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 8px;">Bukti Pembayaran Saat Ini:</p>
                    <a href="{{ asset('storage/bukti_tf/' . $order->payment_proof) }}" target="_blank">
                        <img src="{{ asset('storage/bukti_tf/' . $order->payment_proof) }}" alt="Bukti TF" style="max-width: 100%; max-height: 220px; border-radius: 8px; border: 1px solid #ddd; object-fit: cover;">
                    </a>
                </div>
            @else
                <div style="background: #f8fafc; padding: 20px; text-align: center; border-radius: 8px; border: 1px dashed #cbd5e1; margin-bottom: 20px;">
                    <p style="font-size: 13px; color: #64748b; margin: 0;">Belum ada bukti pembayaran yang diunggah.</p>
                </div>
            @endif

            <!-- Form Upload / Ganti Bukti Transfer -->
            @if(strtolower($order->status) != 'selesai' && strtolower($order->status) != 'dibatalkan')
                <form action="{{ route('payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom: 14px;">
                        <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px; color: #333;">
                            {{ !empty($order->payment_proof) ? 'Ganti / Upload Ulang Bukti TF:' : 'Upload Bukti Transfer:' }}
                        </label>
                        <input type="file" name="payment_proof" accept="image/jpeg,image/png,image/jpg" required style="width: 100%; font-size: 13px; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; background: #fff;">
                        <small style="font-size: 11px; color: #94a3b8; display: block; margin-top: 4px;">Format: JPG, PNG, JPEG. Max: 2MB</small>
                    </div>

                    <button type="submit" style="background: #790D16; color: white; border: none; width: 100%; padding: 10px; border-radius: 20px; font-weight: bold; font-size: 13px; cursor: pointer;">
                        {{ !empty($order->payment_proof) ? '🔄 Perbarui Bukti Pembayaran' : '📤 Unggah Bukti Pembayaran' }}
                    </button>
                </form>
            @else
                <div style="background: #e2e8f0; padding: 10px; border-radius: 8px; text-align: center; font-size: 12px; color: #475569; font-weight: 600;">
                    Status pesanan ini sudah {{ $order->status }}. Bukti tidak dapat diubah lagi.
                </div>
            @endif

        </div>

    </div>
</div>
@endsection