@extends('layouts.admin')

@section('title', 'Kelola Customer')

@section('content')
<div class="welcome-section" style="margin-bottom: 24px;">
    <h1>Kelola Customer 👥</h1>
    <p>Daftar pengguna pembeli yang terdaftar beserta riwayat keberhasilan transaksinya.</p>
</div>

<section class="admin-section" style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-top: 4px solid #790D16;">
    <h2 class="section-title" style="margin-bottom: 20px;">👥 Daftar Pelanggan Aktif</h2>
    
    <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #e2e8f0; background: #f8fafc;">
                    <th style="padding: 12px; font-weight: 600; color: #475569;">Profil</th>
                    <th style="padding: 12px; font-weight: 600; color: #475569;">Nama & Kontak</th>
                    <th style="padding: 12px; font-weight: 600; color: #475569;">Tanggal Daftar</th>
                    <th style="padding: 12px; font-weight: 600; color: #475569; text-align: center;">Total Transaksi Sukses</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <!-- Avatar Lingkaran -->
                    <td style="padding: 14px; width: 60px;">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background-color: #fef0e6; display: flex; justify-content: center; align-items: center; font-weight: bold; color: #e87c2b; font-size: 18px;">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                    </td>
                    
                    <!-- Nama & Email -->
                    <td style="padding: 14px;">
                        <h4 style="font-size: 15px; font-weight: 700; color: #1e293b; margin: 0 0 4px 0;">{{ $customer->name }}</h4>
                        <p style="font-size: 13px; color: #64748b; margin: 0;">{{ $customer->email }}</p>
                    </td>
                    
                    <!-- Tanggal Bergabung -->
                    <td style="padding: 14px;">
                        <span style="font-size: 13px; color: #475569;">{{ $customer->created_at->format('d M Y') }}</span>
                    </td>
                    
                    <!-- Transaksi Sukses (Hasil dari withCount) -->
                    <td style="padding: 14px; text-align: center;">
                        @if($customer->orders_count > 0)
                            <span style="display: inline-block; background: #10b981; color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);">
                                {{ $customer->orders_count }} Transaksi
                            </span>
                        @else
                            <span style="display: inline-block; background: #f1f5f9; color: #94a3b8; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                Belum ada
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8;">
                        Belum ada customer yang terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection