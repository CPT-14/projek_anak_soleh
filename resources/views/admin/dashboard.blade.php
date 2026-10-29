@extends('layouts.admin')
@section('title', 'Dashboard Penjualan')

@section('content')
<div class="welcome-section" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1>Dashboard Penjualan 📈</h1>
        <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Kelola dan pantau laporan pendapatan bulanan.</p>
    </div>

    <!-- === FORM FILTER PER BULAN === -->
    <form action="{{ route('admin.dashboard') }}" method="GET" style="display: flex; gap: 10px; align-items: center; background: #fff; padding: 8px 16px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
        <div>
            <select name="bulan" style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: #f8fafc; font-size: 13px; font-weight: 600; outline: none;">
                <option value="">-- Semua Bulan --</option>
                <option value="01" {{ request('bulan') == '01' ? 'selected' : '' }}>Januari</option>
                <option value="02" {{ request('bulan') == '02' ? 'selected' : '' }}>Februari</option>
                <option value="03" {{ request('bulan') == '03' ? 'selected' : '' }}>Maret</option>
                <option value="04" {{ request('bulan') == '04' ? 'selected' : '' }}>April</option>
                <option value="05" {{ request('bulan') == '05' ? 'selected' : '' }}>Mei</option>
                <option value="06" {{ request('bulan') == '06' ? 'selected' : '' }}>Juni</option>
                <option value="07" {{ request('bulan') == '07' ? 'selected' : '' }}>Juli</option>
                <option value="08" {{ request('bulan') == '08' ? 'selected' : '' }}>Agustus</option>
                <option value="09" {{ request('bulan') == '09' ? 'selected' : '' }}>September</option>
                <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
            </select>
        </div>

        <div>
            <select name="tahun" style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: #f8fafc; font-size: 13px; font-weight: 600; outline: none;">
                @php
                    $currentYear = date('Y');
                @endphp
                <option value="">-- Tahun --</option>
                @for ($i = $currentYear; $i >= $currentYear - 3; $i--)
                    <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>

        <button type="submit" style="background: #790D16; color: white; border: none; padding: 9px 16px; border-radius: 8px; font-weight: bold; font-size: 13px; cursor: pointer;">
            Filter
        </button>

        @if(request('bulan') || request('tahun'))
            <a href="{{ route('admin.dashboard') }}" style="background: #f1f5f9; color: #64748b; padding: 9px 12px; border-radius: 8px; font-size: 13px; text-decoration: none; font-weight: bold;">
                Reset
            </a>
        @endif
    </form>
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

<!-- TABEL RINCIAN AKUN TERJUAL (LATAR PUTIH & PEMBATAS TEGAS) -->
<section style="background: #ffffff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.04); border: 1px solid #cbd5e1;">
    <h2 style="font-size: 18px; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; color: #1e293b;">
        🛒 Rincian Akun yang Terjual
    </h2>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; text-align: left; border-collapse: collapse; font-size: 14px; background: #ffffff;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 14px; border: 1px solid #cbd5e1; color: #475569; font-weight: 700;">Tanggal Terjual</th>
                    <th style="padding: 14px; border: 1px solid #cbd5e1; color: #475569; font-weight: 700;">Nama Pembeli</th>
                    <th style="padding: 14px; border: 1px solid #cbd5e1; color: #475569; font-weight: 700;">Produk & Tipe Paket</th>
                    <th style="padding: 14px; border: 1px solid #cbd5e1; color: #475569; font-weight: 700;">Harga Terjual</th>
                </tr>
            </thead>
            <tbody>
                @forelse($soldOrders as $order)
                <tr style="background: #ffffff; transition: 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                    <!-- Tanggal -->
                    <td style="padding: 14px; border: 1px solid #cbd5e1; color: #64748b;">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </td>
                    
                    <!-- Nama User -->
                    <td style="padding: 14px; border: 1px solid #cbd5e1; font-weight: 600; color: #334155;">
                        {{ $order->user->name ?? 'User Telah Dihapus' }}
                    </td>
                    
                    <!-- Produk & Varian -->
                    <td style="padding: 14px; border: 1px solid #cbd5e1;">
                        <div style="color: #2563eb; font-weight: 700;">{{ $order->variant->product->name ?? 'Produk Dihapus' }}</div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 4px;">{{ $order->variant->name ?? '' }} ({{ $order->variant->duration ?? '' }})</div>
                    </td>
                    
                    <!-- Harga -->
                    <td style="padding: 14px; border: 1px solid #cbd5e1; font-weight: 800; color: #10b981;">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 32px; border: 1px solid #cbd5e1; text-align: center; color: #94a3b8; font-style: italic; background: #ffffff;">
                        Belum ada data akun yang terjual pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection