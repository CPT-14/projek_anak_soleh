@extends('layouts.admin')
@section('title', 'Kelola Pesanan')

@section('content')
<div class="welcome-section" style="margin-bottom: 24px;">
    <h1>Daftar Pesanan 🛒</h1>
    <p>Pantau bukti transfer dan ubah status pesanan pelanggan Anda di sini.</p>
</div>

<section class="admin-section">
    <div style="background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #eee;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #eee;">
                    <th style="padding: 12px; font-weight: 600;">ID / Tanggal</th>
                    <th style="padding: 12px; font-weight: 600;">Pelanggan</th>
                    <th style="padding: 12px; font-weight: 600;">Detail Paket</th>
                    <th style="padding: 12px; font-weight: 600;">Total Harga</th>
                    <th style="padding: 12px; font-weight: 600;">Bukti TF</th>
                    <th style="padding: 12px; font-weight: 600;">Status & Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;">
                        <strong>#{{ $order->id }}</strong><br>
                        <span style="font-size: 12px; color: gray;">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </td>
                    <td style="padding: 12px;">
                        {{ $order->user->name ?? 'User Dihapus' }}<br>
                        <span style="font-size: 12px; color: gray;">{{ $order->user->email ?? '' }}</span>
                    </td>
                    <td style="padding: 12px;">
                        <strong>{{ $order->variant->product->name ?? 'Produk Dihapus' }}</strong><br>
                        <span style="font-size: 12px; color: gray;">Paket: {{ $order->variant->name ?? '' }} ({{ $order->variant->duration ?? '' }})</span>
                    </td>
                    <td style="padding: 12px; font-weight: bold; color: #790D16;">
                        Rp {{ number_format($order->total_price ?? ($order->variant->price ?? 0), 0, ',', '.') }}
                    </td>
                    <td style="padding: 12px;">
                        @if($order->payment_proof)
                            <a href="{{ asset('bukti_tf/' . $order->payment_proof) }}" target="_blank" style="color: #0056b3; text-decoration: underline; font-size: 13px; font-weight: bold;">Lihat Foto ↗</a>
                        @else
                            <span style="color: gray; font-size: 13px;">Belum ada</span>
                        @endif
                    </td>
                    <td style="padding: 12px;">
                        <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST" style="display: flex; gap: 8px;">
                            @csrf
                            @method('PATCH')
                            <select name="status" style="padding: 6px; border-radius: 6px; border: 1px solid #ccc; font-size: 13px; background: 
                                {{ $order->status == 'success' ? '#d4edda' : 
                                  ($order->status == 'menunggu_konfirmasi' ? '#cce5ff' : 
                                  ($order->status == 'pending' ? '#fff3cd' : '#f8d7da')) }};">
                                
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="menunggu_konfirmasi" {{ $order->status == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="success" {{ $order->status == 'success' ? 'selected' : '' }}>Success (Berhasil)</option>
                                <option value="failed" {{ $order->status == 'failed' ? 'selected' : '' }}>Failed / Batal</option>
                            </select>
                            <button type="submit" style="background: #790D16; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 13px;">Simpan</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 20px; text-align: center; color: gray;">Belum ada pesanan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection