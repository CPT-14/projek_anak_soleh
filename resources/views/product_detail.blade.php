@extends('layouts.user')

@section('title', $product->name . ' - Checkout')

@section('styles')
<style>
    body {
        background-color: #fdf5e2; /* Warna krem background sesuai desain */
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
        max-width: 350px;
    }
    .product-img-box {
        background-color: #000;
        border-radius: 16px;
        height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .product-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Diubah dari contain menjadi cover agar pas memenuhi kotak */
    }
    .product-desc-box {
        background-color: #eef1f6;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
    }
    .product-desc-box h2 {
        font-size: 24px;
        font-weight: 900;
        margin-bottom: 12px;
        color: #222;
    }
    .product-desc-box p {
        font-size: 13px;
        color: #555;
        line-height: 1.6;
    }

    /* --- KOLOM KANAN (FORM) --- */
    .right-col {
        flex: 2;
        min-width: 400px;
        background-color: #eef1f6; /* Warna abu-abu muda form */
        border-radius: 20px;
        padding: 40px;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    .input-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .input-group label {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }
    .input-group input {
        padding: 12px 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        background: #fff;
        transition: 0.2s;
    }
    .input-group input:focus {
        border-color: #790D16;
    }
    
    /* Tombol/Kotak Buka Pop Up Paket */
    .btn-pilih-paket {
        background-color: #fff;
        border: 1px solid #ddd;
        color: #333;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: border-color 0.2s;
    }
    .btn-pilih-paket:hover {
        border-color: #790D16;
    }

    /* Info Harga yang muncul setelah pilih paket */
    .price-display {
        display: none; 
        flex-direction: column;
        gap: 4px;
        margin-top: 10px;
    }
    .price-display span {
        font-size: 14px;
        font-weight: bold;
        color: #333;
    }
    .price-display .amount {
        font-size: 22px;
        color: #d9534f;
        font-weight: bold;
    }

    /* Metode Pengiriman */
    .delivery-options {
        display: flex;
        gap: 20px;
        align-items: center;
        margin-top: 10px;
    }
    .delivery-options label {
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        color: #666;
    }

    /* Tombol Submit */
    .btn-submit {
        background-color: #790D16;
        color: white;
        border: none;
        padding: 14px 30px;
        border-radius: 30px;
        font-weight: bold;
        font-size: 14px;
        cursor: pointer;
        margin-top: 20px;
        transition: 0.2s;
        box-shadow: 0 4px 10px rgba(121, 13, 22, 0.2);
    }
    .btn-submit:hover { 
        opacity: 0.9; 
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(121, 13, 22, 0.3);
    }

    /* --- POP UP MODAL (Desain Diperbarui) --- */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.55);
        backdrop-filter: blur(3px); /* Efek blur pada background belakang */
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
        padding: 40px;
        border-radius: 24px;
        width: 90%;
        max-width: 550px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        transform: translateY(-20px);
        transition: transform 0.3s ease;
    }
    .modal-overlay.active .modal-box {
        transform: translateY(0);
    }
    
    .modal-box h3 {
        text-align: center;
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 24px;
        color: #111;
    }
    
    .variant-row {
        background: #fafafa;
        border: 2px solid #eaeaea;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .variant-row:hover {
        border-color: #790D16;
        background: #fdf8f8;
    }
    .variant-row input[type="radio"] {
        margin-right: 14px;
        transform: scale(1.3);
        accent-color: #790D16;
        cursor: pointer;
    }
    .variant-title { font-size: 15px; font-weight: 600; color: #333; display: flex; align-items: center; }
    .variant-price { font-size: 16px; font-weight: bold; color: #d9534f; }
    
    /* Tata Letak Tombol Modal yang Seimbang */
    .modal-actions {
        display: flex;
        gap: 16px;
        margin-top: 32px;
    }
    .btn-modal {
        flex: 1; /* Membuat tombol membagi ruang sama rata */
        padding: 14px;
        border-radius: 30px;
        font-weight: bold;
        font-size: 15px;
        cursor: pointer;
        text-align: center;
        border: none;
        transition: all 0.2s;
    }
    .btn-modal-cancel {
        background-color: #f1f1f1;
        color: #555;
    }
    .btn-modal-cancel:hover {
        background-color: #e4e4e4;
        color: #333;
    }
    .btn-modal-confirm {
        background-color: #790D16;
        color: white;
        box-shadow: 0 4px 10px rgba(121, 13, 22, 0.2);
    }
    .btn-modal-confirm:hover {
        background-color: #5e0911;
        box-shadow: 0 6px 15px rgba(121, 13, 22, 0.3);
    }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .checkout-container { margin: 20px auto; flex-direction: column; }
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
                <h1 style="color: #E50914; font-size: 200px; font-family: 'Arial Black', sans-serif; margin: 0;">N</h1>
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
            <div style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 12px; margin-bottom: 24px; border: 1px solid #f5c6cb;">
                <ul style="margin: 0; padding-left: 20px; font-size: 13px; font-weight: 500;">
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
                        <label>Email</label>
                        <input type="email" name="email" id="emailInput" placeholder="contoh@gmail.com">
                    </div>
                    <div class="input-group">
                        <label>Nama Pengguna</label>
                        <input type="text" name="username" placeholder="Nama Anda" required>
                    </div>
                    <div class="input-group">
                        <label>No WhatsApp</label>
                        <input type="tel" name="contact_info" id="waInput" placeholder="08123456789">
                    </div>
                </div>

                <!-- Sisi Kanan Form -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    
                    <div class="input-group">
                        <label>Paket</label>
                        <!-- Kotak ini memicu Pop-up Modal -->
                        <div class="btn-pilih-paket" id="btnOpenModal">
                            <span id="paketText" style="color: #999;">Pilih paket di sini...</span>
                            <span style="font-size: 10px; color: #999;">▼</span>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label>Durasi</label>
                        <input type="text" id="displayDurasi" readonly placeholder="Pilih paket dahulu..." style="background:#f4f4f4; color:#999; cursor:not-allowed; border: 1px solid #eaeaea;">
                    </div>

                    <div class="price-display" id="priceDisplay">
                        <span>HARGA</span>
                        <div class="amount" id="displayHarga">Rp. 0</div>
                    </div>
                </div>
            </div>

            <!-- Bagian Bawah Form -->
            <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
                <button type="submit" class="btn-submit" id="btnSubmit">Lanjutkan Pembayaran</button>
                
                <div>
                    <span style="font-size: 13px; font-weight: bold; color: #333;">Pilihan metode pengiriman akun</span>
                    <div class="delivery-options">
                        <label>
                            <input type="radio" name="delivery_method" value="whatsapp" checked>
                            WhatsApp
                        </label>
                        <label>
                            <input type="radio" name="delivery_method" value="email">
                            Email
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>

<!-- ============================================== -->
<!-- POP UP MODAL PILIH PAKET -->
<!-- ============================================== -->
<div class="modal-overlay" id="modalPaket">
    <div class="modal-box">
        <h3>Konfirmasi Paket</h3>
        
        @forelse($product->variants as $variant)
        <label class="variant-row">
            <div class="variant-title">
                <input type="radio" name="modal_variant" 
                       value="{{ $variant->id }}" 
                       data-name="{{ $variant->name }}"
                       data-durasi="{{ $variant->duration }}"
                       data-harga="{{ number_format($variant->price, 0, ',', '.') }}">
                {{ $product->name }} {{ $variant->name }} - {{ $variant->duration }}
            </div>
            <div class="variant-price">
                Rp {{ number_format($variant->price, 0, ',', '.') }}
            </div>
        </label>
        @empty
        <p style="text-align:center; color:#d9534f; font-weight:bold;">Maaf, paket untuk produk ini belum tersedia.</p>
        @endforelse

        <!-- Tombol Batal & Konfirmasi (Desain Rapi Sejajar) -->
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

        // Tutup Modal jika klik tombol Batal
        btnBatalModal.addEventListener('click', function() {
            modalPaket.classList.remove('active');
        });

        // Konfirmasi Pilihan & Tutup Modal
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
            
            // Ubah teks di dalam kotak menjadi nama paket yang dipilih
            paketText.innerText = vName;
            paketText.style.color = '#333';
            paketText.style.fontWeight = '700';
            
            displayDurasi.value = vDurasi; 
            displayDurasi.style.color = '#333';
            displayDurasi.style.fontWeight = '600';
            
            displayHarga.innerText = 'Rp ' + vHarga; 
            priceDisplayBox.style.display = 'flex';

            // Tutup Modal
            modalPaket.classList.remove('active');
        });

        // Tutup modal jika area gelap di luar modal diklik
        modalPaket.addEventListener('click', function(e) {
            if (e.target === modalPaket) {
                modalPaket.classList.remove('active');
            }
        });

        // --- 2. LOGIKA VALIDASI FORM & METODE PENGIRIMAN ---
        const form = document.getElementById('checkoutForm');
        const waInput = document.getElementById('waInput');
        const emailInput = document.getElementById('emailInput');

        form.addEventListener('submit', function(e) {
            if (!inputVariantId.value) {
                e.preventDefault();
                alert('Anda harus memilih Paket terlebih dahulu sebelum melanjutkan!');
                return;
            }

            const selectedDelivery = document.querySelector('input[name="delivery_method"]:checked').value;
            
            if (selectedDelivery === 'whatsapp' && waInput.value.trim() === '') {
                e.preventDefault();
                alert('Karena Anda memilih pengiriman via WhatsApp, No WhatsApp wajib diisi!');
                waInput.focus();
                return;
            }

            if (selectedDelivery === 'email' && emailInput.value.trim() === '') {
                e.preventDefault();
                alert('Karena Anda memilih pengiriman via Email, Alamat Email wajib diisi!');
                emailInput.focus();
                return;
            }
        });
    });
</script>
@endsection