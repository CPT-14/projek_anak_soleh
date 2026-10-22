@extends('layouts.admin')
@section('title', 'Dashboard Penjualan')

@section('content')
<div class="welcome-section" style="margin-bottom: 24px;">
    <h1>Dashboard Penjualan 📈</h1>
    <p>.</p>
</div>

<!-- KOTAK STATISTIK PENJUALAN -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <!-- KOTAK 1: AKUN TERJUAL -->
    <div style="background: #fff; padding: 24px; border-radius: 12px; border-left: 6px solid #0ea5e9; box-shadow: 0 4px 10px rgba(0,0,0,0.04);">
        <h3 style="font-size: 14px; color: #64748b; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Total Akun Terjual</h3>
        <div style="font-size: 36px; font-weight: 800; color: #0f172a;">
            {{ $totalTerjual }} <span style="font-size: 14px; font-weight: 600; color: #94a3b8;">Akun</span>
        </div>
    </div>

    <!-- KOTAK 2: TOTAL PENDAPATAN -->
    <div style="background: #fff; padding: 24px; border-radius: 12px; border-left: 6px solid #10b981; box-shadow: 0 4px 10px rgba(0,0,0,0.04);">
        <h3 style="font-size: 14px; color: #64748b; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Pendapatan Bersih</h3>
        <div style="font-size: 36px; font-weight: 800; color: #10b981;">
            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
        </div>
    </div>
</div>

<!-- TABEL RINCIAN AKUN TERJUAL -->
<section class="admin-section" style="background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.04); border: 1px solid #f1f5f9;">
    <h2 style="font-size: 18px; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px; color: #1e293b;">
        🛒 Rincian Akun yang Terjual
    </h2>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; text-align: left; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 14px; border-bottom: 2px solid #e2e8f0; color: #475569;">Tanggal Terjual</th>
                    <th style="padding: 14px; border-bottom: 2px solid #e2e8f0; color: #475569;">Nama Pembeli</th>
                    <th style="padding: 14px; border-bottom: 2px solid #e2e8f0; color: #475569;">Produk & Tipe Paket</th>
                    <th style="padding: 14px; border-bottom: 2px solid #e2e8f0; color: #475569;">Harga Terjual</th>
                </tr>
            </thead>
            <tbody>
                @forelse($soldOrders as $order)
                <tr style="transition: all 0.2s;">
                    <!-- Tanggal -->
                    <td style="padding: 14px; border-bottom: 1px solid #f1f5f9; color: #64748b;">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </td>
                    
                    <!-- Nama User -->
                    <td style="padding: 14px; border-bottom: 1px solid #f1f5f9; font-weight: 600; color: #334155;">
                        {{ $order->user->name ?? 'User Telah Dihapus' }}
                    </td>
                    
                    <!-- Produk & Varian -->
                    <td style="padding: 14px; border-bottom: 1px solid #f1f5f9;">
                        <div style="color: #2563eb; font-weight: 700;">{{ $order->variant->product->name ?? 'Produk Dihapus' }}</div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 4px;">{{ $order->variant->name ?? '' }} ({{ $order->variant->duration ?? '' }})</div>
                    </td>
                    
                    <!-- Harga -->
                    <td style="padding: 14px; border-bottom: 1px solid #f1f5f9; font-weight: 800; color: #10b981;">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 32px; text-align: center; color: #94a3b8; font-style: italic;">
                        Belum ada data akun yang terjual.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection