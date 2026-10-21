@extends('layouts.user')

@section('title', 'Pembayaran - QITSPREM')

@section('styles')
<style>
    /* HANYA CSS UNTUK CHECKOUT SAJA */
    .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
    .btn-back { display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); text-decoration: none; font-weight: 600; margin-bottom: 20px; transition: 0.2s; }
    .btn-back:hover { color: var(--brand-hover); }

    .checkout-grid { display: grid; grid-template-columns: 1fr; gap: 24px; }
    @media (min-width: 768px) { .checkout-grid { grid-template-columns: 1.5fr 1fr; } }

    .card { background: var(--card-bg); border-radius: 16px; padding: 24px; border: 1px solid var(--border-color); margin-bottom: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
    .card-title { font-size: 16px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }

    .product-info { display: flex; align-items: center; gap: 16px; }
    .product-img { width: 60px; height: 60px; border-radius: 12px; object-fit: cover; }
    .product-details h4 { font-size: 18px; margin-bottom: 4px; }
    .product-details p { font-size: 13px; color: var(--text-muted); }

    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; }
    .form-control { width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; outline: none; transition: 0.2s; background: #fafafa; }
    .form-control:focus { border-color: var(--brand-hover); background: #fff; }
    .form-control[readonly] { background: #f0f0f0; cursor: not-allowed; color: #666; }

    .summary-table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .summary-table td { padding: 10px 0; color: var(--text-muted); }
    .summary-table .text-right { text-align: right; color: var(--text-dark); font-weight: 500; }
    .summary-table .total-row td { padding-top: 16px; border-top: 1px dashed var(--border-color); font-size: 16px; font-weight: 700; color: var(--text-dark); }
    .summary-table .total-price { color: var(--brand-hover) !important; font-size: 18px !important; }

    .method-selector { display: flex; gap: 12px; margin-bottom: 20px; }
    .method-card { flex: 1; border: 2px solid var(--border-color); border-radius: 12px; padding: 16px; text-align: center; cursor: pointer; transition: 0.2s; background: #fff; }
    .method-card img { height: 24px; margin-bottom: 8px; }
    .method-card i { font-size: 24px; color: #555; margin-bottom: 8px; display: block; }
    .method-card span { display: block; font-size: 13px; font-weight: 600; }
    .method-card.active { border-color: var(--brand-hover); background-color: #fcf4f5; }
    
    .payment-detail { display: none; background: #fafafa; padding: 20px; border-radius: 12px; border: 1px solid var(--border-color); text-align: center; margin-bottom: 20px; }
    .payment-detail.active { display: block; }
    .qris-img { width: 160px; height: 160px; margin: 10px auto; border-radius: 12px; border: 1px solid #ddd; padding: 10px; background: white; }
    
    .bank-list { text-align: left; }
    .bank-item { display: flex; justify-content: space-between; align-items: center; padding: 12px; border-bottom: 1px solid #eee; }
    .bank-item:last-child { border-bottom: none; }
    
    .btn-submit { width: 100%; background: var(--brand-hover); color: white; border: none; padding: 14px; border-radius: 8px; font-size: 15px; font-weight: 700; cursor: pointer; transition: 0.2s; }
    .btn-submit:hover { opacity: 0.9; }
</style>
@endsection

@section('content')
<!-- ISI HALAMAN CHECKOUT -->
<div class="container">
    <a href="{{ route('dashboard') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog</a>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="checkout-grid">
            
            <!-- KOLOM KIRI: INFO PRODUK & BIODATA -->
            <div class="checkout-left">
                <div class="card">
                    <div class="product-info">
                        <img src="{{ !empty($product->image) ? $product->image : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&background=c06c74&color=fff' }}" alt="Produk" class="product-img">
                        <div class="product-details">
                            <h4>{{ $product->name }}</h4>
                            <p>{{ $product->category }}</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h3 class="card-title"><i class="fa-solid fa-address-card"></i> Informasi Pemesan</h3>
                    <div class="form-group">
                        <label class="form-label">Nama Akun (Otomatis)</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Anda</label>
                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor WhatsApp (Aktif)</label>
                        <input type="number" name="contact_info" class="form-control" placeholder="Contoh: 081234567890" value="{{ old('contact_info') }}" required>
                        @error('contact_info')
                            <div style="color: red; margin-top: 5px; font-size: 13px;">{{ $message }}</div>
                        @enderror
                        <small style="color: var(--text-muted); font-size: 11px; margin-top: 4px; display: block;">*Akun premium akan dikirimkan ke nomor WA ini.</small>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: PEMBAYARAN & TOTAL -->
            <div class="checkout-right">
                <div class="card">
                    <h3 class="card-title"><i class="fa-solid fa-wallet"></i> Metode Pembayaran</h3>
                    <div class="method-selector">
                        <div class="method-card active" onclick="switchMethod('qris')" id="btn-qris">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS">
                            <span>QRIS All Payment</span>
                        </div>
                        <div class="method-card" onclick="switchMethod('bank')" id="btn-bank">
                            <i class="fa-solid fa-building-columns"></i>
                            <span>Transfer Bank</span>
                        </div>
                    </div>

                    <div class="payment-detail active" id="panel-qris">
                        <p style="font-size: 13px; font-weight: 600;">Scan QRIS di bawah ini:</p>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=Pembayaran_QITSPREM" alt="QRIS" class="qris-img">
                        <p style="font-size: 11px; color: var(--text-muted);">Mendukung GoPay, OVO, DANA, ShopeePay & M-Banking</p>
                    </div>

                    <div class="form-group" style="margin-top: 24px; padding-top: 16px; border-top: 1px dashed var(--border-color);">
                        <label class="form-label"><i class="fa-solid fa-camera"></i> Upload Bukti Transfer</label>
                        <input type="file" name="proof_of_payment" class="form-control" accept=".jpg, .jpeg, .png" style="padding: 8px;" required>
                        @error('proof_of_payment')
                            <div style="color: red; margin-top: 6px; font-size: 13px;">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </div>
                        @enderror
                        <small style="color: var(--text-muted); font-size: 11px; margin-top: 6px; display: block;">Wajib diunggah sekarang. Format: JPG/PNG, Maks. 2MB.</small>
                        <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="qris">
                    </div>

                    <div class="payment-detail" id="panel-bank">
                        <div class="bank-list">
                            <div class="bank-item">
                                <div>
                                    <p style="font-weight: 700; font-size: 14px; color: #e87c2b;">SeaBank</p>
                                    <p style="font-size: 12px; color: var(--text-muted);">A.N Arshaa</p>
                                </div>
                                <p style="font-weight: 700; font-size: 14px;">901144714889</p>
                            </div>
                            <div class="bank-item">
                                <div>
                                    <p style="font-weight: 700; font-size: 14px; color: #1a52b2;">BCA</p>
                                    <p style="font-size: 12px; color: var(--text-muted);">A.N Rayhan</p>
                                </div>
                                <p style="font-weight: 700; font-size: 14px;">3481037482</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h3 class="card-title"><i class="fa-solid fa-receipt"></i> Ringkasan Pesanan</h3>
                    <table class="summary-table">
                        <tr>
                            <td>Nama Produk</td>
                            <td class="text-right">{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <td>Harga Normal</td>
                            <td class="text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="total-row">
                            <td>Total Bayar</td>
                            <td class="text-right total-price">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                    <button type="submit" class="btn-submit" style="margin-top: 20px;">
                        Konfirmasi Pembayaran <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@section('scripts')
<!-- HANYA JAVASCRIPT UNTUK CHECKOUT SAJA -->
<script>
    function switchMethod(method) {
        document.getElementById('btn-qris').classList.remove('active');
        document.getElementById('btn-bank').classList.remove('active');
        document.getElementById('panel-qris').classList.remove('active');
        document.getElementById('panel-bank').classList.remove('active');

        document.getElementById('btn-' + method).classList.add('active');
        document.getElementById('panel-' + method).classList.add('active');
        document.getElementById('selectedPaymentMethod').value = method;
    }
</script>
@endsection