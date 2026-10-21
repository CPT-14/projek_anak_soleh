@extends('layouts.admin')
@section('title', 'Kelola Stok Akun')

@section('content')
<div class="welcome-section" style="margin-bottom: 24px;">
    <h1>Kelola Stok: {{ $variant->product->name }} ({{ $variant->name }}) 🔐</h1>
    <p>Di sinilah Anda menambah stok email dan password secara khusus.</p>
</div>

<a href="{{ route('admin.product.edit', $variant->product->id) }}" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; color: #790D16; font-weight: bold; text-decoration: none;">
    &larr; Kembali ke Edit Produk
</a>

@if(session('success'))
    <div style="background: #d1e7dd; color: #0f5132; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; border: 1px solid #badbcc;">
        ✅ {{ session('success') }}
    </div>
@endif

<div style="display: flex; gap: 24px; flex-wrap: wrap;">
    <!-- KIRI: FORM TAMBAH STOK BARU -->
    <div style="flex: 1; min-width: 300px; background: #fff; padding: 24px; border-radius: 12px; border: 1px solid #eee; border-top: 4px solid #28a745; height: fit-content;">
        <h3 style="margin-bottom: 16px; font-size: 16px;">➕ Tambah Stok Akun Baru</h3>
        <form action="{{ route('admin.variant.stocks.store', $variant->id) }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px;">Email Akun (Opsional)</label>
                <input type="text" name="account_email" placeholder="Kosongkan jika otomatis 'hubungi admin'" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px;">Password Akun (Opsional)</label>
                <input type="text" name="account_password" placeholder="Kosongkan jika otomatis 'no admin'" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
            </div>
            <button type="submit" style="background-color: #28a745; color: white; width: 100%; padding: 12px; font-size: 14px; border-radius: 8px; cursor: pointer; border: none; font-weight: bold;">TAMBAHKAN STOK</button>
        </form>
    </div>

    <!-- KANAN: DAFTAR STOK YANG MASIH TERSEDIA SAJA -->
    <div style="flex: 2; min-width: 400px; background: #fff; padding: 24px; border-radius: 12px; border: 1px solid #eee;">
        <h3 style="margin-bottom: 16px; font-size: 16px;">📦 Daftar Stok Tersedia ({{ $availableStocks->count() }})</h3>
        
        <table style="width: 100%; text-align: left; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Email</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Password</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Status</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($availableStocks) && $availableStocks->count() > 0): ?>
                    <?php foreach($availableStocks as $stock): ?>
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: 600;"><?php echo e($stock->account_email); ?></td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee; font-family: monospace;"><?php echo e($stock->account_password); ?></td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span style="background: #d1e7dd; color: #0f5132; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">Tersedia</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <form action="{{ route('admin.stock.destroy', $stock->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus stok ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #dc3545; color: white; border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="4" style="padding: 24px; text-align: center; color: #6c757d;">Belum ada stok akun yang tersedia untuk paket ini.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
@endsection