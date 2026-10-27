@extends('layouts.user')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="container-responsive" style="max-width: 900px; margin: 20px auto;">
    
    <a href="{{ route('dashboard') }}" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; color: #790D16; font-weight: bold; text-decoration: none;">
        &larr; Kembali ke Riwayat Pesanan
    </a>

    <!-- HANYA MUNCUL SAAT PESANAN BARU DIBUAT (Belum ada bukti TF) -->
    @if(session('success') && empty($order->payment_proof))
        <div style="background: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="flex-desktop-row" style="display: flex; gap: 24px; flex-wrap: wrap;">
        
        <!-- KOLOM KIRI: RINCIAN PESANAN -->
        <div style="flex: 1.2; background: #fff; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; width: 100%; min-width: 320px;">
            <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; color: #1e293b;">
                📄 Rincian Pesanan
            </h3>

            <div class="table-responsive">
                <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; color: #64748b; width: 110px;">No Transaksi</td>
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
            </div>

            <div style="margin-top: 20px; background: #f8fafc; padding: 14px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 12px; color: #475569;">
                💡 <strong>Catatan:</strong> Jika status pesanan sudah <strong>Selesai</strong>, detail akun premium telah dikirimkan ke WhatsApp Anda.
            </div>

            <!-- === TOMBOL HUBUNGI ADMIN (PERMANEN DI BAWAH RINCIAN PESANAN) === -->
            @if(!empty($order->payment_proof))
                <div style="margin-top: 16px;">
                    <!-- Silakan ganti nomor WhatsApp admin (contoh: 6281234567890) di URL bawah ini -->
                    <a href="https://wa.me/6285134889725?text=Halo%20Admin,%20saya%20sudah%20mengunggah%20bukti%20pembayaran%20untuk%20pesanan%20%23{{ $order->order_number }}" target="_blank" style="display: block; width: 100%; background: #25d366; color: white; text-align: center; padding: 12px; border-radius: 10px; font-weight: bold; font-size: 14px; text-decoration: none; box-shadow: 0 4px 10px rgba(37, 211, 102, 0.2);">
                        💬 Hubungi Admin
                    </a>
                </div>
            @endif
        </div>

        <!-- KOLOM KANAN: BUKTI TF & UPLOAD REPEAT -->
        <div style="flex: 1; background: #fff; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; width: 100%; min-width: 320px;">
            <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; color: #1e293b;">
                🧾 Bukti Pembayaran
            </h3>

            <!-- === KOTAK QRIS === -->
            @if(strtolower($order->status) != 'selesai' && strtolower($order->status) != 'dibatalkan')
            <div style="background: #fafafa; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 16px; text-align: center; margin-bottom: 20px;">
                <p style="font-size: 13px; font-weight: 600; margin-bottom: 10px;">Silakan Scan QRIS Berikut:</p>
                <img src="{{ asset('images/QRHARAM.jpeg') }}" alt="QRIS" style="width: 150px; height: 150px; object-fit: contain; margin: 0 auto; background: white; border-radius: 8px; padding: 5px; border: 1px solid #ddd;">
                <p style="font-size: 11px; color: #64748b; margin-top: 8px;">Mendukung GoPay, OVO, DANA & M-Banking</p>
            </div>
            @endif

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
                        
                        <!-- Input File dengan ID untuk Preview -->
                        <input type="file" name="payment_proof" id="paymentProofInput" accept="image/jpeg,image/png,image/jpg" required style="width: 100%; font-size: 13px; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; background: #fff;">
                        <small style="font-size: 11px; color: #94a3b8; display: block; margin-top: 4px;">Format: JPG, PNG, JPEG. Max: 2MB</small>

                        <!-- CONTAINER PREVIEW GAMBAR INSTAN -->
                        <div id="imagePreviewContainer" style="display: none; margin-top: 14px; text-align: center; background: #fafafa; padding: 10px; border-radius: 8px; border: 1px dashed #790D16;">
                            <p style="font-size: 11px; color: #790D16; font-weight: bold; margin-bottom: 6px;">Preview Bukti Pembayaran Dipilih:</p>
                            <img id="imagePreview" src="" alt="Preview Bukti TF" style="max-width: 100%; max-height: 200px; border-radius: 6px; object-fit: cover;">
                        </div>
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

<!-- === HANYA MUNCUL JIKA UPLOAD BUKTI TF BERHASIL === -->
@if(session('success') && !empty($order->payment_proof))
<div id="successModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); display: flex; justify-content: center; align-items: center; z-index: 9999;">
    <div style="background: white; padding: 30px; border-radius: 20px; max-width: 420px; width: 90%; text-align: center; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
        <div style="font-size: 48px; margin-bottom: 12px;">✅</div>
        <h3 style="font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 10px;">Berhasil!</h3>
        <p style="font-size: 14px; color: #475569; margin-bottom: 24px; line-height: 1.5;">
            Bukti transfer berhasil diperbarui! Mohon tunggu konfirmasi admin.
        </p>
        
        <!-- === TOMBOL DIDALAM POP UP === -->
        <div style="display: flex; gap: 10px; flex-direction: column;">
            <a href="https://wa.me/6285134889725?text=Halo%20Admin,%20saya%20sudah%20mengunggah%20bukti%20pembayaran%20untuk%20pesanan%20%23{{ $order->order_number }}" target="_blank" style="background: #25d366; color: white; text-align: center; padding: 12px; border-radius: 12px; font-weight: bold; font-size: 14px; text-decoration: none;">
                💬 Hubungi Admin
            </a>
            <button onclick="document.getElementById('successModal').style.display='none'" style="background: #f1f5f9; color: #334155; border: none; padding: 12px 28px; border-radius: 12px; font-weight: bold; font-size: 14px; cursor: pointer;">
                Nanti Saja
            </button>
        </div>
    </div>
</div>
@endif

<!-- === SCRIPT UNTUK PREVIEW GAMBAR === -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById('paymentProofInput');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImage = document.getElementById('imagePreview');

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewContainer.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.style.display = 'none';
                    previewImage.src = '';
                }
            });
        }
    });
</script>
@endsection