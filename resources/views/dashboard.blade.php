@extends('layouts.user')

@section('title', 'Pesanan Saya - QITSPREM')

@section('styles')
<style>
    .container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
    .page-title { font-size: 24px; font-weight: 800; margin-bottom: 24px; color: #111; }
    .btn-back { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px; text-decoration: none; color: var(--brand-hover); font-weight: 600; font-size: 14px; transition: 0.2s; }
    .btn-back:hover { opacity: 0.8; }
    
    .order-card { background: white; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; margin-bottom: 16px; display: flex; flex-direction: column; gap: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
    .order-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; }
    .order-id { font-weight: 700; font-size: 16px; }
    
    .order-status { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
    .status-pending { background-color: #fef3c7; color: #d97706; }
    .status-diproses { background-color: #dbeafe; color: #2563eb; }
    .status-selesai { background-color: #d1e7dd; color: #0f5132; }
    .status-dibatalkan { background-color: #fee2e2; color: #dc2626; }
    
    .order-body { display: flex; justify-content: space-between; align-items: center; }
    .product-name { font-size: 18px; font-weight: 700; color: var(--brand-hover); }
    .order-price { font-size: 16px; font-weight: 600; }
</style>
@endsection

@section('content')
<div class="container">
    @if(session('success'))
    <div style="background-color: #d1e7dd; color: #0f5132; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-weight: bold; border: 1px solid #badbcc;">
        ✅ {{ session('success') }}
    </div>
    @endif

    <a href="{{ route('home') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog</a>
    <h1 class="page-title">Riwayat Pesanan Saya</h1>

    @forelse($orders as $order)
        <div class="order-card">
            <div class="order-header">
                <span class="order-id">{{ $order->order_number }}</span>
                <span class="order-status status-{{ strtolower($order->status) }}">
                    {{ $order->status }}
                </span>
            </div>
            <div class="order-body">
                <div>
                    {{-- Diperbaiki agar membaca relasi dari variant ke product --}}
                    <div class="product-name">
                        {{ $order->variant->product->name ?? 'Produk' }} 
                        <span style="font-size: 14px; font-weight: normal; color: #555;">({{ $order->variant->name ?? '' }})</span>
                    </div>
                    <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                        Dipesan: {{ $order->created_at->format('d M Y, H:i') }}
                    </div>

                    {{-- FITUR TAMPILAN AKUN (Menyesuaikan kolom database: account_email & account_password) --}}
                    @if(strtolower($order->status) == 'selesai' && $order->stock)
                        <div style="margin-top: 12px; padding: 12px; background-color: #fefcfc; border-radius: 8px; border: 1px dashed var(--brand-hover);">
                            <div style="font-size: 12px; font-weight: 700; color: var(--brand-hover); margin-bottom: 6px;">🎉 Pesanan Berhasil! Ini detail akun Anda:</div>
                            <div style="font-size: 13px; color: #333;">Email: <strong>{{ $order->stock->account_email }}</strong></div>
                            <div style="font-size: 13px; color: #333;">Sandi: <strong>{{ $order->stock->account_password }}</strong></div>
                        </div>
                    @endif
                </div>
                
                <div class="order-price">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 40px; background: white; border-radius: 12px; border: 1px solid var(--border-color);">
            <p style="color: var(--text-muted); margin-bottom: 16px;">Anda belum memiliki pesanan.</p>
            <a href="{{ route('home') }}" style="background-color: var(--brand-hover); color: white; text-decoration: none; padding: 10px 24px; border-radius: 20px; font-weight: 600; font-size: 14px; display: inline-block;">Mulai Belanja</a>
        </div>
    @endforelse
</div>
@endsection