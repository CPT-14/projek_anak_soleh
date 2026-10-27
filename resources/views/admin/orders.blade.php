@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('styles')
<style>
    /* Styling khusus untuk tombol filter pesanan */
    .filter-btn-group {
        display: flex;
        gap: 10px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .btn-filter {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
        border: 1px solid #d1d5db;
        color: #4b5563;
        background: white;
    }
    .btn-filter:hover { background: #f3f4f6; }
    
    /* Warna tombol saat aktif diklik */
    .btn-filter.active-all { background: #1e293b; color: white; border-color: #1e293b; }
    .btn-filter.active-menunggu { background: #f59e0b; color: white; border-color: #f59e0b; }
    .btn-filter.active-diproses { background: #3b82f6; color: white; border-color: #3b82f6; }
    .btn-filter.active-selesai { background: #10b981; color: white; border-color: #10b981; }
</style>
@endsection

@section('content')
<div class="welcome-section" style="margin-bottom: 24px;">
    <h1>Daftar Pesanan Masuk 🧾</h1>
    <p>Pantau transaksi pelanggan, verifikasi pembayaran, dan ubah status pesanan.</p>
</div>

<!-- TOMBOL FILTER STATUS -->
@php $currentStatus = $filterStatus ?? 'all'; @endphp
<div class="filter-btn-group">
    <a href="{{ route('admin.orders', ['status' => 'all']) }}" 
       class="btn-filter {{ $currentStatus == 'all' ? 'active-all' : '' }}">
       Semua Pesanan
    </a>
    
    <a href="{{ route('admin.orders', ['status' => 'menunggu pembayaran']) }}" 
       class="btn-filter {{ $currentStatus == 'menunggu pembayaran' ? 'active-menunggu' : '' }}">
       ⏳ Menunggu Pembayaran
    </a>
    
    <a href="{{ route('admin.orders', ['status' => 'diproses']) }}" 
       class="btn-filter {{ $currentStatus == 'diproses' ? 'active-diproses' : '' }}">
       🔍 Diproses (Cek Pembayaran)
    </a>
    
    <a href="{{ route('admin.orders', ['status' => 'selesai']) }}" 
       class="btn-filter {{ $currentStatus == 'selesai' ? 'active-selesai' : '' }}">
       ✅ Selesai (Akun Terkirim)
    </a>
</div>

<!-- TABEL PESANAN -->
<section class="admin-section" style="border-top: 4px solid var(--brand-color); background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <div class="table-responsive">
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                    <th style="padding: 12px;">ID / Tgl</th>
                    <th style="padding: 12px;">Pelanggan</th>
                    <th style="padding: 12px;">Produk & Total</th>
                    <th style="padding: 12px;">Status</th>
                    <th style="padding: 12px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 14px;">
                        <strong style="color: var(--brand-color);">{{ $order->order_number }}</strong><br>
                        <span style="font-size: 11px; color: #64748b;">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </td>
                    <td style="padding: 14px;">
                        <span style="font-weight: 600;">{{ $order->user->name ?? 'User Dihapus' }}</span><br>
                        <span style="font-size: 12px; color: #64748b;">{{ $order->whatsapp ?? '-' }}</span>
                    </td>
                    <td style="padding: 14px;">
                        <span style="font-size: 13px; font-weight: 600;">{{ $order->variant->product->name ?? 'Produk Dihapus' }}</span><br>
                        <span style="font-size: 12px; color: #64748b;">{{ $order->variant->name ?? '-' }} ({{ $order->variant->duration ?? '-' }})</span><br>
                        <span style="font-size: 13px; font-weight: 800; color: #10b981;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </td>
                    <td style="padding: 14px;">
                        @if($order->status === 'selesai' || strtolower($order->status) === 'success')
                            <span style="background: #d1e7dd; color: #0f5132; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold;">Selesai</span>
                        @elseif($order->status === 'menunggu pembayaran')
                            <span style="background: #fff3cd; color: #856404; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold;">Menunggu</span>
                        @elseif($order->status === 'diproses')
                            <span style="background: #e0e7ff; color: #3730a3; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold;">Diproses</span>
                        @else
                            <span style="background: #f8d7da; color: #842029; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold;">{{ ucfirst($order->status) }}</span>
                        @endif
                    </td>
                    <td style="padding: 14px;">
                        <a href="{{ route('admin.order.detail', $order->id) }}" style="background: #2563eb; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold; display: inline-block;">
                            Lihat Detail →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 40px; text-align: center; color: #94a3b8;">
                        @if($currentStatus !== 'all')
                            Tidak ada pesanan dengan status "<strong>{{ $currentStatus }}</strong>".
                        @else
                            Belum ada transaksi sama sekali.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection