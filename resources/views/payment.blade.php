@extends('layouts.user')

@section('title', 'Pembayaran - ' . $order->order_number)

@section('styles')
<style>
    body {
        background-color: #fdf5e2; 
    }
    .payment-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }
    
    .payment-card {
        background: #eef1f6;
        border-radius: 20px;
        padding: 40px;
        flex: 1;
        min-width: 320px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    
    .payment-card h2 {
        font-size: 22px;
        font-weight: 900;
        margin-bottom: 24px;
        color: #111;
        border-bottom: 2px solid #ddd;
        padding-bottom: 12px;
    }

    .info-group { margin-bottom: 20px; }
    .info-title { font-size: 13px; color: #777; font-weight: 600; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
    .info-value { font-size: 18px; font-weight: 700; color: #222; }
    .total-price { font-size: 36px; color: #790D16; font-weight: 900; margin-top: 5px; }
    
    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 30px;
        font-weight: bold;
        font-size: 14px;
        margin-top: 10px;
    }
    .status-pending { background-color: #fcebeb; color: #d9534f; border: 1px solid #d9534f; }
    .status-menunggu { background-color: #fcf8e3; color: #f0ad4e; border: 1px solid #f0ad4e; }

    .qris-box {
        background: #fff;
        border: 2px dashed #ccc;
        height: 280px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        overflow: hidden;
        position: relative;
    }
    .qris-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .upload-area {
        background: #fff;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
    }
    
    /* Box untuk preview gambar */
    .preview-box {
        margin-top: 16px;
        text-align: center;
        background-color: #f9f9f9;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 10px;
    }

    .btn-submit {
        background-color: #790D16;
        color: white;
        border: none;
        padding: 16px;
        border-radius: 30px;
        font-weight: bold;
        font-size: 15px;
        width: 100%;
        cursor: pointer;
        transition: 0.2s;
        box-shadow: 0 4px 10px rgba(121, 13, 22, 0.2);
    }
    .btn-submit:hover { 
        background-color: #5e0911;
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="payment-container">
    
    <!-- KARTU KIRI: RINCIAN PESANAN -->
    <div class="payment-card">
        <h2>Rincian Pesanan</h2>

        <div class="info-group">
            <div class="info-title">Nomor Transaksi</div>
            <div class="info-value">{{ $order->order_number }}</div>
        </div>

        <div class="info-group">
            <div class="info-title">Layanan & Paket</div>
            <div class="info-value">{{ $order->variant->product->name }} - {{ $order->variant->name }}</div>
            <div style="font-size: 14px; color: #555; font-weight: 600;">Durasi: {{ $order->variant->duration }}</div>
        </div>

        <div class="info-group" style="margin-top: 40px;">
            <div class="info-title">Total Tagihan</div>
            <div class="total-price">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
        </div>

        <div class="info-group">
            <div class="info-title">Status Pesanan</div>
            @if($order->status == 'pending')
                <div class="status-badge status-pending">Menunggu Pembayaran</div>
            @else
                <div class="status-badge status-menunggu">Menunggu Konfirmasi Admin</div>
            @endif
        </div>
    </div>

    <!-- KARTU KANAN: INSTRUKSI & UPLOAD -->
    <div class="payment-card">
        <h2>Instruksi Pembayaran</h2>

        <!-- FOTO QRIS/REKENING -->
        <div class="qris-box">
            <img src="{{ asset('images/qris-anda.jpg') }}" alt="QRIS / Bank" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            
            <div style="display: none; text-align: center; color: #999; font-weight: bold;">
                <span style="font-size: 24px;">📷</span><br>
                Taruh Foto QRIS/Rekening Di Sini
            </div>
        </div>

        <p style="font-size: 14px; color: #555; line-height: 1.6; margin-bottom: 24px; text-align: center;">
            Silakan transfer sesuai <strong>Total Tagihan</strong> ke rekening / QRIS di atas. Jika sudah, unggah bukti screenshot transfer Anda pada form di bawah ini.
        </p>

        <!-- Form Upload Bukti -->
        <form action="{{ route('payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if($order->payment_proof)
                <div style="text-align: center; margin-bottom: 15px; color: #49a85d; font-weight: bold; font-size: 13px;">
                    ✅ Bukti transfer sebelumnya sudah diunggah. <br>(Unggah file baru jika ingin mengganti)
                </div>
            @endif

            <div class="upload-area">
                <label style="font-size: 13px; font-weight: 600; color: #333; margin-bottom: 8px; display: block;">Upload Bukti Transfer (JPG/PNG)</label>
                <!-- Input File -->
                <input type="file" name="payment_proof" id="proofInput" required accept="image/*" style="width: 100%; font-size: 14px;">
                
                <!-- Area Preview Gambar -->
                <div class="preview-box" id="previewContainer" style="display: {{ $order->payment_proof ? 'block' : 'none' }};">
                    <span style="font-size: 12px; color: #777; display: block; margin-bottom: 8px;">Pratinjau Bukti:</span>
                    <img id="imgPreview" 
     src="{{ $order->payment_proof ? asset('storage/bukti_tf/' . $order->payment_proof) : '' }}" 
     style="max-width: 100%; max-height: 250px; border-radius: 8px; object-fit: contain;">
                </div>
            </div>
            
            <button type="submit" class="btn-submit">
                {{ $order->payment_proof ? 'Update Bukti Pembayaran' : 'Kirim Bukti Pembayaran' }}
            </button>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Logika Live Preview Gambar
    const proofInput = document.getElementById('proofInput');
    const previewContainer = document.getElementById('previewContainer');
    const imgPreview = document.getElementById('imgPreview');

    proofInput.addEventListener('change', function() {
        const file = this.files[0];
        
        if (file) {
            // Membaca file gambar yang baru dipilih user
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            // Jika user batal memilih (Cancel file explorer)
            // Kembalikan ke foto lama jika ada, atau sembunyikan kotak preview
            let oldProof = "{{ $order->payment_proof ? asset('storage/bukti_tf/' . $order->payment_proof) : '' }}";
            if(oldProof) {
                imgPreview.src = oldProof;
                previewContainer.style.display = 'block';
            } else {
                imgPreview.src = "";
                previewContainer.style.display = 'none';
            }
        }
    });
</script>
@endsection