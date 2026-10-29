@extends('layouts.admin')
@section('title', 'Kelola Customer')

@section('content')
<div class="welcome-section" style="margin-bottom: 24px;">
    <h1>Kelola Customer 👥</h1>
    <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Daftar pelanggan terdaftar dan riwayat interaksi mereka.</p>
</div>

<section style="background: #ffffff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.04); border: 1px solid #cbd5e1;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; text-align: left; border-collapse: collapse; font-size: 14px; background: #ffffff;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 14px; border: 1px solid #cbd5e1; color: #475569; font-weight: 700;">Nama Customer</th>
                    <th style="padding: 14px; border: 1px solid #cbd5e1; color: #475569; font-weight: 700;">Email</th>
                    
                    <!-- KOLOM BARU: Total Transaksi Berhasil -->
                    <th style="padding: 14px; border: 1px solid #cbd5e1; color: #475569; font-weight: 700; text-align: center;">Transaksi Berhasil</th>
                    
                    <th style="padding: 14px; border: 1px solid #cbd5e1; color: #475569; font-weight: 700;">No. WA (Terakhir)</th>
                    <th style="padding: 14px; border: 1px solid #cbd5e1; color: #475569; font-weight: 700;">Tanggal Bergabung</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr style="background: #ffffff; transition: 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                    <td style="padding: 14px; border: 1px solid #cbd5e1; font-weight: 600; color: #334155;">
                        {{ $customer->name }}
                    </td>
                    <td style="padding: 14px; border: 1px solid #cbd5e1; color: #64748b;">
                        {{ $customer->email }}
                    </td>
                    
                    <!-- DATA BARU: Total Transaksi Berhasil -->
                    <td style="padding: 14px; border: 1px solid #cbd5e1; text-align: center;">
                        @if($customer->orders_count > 0)
                            <span style="background-color: #d1e7dd; color: #0f5132; padding: 4px 10px; border-radius: 12px; font-weight: 800; font-size: 12px;">
                                {{ $customer->orders_count }}x
                            </span>
                        @else
                            <span style="color: #94a3b8; font-weight: 500; font-size: 13px;">0</span>
                        @endif
                    </td>
                    
                    <td style="padding: 14px; border: 1px solid #cbd5e1; color: #64748b;">
                        @if($customer->orders->isNotEmpty() && $customer->orders->first()->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $customer->orders->first()->whatsapp) }}" target="_blank" style="color: #10b981; font-weight: 600; text-decoration: none;">
                                💬 {{ $customer->orders->first()->whatsapp }}
                            </a>
                        @else
                            <span style="color: #94a3b8; font-style: italic;">Belum ada pesanan</span>
                        @endif
                    </td>
                    <td style="padding: 14px; border: 1px solid #cbd5e1; color: #64748b;">
                        {{ $customer->created_at->format('d M Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 32px; border: 1px solid #cbd5e1; text-align: center; color: #94a3b8; font-style: italic; background: #ffffff;">
                        Belum ada data customer terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection