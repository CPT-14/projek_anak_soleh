@extends('layouts.admin')
@section('title', 'Kelola Pesanan')

@section('styles')
<style>
    /* Card Pesanan Model Vertikal (Nyaman di HP & Laptop) */
    .order-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        gap: 16px;
        transition: transform 0.2s;
    }
    .order-card:hover {
        border-color: #cbd5e1;
    }
    @media (min-width: 768px) {
        .order-card {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }
    
    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
        text-transform: uppercase;
    }
    .status-selesai { background: var(--badge-green-bg); color: var(--badge-green-text); }
    .status-menunggu { background: #fffbeb; color: #b45309; }
    .status-diproses { background: #eff6ff; color: #1d4ed8; }
    .status-batal { background: var(--badge-red-bg); color: var(--badge-red-text); }
    
    .btn-detail {
        background: #790D16;
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        display: inline-block;
        text-align: center;
        transition: 0.2s;
        box-shadow: 0 4px 10px rgba(121, 13, 22, 0.15);
    }
    .btn-detail:hover {
        background: #5e0911;
        transform: translateY(-1px);
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 24px; font-weight: 800; color: #1e293b; margin-bottom: 8px;">Kelola Pesanan 🛒</h1>
    <p style="color: #64748b; font-size: 14px;">.</p>
</div>

@forelse($orders as $order)
    <div class="order-card">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px; flex-wrap: wrap;">
                <span style="font-weight: 800; font-size: 16px; color: #1e293b;">#ORD-{{ $order->id }}</span>
                
                @php
                    $status = strtolower($order->status);
                    if($status === 'selesai' || $status === 'success') {
                        $badgeClass = 'status-selesai';
                        $statusText = 'Selesai';
                    } elseif($status === 'menunggu pembayaran') {
                        $badgeClass = 'status-menunggu';
                        $statusText = 'Menunggu';
                    } elseif($status === 'diproses') {
                        $badgeClass = 'status-diproses';
                        $statusText = 'Diproses';
                    } else {
                        $badgeClass = 'status-batal';
                        $statusText = $status;
                    }
                @endphp
                <span class="status-badge {{ $badgeClass }}">{{ $statusText }}</span>
            </div>
            
            <div style="font-size: 14px; color: #334155; margin-bottom: 4px;">
                <strong>Pembeli:</strong> {{ $order->user->name ?? 'User Dihapus' }} <span style="color: #94a3b8;">({{ $order->user->email ?? '-' }})</span>
            </div>
            
            <div style="font-size: 14px; color: #475569; margin-bottom: 6px;">
                <strong>Produk:</strong> <span style="color: #790D16; font-weight: 700;">{{ $order->variant->product->name ?? '-' }}</span> — {{ $order->variant->name ?? '-' }} ({{ $order->variant->duration ?? '-' }})
            </div>
            
            <div style="font-size: 13px; color: #94a3b8;">
                Tanggal: {{ $order->created_at->format('d M Y, H:i') }} | Total: <strong style="color: #10b981;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div>
            <a href="{{ route('admin.order.detail', $order->id) }}" class="btn-detail">
                Lihat Detail &rarr;
            </a>
        </div>
    </div>
@empty
    <div style="background: #fff; border: 1px dashed #cbd5e1; border-radius: 16px; padding: 40px; text-align: center;">
        <div style="font-size: 40px; margin-bottom: 12px;">📭</div>
        <p style="color: #64748b; font-size: 15px; margin: 0;">Belum ada data pesanan yang masuk.</p>
    </div>
@endforelse
@endsection