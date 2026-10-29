@extends('layouts.admin')
@section('title', 'Kelola Pesanan')

@section('styles')
<style>
    .filter-wrapper { display: flex; flex-direction: column; gap: 16px; background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 24px; }
    @media (min-width: 768px) { .filter-wrapper { flex-direction: row; justify-content: space-between; align-items: center; } }
    .search-form { display: flex; gap: 8px; width: 100%; max-width: 400px; }
    .filter-btn-group { display: flex; gap: 10px; flex-wrap: wrap; }
    
    .btn-filter { padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid #d1d5db; color: #4b5563; background: white; }
    .btn-filter.active-all { background: #1e293b; color: white; border-color: #1e293b; }
    .btn-filter.active-menunggu { background: #f59e0b; color: white; border-color: #f59e0b; }
    .btn-filter.active-diproses { background: #3b82f6; color: white; border-color: #3b82f6; }
    .btn-filter.active-selesai { background: #10b981; color: white; border-color: #10b981; }

    @media (max-width: 768px) {
        .search-form { max-width: 100%; }
        .filter-btn-group { flex-wrap: nowrap; overflow-x: auto; padding-bottom: 8px; }
        .btn-filter { white-space: nowrap; flex-shrink: 0; }
    }
</style>
@endsection

@section('content')
<div class="welcome-section" style="margin-bottom: 24px;">
    <h1>Daftar Pesanan Masuk 🧾</h1>
    <p>Pantau transaksi pelanggan, verifikasi pembayaran, dan ubah status pesanan.</p>
</div>

<div class="filter-wrapper">
    <form action="{{ route('admin.orders') }}" method="GET" class="search-form">
        @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Transaksi..." style="flex: 1; padding: 10px 16px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none;">
        <button type="submit" style="background: #790D16; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer;">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.orders', ['status' => request('status')]) }}" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 10px 16px; border-radius: 8px; text-decoration: none; display: flex; align-items: center;">✕</a>
        @endif
    </form>

    @php $currentStatus = $filterStatus ?? 'all'; @endphp
    <div class="filter-btn-group">
        <a href="{{ route('admin.orders', ['status' => 'all', 'search' => request('search')]) }}" class="btn-filter {{ $currentStatus == 'all' ? 'active-all' : '' }}">Semua</a>
        <a href="{{ route('admin.orders', ['status' => 'menunggu pembayaran', 'search' => request('search')]) }}" class="btn-filter {{ $currentStatus == 'menunggu pembayaran' ? 'active-menunggu' : '' }}">⏳ Menunggu</a>
        <a href="{{ route('admin.orders', ['status' => 'diproses', 'search' => request('search')]) }}" class="btn-filter {{ $currentStatus == 'diproses' ? 'active-diproses' : '' }}">🔍 Diproses</a>
        <a href="{{ route('admin.orders', ['status' => 'selesai', 'search' => request('search')]) }}" class="btn-filter {{ $currentStatus == 'selesai' ? 'active-selesai' : '' }}">✅ Selesai</a>
    </div>
</div>

<section class="admin-section" style="border-top: 4px solid var(--header-bg);">
    <!-- DESKTOP TABLE -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                    <th style="padding: 12px;">ID / Tgl</th>
                    <th style="padding: 12px;">Pelanggan</th>
                    <th style="padding: 12px;">Produk & Total</th>
                    <th style="padding: 12px;">Status</th>
                    <th style="padding: 12px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 14px;"><strong style="color: #790D16;">{{ $order->order_number }}</strong><br><span style="font-size: 11px; color: #64748b;">{{ $order->created_at->format('d M Y, H:i') }}</span></td>
                    <td style="padding: 14px;"><span style="font-weight: 600;">{{ $order->user->name ?? 'User Dihapus' }}</span><br><span style="font-size: 12px; color: #64748b;">{{ $order->whatsapp ?? '-' }}</span></td>
                    <td style="padding: 14px;"><span style="font-size: 13px; font-weight: 600;">{{ $order->variant->product->name ?? 'Produk Dihapus' }}</span><br><span style="font-size: 12px; color: #64748b;">{{ $order->variant->name ?? '-' }}</span><br><span style="font-size: 13px; font-weight: 800; color: #10b981;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></td>
                    <td style="padding: 14px;">
                        @if($order->status === 'selesai' || strtolower($order->status) === 'success') <span style="background: #d1e7dd; color: #0f5132; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold;">Selesai</span>
                        @elseif($order->status === 'menunggu pembayaran') <span style="background: #fff3cd; color: #856404; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold;">Menunggu</span>
                        @elseif($order->status === 'diproses') <span style="background: #e0e7ff; color: #3730a3; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold;">Diproses</span>
                        @else <span style="background: #f8d7da; color: #842029; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold;">{{ ucfirst($order->status) }}</span> @endif
                    </td>
                    <td style="padding: 14px; text-align: center;"><a href="{{ route('admin.order.detail', $order->id) }}" style="background: #2563eb; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold;">Detail →</a></td>
                </tr>
                @empty
                <tr><td colspan="5" style="padding: 40px; text-align: center; color: #94a3b8;">Tidak ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- MOBILE CARDS -->
    <div class="mobile-cards">
        @forelse($orders as $order)
        <div class="mc-card">
            <div class="mc-header">
                <div><strong style="color: #790D16; font-size: 14px;">{{ $order->order_number }}</strong><div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ $order->created_at->format('d M Y, H:i') }}</div></div>
                <div>
                    @if($order->status === 'selesai' || strtolower($order->status) === 'success') <span style="background: #d1e7dd; color: #0f5132; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold;">Selesai</span>
                    @elseif($order->status === 'menunggu pembayaran') <span style="background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold;">Menunggu</span>
                    @elseif($order->status === 'diproses') <span style="background: #e0e7ff; color: #3730a3; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold;">Diproses</span>
                    @else <span style="background: #f8d7da; color: #842029; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold;">{{ ucfirst($order->status) }}</span> @endif
                </div>
            </div>
            <div class="mc-body">
                <div><strong>Pelanggan:</strong> {{ $order->user->name ?? 'Dihapus' }}</div>
                <div><strong>Produk:</strong> {{ $order->variant->product->name ?? 'Dihapus' }} ({{ $order->variant->name ?? '' }})</div>
                <div><strong>Total:</strong> <span style="color: #10b981; font-weight: 800;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></div>
            </div>
            <div class="mc-footer"><a href="{{ route('admin.order.detail', $order->id) }}" style="background: #2563eb; color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: bold;">Lihat Detail →</a></div>
        </div>
        @empty
        <div style="padding: 30px; text-align: center; color: #94a3b8; background: white; border-radius: 12px; border: 1px solid #e2e8f0;">Tidak ada pesanan.</div>
        @endforelse
    </div>
</section>
@endsection