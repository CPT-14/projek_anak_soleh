@extends('layouts.user')

@section('title', 'Riwayat Pesanan Saya')

@section('content')
<div style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
    
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 24px; font-weight: 800; color: #222;">📦 Riwayat Pesanan Saya</h2>
        <p style="color: #666; font-size: 14px;">Pantau status pesanan dan kelola bukti pembayaran Anda di sini.</p>
    </div>

    @if(session('success'))
        <div style="background: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if($orders->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach($orders as $order)
                <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                            <span style="font-weight: 800; font-size: 15px; color: #1e293b;">#{{ $order->order_number }}</span>
                            
                            <!-- Status Badge -->
                            @php
                                $status = strtolower($order->status);
                                $badgeBg = $status == 'selesai' ? '#d1e7dd' : ($status == 'dibatalkan' ? '#f8d7da' : '#fff3cd');
                                $badgeColor = $status == 'selesai' ? '#0f5132' : ($status == 'dibatalkan' ? '#721c24' : '#664d03');
                            @endphp
                            <span style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; font-size: 12px; font-weight: bold; padding: 4px 10px; border-radius: 20px; text-transform: uppercase;">
                                {{ $order->status }}
                            </span>
                        </div>
                        
                        <div style="font-size: 14px; color: #475569; margin-bottom: 4px;">
                            <strong>{{ $order->variant->product->name ?? 'Produk' }}</strong> - {{ $order->variant->name ?? '-' }} ({{ $order->variant->duration ?? '-' }})
                        </div>
                        
                        <div style="font-size: 12px; color: #94a3b8;">
                            Tanggal: {{ $order->created_at->format('d M Y, H:i') }} | Total: <strong style="color: #10b981;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('order.detail.user', $order->id) }}" style="background: #790D16; color: #fff; padding: 10px 18px; border-radius: 20px; text-decoration: none; font-weight: bold; font-size: 13px; display: inline-block;">
                            🔍 Detail & Bukti TF
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="background: #fff; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 40px; text-align: center;">
            <p style="color: #64748b; font-size: 15px; margin-bottom: 12px;">Anda belum memiliki riwayat pesanan.</p>
            <a href="{{ route('home') }}" style="background: #790D16; color: white; padding: 10px 20px; border-radius: 20px; text-decoration: none; font-weight: bold; font-size: 13px; display: inline-block;">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection