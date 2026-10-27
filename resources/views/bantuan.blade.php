@extends('layouts.user') <!-- Atau layouts.app, sesuaikan dengan nama layout Anda -->

@section('title', 'Pusat Bantuan - QITSPREM')

@section('content')
<div class="container-responsive" style="max-width: 800px; padding-top: 40px; padding-bottom: 60px;">
    
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 32px; font-weight: 800; color: #790D16; margin-bottom: 12px;">Pusat Bantuan QITSPREM</h1>
        <p style="color: #64748b; font-size: 16px;">Ada kendala dengan akun Anda? Tim kami siap membantu!</p>
    </div>

    <div style="background: white; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; overflow: hidden;">
        
        <!-- Jam Operasional -->
        <div style="padding: 30px; border-bottom: 1px solid #e2e8f0; background: #fafafa;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 50px; height: 50px; background: #e0e7ff; color: #4338ca; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">Jam Operasional</h3>
                    <p style="color: #64748b; font-size: 14px; margin: 0;">Senin - Minggu: 08:00 - 22:00 WIB</p>
                </div>
            </div>
        </div>

        <!-- Opsi WhatsApp -->
        <div style="padding: 30px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 20px;">Hubungi Kami via WhatsApp</h3>
            
            <p style="color: #475569; font-size: 14px; line-height: 1.6; margin-bottom: 24px;">
                Untuk respon yang lebih cepat, silakan hubungi admin kami melalui WhatsApp. Jangan lupa siapkan <strong>Nomor Transaksi (Contoh: TRX-QITS-XXXX)</strong> jika pertanyaan Anda terkait pesanan.
            </p>

            @php
                // GANTI 6281234567890 DENGAN NOMOR WA ASLI ANDA
                $waAdmin = '6285134889725';
                $pesan = urlencode("Halo Admin QITSPREM, saya ingin bertanya tentang...");
            @endphp

            <a href="https://wa.me/{{ $waAdmin }}?text={{ $pesan }}" target="_blank" style="display: inline-flex; align-items: center; gap: 10px; background: #25d366; color: white; padding: 14px 24px; border-radius: 30px; font-weight: 700; text-decoration: none; font-size: 16px; transition: all 0.3s; box-shadow: 0 4px 15px rgba(37,211,102,0.3);">
                <i class="fa-brands fa-whatsapp" style="font-size: 20px;"></i>
                Chat WhatsApp Admin
            </a>
        </div>

    </div>
</div>
@endsection