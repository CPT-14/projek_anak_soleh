@extends('layouts.admin')
@section('title', 'Dashboard Gudang')

@section('content')
<div class="welcome-section" style="margin-bottom: 24px;">
    <h1>Halo, Admin! 👋</h1>
    <p>Kelola penyimpanan akun stok QITSPREM Anda di sini.</p>
</div>

<section class="admin-section" style="border-top: 4px solid #790D16;">
    <h2 class="section-title">🗄️ Daftar Akun di Gudang</h2>
    
    <div class="list-grid">
        @forelse($stocks as $stock)
        <div style="display: flex; justify-content: space-between; align-items: center; border: 1px solid var(--card-border); border-radius: 12px; padding: 16px; margin-bottom: 12px; background: #fff;">
            <div>
                <h4 style="color: #790D16;">{{ $stock->variant->product->name ?? 'Produk Dihapus' }} - {{ $stock->variant->name ?? '' }} ({{ $stock->variant->duration ?? '' }})</h4>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                    📧 {{ $stock->email ?? 'Tanyakan kepada Admin' }} | 
                    🔑 {{ $stock->password ?? 'Tanyakan kepada Admin' }}
                </p>
            </div>
            <form action="{{ route('admin.stock.destroy', $stock->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini dari gudang?');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
        </div>
        @empty
        <p style="color: var(--text-muted); text-align: center;">Gudang masih kosong.</p>
        @endforelse
    </div>

    <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--card-border);">
        <h3 style="font-size: 16px; margin-bottom: 16px;">+ Tambah Stok ke Paket Spesifik</h3>
        <form action="{{ route('admin.stock.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div style="grid-column: span 2;">
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px;">Pilih Produk & Paket</label>
                    <select name="product_variant_id" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;" required>
                        <option value="">-- Pilih Tipe Paket --</option>
                        @foreach($variants as $variant)
                            <option value="{{ $variant->id }}">
                                {{ $variant->product->name }} - Paket {{ $variant->name }} ({{ $variant->duration }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px;">Email (Opsional)</label>
                    <input type="email" name="email" placeholder="Kosongkan jika manual" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                </div>
                <div>
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px;">Password (Opsional)</label>
                    <input type="text" name="password" placeholder="Kosongkan jika manual" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="background-color: #790D16; color: white;">Simpan ke Gudang</button>
        </form>
    </div>
</section>
@endsection