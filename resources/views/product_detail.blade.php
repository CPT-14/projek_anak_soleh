@extends('layouts.user')

@section('title', $product->name . ' - Checkout')

@section('styles')
<style>
    body {
        background-color: #fdf5e2; 
    }
    .checkout-container {
        max-width: 1100px;
        margin: 40px auto;
        padding: 0 20px;
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }
    
    /* --- KOLOM KIRI (INFO PRODUK) --- */
    .left-col {
        flex: 1;
        min-width: 300px;
        max-width: 380px;
    }
    .product-img-box {
        background-color: #fff;
        border-radius: 20px;
        height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border: 1px solid #eaeaea;
    }
    .product-img-box img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Contain lebih aman agar logo tidak terpotong */
        padding: 20px;
    }
    .product-desc-box {
        background-color: #fff;
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid #eaeaea;
    }
    .product-desc-box h2 {
        font-size: 26px;
        font-weight: 900;
        margin-bottom: 12px;
        color: #111;
    }
    .product-desc-box p {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
    }

    /* --- KOLOM KANAN (FORM) --- */
    .right-col {
        flex: 2;
        min-width: 320px;
        background-color: #fff; 
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        border: 1px solid #eaeaea;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 30px;
    }
    .input-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .input-group label {
        font-size: 14px;
        font-weight: 700;
        color: #333;
    }
    .input-group input {
        padding: 14px 16px;
        border: 1px solid #ddd;
        border-radius: 10px;
        font-size: 14px;
        outline: none;
        background: #f8fafc;
        transition: all 0.2s;
    }
    .input-group input:focus {
        border-color: #790D16;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(121, 13, 22, 0.1);
    }
    
    /* Tombol/Kotak Buka Pop Up Paket */
    .btn-pilih-paket {
        background-color: #fff;
        border: 2px dashed #cbd5e1;
        color: #333;
        padding: 14px 16px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
    }
    .btn-pilih-paket:hover {
        border-color: #790D16;
        background-color: #fffafa;
    }

    /* Info Harga */
    .price-display {
        display: none; 
        flex-direction: column;
        gap: 4px;
        margin-top: 12px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
    .price-display span {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
    }
    .price-display .amount {
        font-size: 24px;
        color: #790D16;
        font-weight: 900;
    }

    /* Metode Pengiriman */
    .delivery-section {
        background: #f8fafc;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
    }
    .delivery-options {
        display: flex;
        gap: 20px;
        align-items: center;
        margin-top: 12px;
    }
    .delivery-options label {
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        color: #333;
        background: #fff;
        padding: 10px 16px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        transition: 0.2s;
    }
    .delivery-options label:hover {
        border-color: #790D16;
    }
    .delivery-options input[type="radio"] {
        accent-color: #790D16;
        transform: scale(1.2);
    }

    /* Tombol Submit */
    .btn-submit {
        background-color: #790D16;
        color: white;
        border: none;
        padding: 16px 30px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
        transition: all 0.2s;
        box-shadow: 0 4px 15px rgba(121, 13, 22, 0.2);
    }
    .btn-submit:hover { 
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(121, 13, 22, 0.3);
    }

    /* --- POP UP MODAL --- */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(4px); 
        display: none; justify-content: center; align-items: center; z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .modal-overlay.active { 
        display: flex; 
        opacity: 1;
    }
    
    .modal-box {
        background: #ffffff;
        padding: 30px;
        border-radius: 24px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        transform: scale(0.95);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }
    .modal-overlay.active .modal-box {
        transform: scale(1);
    }
    
    .modal-box h3 {
        text-align: center;
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 24px;
        color: #1e293b;
    }
    
    .variant-container {
        overflow-y: auto;
        padding-right: 8px;
        margin-bottom: 24px;
    }
    
    .variant-row {
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .variant-row:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .variant-row input[type="radio"] {
        margin-right: 16px;
        transform: scale(1.4);
        accent-color: #790D16;
        cursor: pointer;
    }
    .variant-title { font-size: 16px; font-weight: 700; color: #1e293b; display: flex; align-items: center; }
    .variant-price { font-size: 16px; font-weight: 900; color: #10b981; }
    
    .modal-actions {
        display: flex;
        gap: 16px;
        margin-top: auto;
    }
    .btn-modal {
        flex: 1;
        padding: 14px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 15px;
        cursor: pointer;
        text-align: center;
        border: none;
        transition: all 0.2s;
    }
    .btn-modal-cancel {
        background-color: #f1f5f9;
        color: #64748b;
    }
    .btn-modal-cancel:hover {
        background-color: #e2e8f0;
        color: #334155;
    }
    .btn-modal-confirm {
        background-color: #790D16;
        color: white;
    }
    .btn-modal-confirm:hover {
        background-color: #5e0911;
    }

    @media (max-width: 768px) {
        .checkout-container { margin: 20px auto; flex-direction: column; gap: 20px; padding: 0 16px; }
        .left-col { max-width: 100%; }
        .right-col { min-width: 100%; padding: 24px; }
        .form-grid { grid-template-columns: 1fr; gap: 16px; margin-bottom: 24px; }
        .product-img-box { height: 250px; }
        .delivery-options { flex-direction: column; align-items: stretch; gap: 10px; }
    }
</style>
@endsection

@section('content')
<div class="checkout-container">
    
    <!-- KOLOM KIRI -->
    <div class="left-col">
        <div class="product-img-box">
            @if($product->image)
                <img src="{{ $product->image }}" alt="{{ $product->name }}">
            @else
                <h1 style="color: #E50914; font-size: 150px; font-family: 'Arial Black', sans-serif; margin: 0;">N</h1>
            @endif
        </div>
        <div class="product-desc-box">
            <h2>{{ $product->name }}</h2>
            <p>{{ $product->description }}</p>
        </div>
    </div>

    <!-- KOLOM KANAN (FORM) -->
    <div class="right-col">
        <!-- Error validasi dari server -->
        @if ($errors->any())
            <div style="background-color: #fef2f2; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 24px; border: 1px solid #fecaca;">
                <div style="font-weight: bold; margin-bottom: 8px;">⚠️ Terdapat Kesalahan:</div>
                <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('order.create') }}" method="POST" id="checkoutForm">
            @csrf
            <!-- Hidden input untuk menangkap ID Varian dari Modal -->
            <input type="hidden" name="variant_id" id="inputVariantId">

            <div class="form-grid">
                <!-- Sisi Kiri Form -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div class="input-group">
                        <label>Email Pengiriman</label>
                        <input type="email" name="email" id="emailInput" placeholder="contoh@gmail.com">
                    </div>
                    <div class="input-group">
                        <label>Nama Pengguna</label>
                        <input type="text" name="username" placeholder="Nama Anda" required>
                    </div>
                    <div class="input-group">
                        <label>No WhatsApp</label>
                        <!-- BUG FIXED: NAME UDAH DIGANTI JADI contact_wa -->
                        <input type="tel" name="contact_wa" id="waInput" placeholder="Contoh: 08123456789" required>
                    </div>
                </div>

                <!-- Sisi Kanan Form -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    
                    <div class="input-group">
                        <label>Pilih Paket</label>
                        <div class="btn-pilih-paket" id="btnOpenModal">
                            <span id="paketText" style="color: #94a3b8;">🔍 Klik untuk pilih paket...</span>
                            <span style="font-size: 12px; color: #94a3b8;">▼</span>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label>Durasi Aktif</label>
                        <input type="text" id="displayDurasi" readonly placeholder="-" style="background:#f1f5f9; color:#94a3b8; cursor:not-allowed; border: 1px solid #e2e8f0; font-weight: 600;">
                    </div>

                    <div class="price-display" id="priceDisplay">
                        <span>Total Harga Paket</span>
                        <div class="amount" id="displayHarga">Rp 0</div>
                    </div>
                </div>
            </div>

            <!-- Metode Pengiriman -->
            <div class="delivery-section">
                <span style="font-size: 14px; font-weight: 800; color: #1e293b;">Pilihan Metode Pengiriman Akun</span>
                <div class="delivery-options">
                    <label>
                        <input type="radio" name="delivery_method" value="whatsapp" checked>
                        Kirim via WhatsApp
                    </label>
                    <label>
                        <input type="radio" name="delivery_method" value="email">
                        Kirim via Email
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="btnSubmit">Lanjutkan ke Pembayaran &rarr;</button>
        </form>
    </div>

</div>

<!-- ============================================== -->
<!-- POP UP MODAL PILIH PAKET -->
<!-- ============================================== -->
<div class="modal-overlay" id="modalPaket">
    <div class="modal-box">
        <h3>Pilih Paket {{ $product->name }}</h3>
        
        <div class="variant-container">
            @forelse($product->variants as $variant)
            <label class="variant-row">
                <div class="variant-title">
                    <input type="radio" name="modal_variant" 
                        value="{{ $variant->id }}" 
                        data-name="{{ $variant->name }}"
                        data-durasi="{{ $variant->duration }}"
                        data-harga="{{ number_format($variant->price, 0, ',', '.') }}">
                    <div>
                        <div style="margin-bottom: 2px;">{{ $variant->name }}</div>
                        <div style="font-size: 12px; color: #64748b; font-weight: normal;">Durasi: {{ $variant->duration }}</div>
                    </div>
                </div>
                <div class="variant-price">
                    Rp {{ number_format($variant->price, 0, ',', '.') }}
                </div>
            </label>
            @empty
            <div style="text-align:center; padding: 20px; background: #fef2f2; border-radius: 12px;">
                <p style="color:#ef4444; font-weight:bold; margin: 0;">Maaf, paket untuk produk ini belum tersedia.</p>
            </div>
            @endforelse
        </div>

        <div class="modal-actions">
            <button type="button" class="btn-modal btn-modal-cancel" id="btnBatalModal">Batal</button>
            <button type="button" class="btn-modal btn-modal-confirm" id="btnKonfirmasiPaket">Konfirmasi</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- 1. LOGIKA MODAL PAKET ---
        const btnOpenModal = document.getElementById('btnOpenModal');
        const modalPaket = document.getElementById('modalPaket');
        const btnKonfirmasiPaket = document.getElementById('btnKonfirmasiPaket');
        const btnBatalModal = document.getElementById('btnBatalModal');
        
        const inputVariantId = document.getElementById('inputVariantId');
        const paketText = document.getElementById('paketText');
        const displayDurasi = document.getElementById('displayDurasi');
        const displayHarga = document.getElementById('displayHarga');
        const priceDisplayBox = document.getElementById('priceDisplay');

        // Buka Modal
        btnOpenModal.addEventListener('click', function() {
            modalPaket.classList.add('active');
        });

        // Tutup Modal
        btnBatalModal.addEventListener('click', function() {
            modalPaket.classList.remove('active');
        });

        // Efek visual saat radio button dipilih
        const radioVariants = document.querySelectorAll('input[name="modal_variant"]');
        radioVariants.forEach(radio => {
            radio.addEventListener('change', function() {
                // Reset semua border
                document.querySelectorAll('.variant-row').forEach(row => {
                    row.style.borderColor = '#e2e8f0';
                    row.style.backgroundColor = '#fff';
                });
                // Highlight yang dipilih
                if(this.checked) {
                    this.closest('.variant-row').style.borderColor = '#790D16';
                    this.closest('.variant-row').style.backgroundColor = '#fffafa';
                }
            });
        });

        // Konfirmasi Pilihan
        btnKonfirmasiPaket.addEventListener('click', function() {
            const selectedRadio = document.querySelector('input[name="modal_variant"]:checked');
            
            if (!selectedRadio) {
                alert('Silakan pilih salah satu paket terlebih dahulu!');
                return;
            }

            const vId = selectedRadio.value;
            const vName = selectedRadio.getAttribute('data-name');
            const vDurasi = selectedRadio.getAttribute('data-durasi');
            const vHarga = selectedRadio.getAttribute('data-harga');

            inputVariantId.value = vId;
            
            // Ubah tampilan kotak tombol
            paketText.innerText = vName;
            paketText.style.color = '#1e293b';
            paketText.style.fontWeight = '800';
            btnOpenModal.style.borderColor = '#10b981';
            btnOpenModal.style.backgroundColor = '#f0fdf4';
            
            displayDurasi.value = vDurasi; 
            displayDurasi.style.color = '#1e293b';
            
            displayHarga.innerText = 'Rp ' + vHarga; 
            priceDisplayBox.style.display = 'flex';

            modalPaket.classList.remove('active');
        });

        // Tutup modal area luar
        modalPaket.addEventListener('click', function(e) {
            if (e.target === modalPaket) {
                modalPaket.classList.remove('active');
            }
        });

        // --- 2. LOGIKA VALIDASI FORM ---
        const form = document.getElementById('checkoutForm');
        const waInput = document.getElementById('waInput');
        const emailInput = document.getElementById('emailInput');

        form.addEventListener('submit', function(e) {
            if (!inputVariantId.value) {
                e.preventDefault();
                alert('Anda belum memilih Paket! Silakan klik tombol Pilih Paket.');
                modalPaket.classList.add('active');
                return;
            }

            const selectedDelivery = document.querySelector('input[name="delivery_method"]:checked').value;
            
            if (selectedDelivery === 'whatsapp' && waInput.value.trim() === '') {
                e.preventDefault();
                alert('Metode pengiriman via WhatsApp dipilih, No WhatsApp Wajib diisi!');
                waInput.focus();
                return;
            }

            if (selectedDelivery === 'email' && emailInput.value.trim() === '') {
                e.preventDefault();
                alert('Metode pengiriman via Email dipilih, Email Wajib diisi!');
                emailInput.focus();
                return;
            }
        });
    });
</script>
@endsection