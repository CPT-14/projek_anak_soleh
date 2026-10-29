@extends('layouts.admin')
@section('title', 'Kelola Produk')

@section('styles')
<style>
    .category-list-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #ccc; }
    .category-list-item:last-child { border-bottom: none; }
    .btn-delete-cat { background: none; border: none; color: #dc3545; cursor: pointer; font-size: 12px; font-weight: bold; }
</style>
@endsection

@section('content')
<div class="welcome-section" style="margin-bottom: 24px;">
    <h1>Kelola Etalase Produk 📦</h1>
    <p>Tambah, edit, dan hapus produk serta tipe paket di toko Anda.</p>
</div>

@if(session('success'))
    <div style="background: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
        ✅ {{ session('success') }}
    </div>
@endif

<!-- BAGIAN 1: FORM MULTI-VARIAN (2 KOLOM) -->
<section class="admin-section" style="border-top: 4px solid #790D16; padding: 20px; background: #fff; border-radius: 12px; margin-bottom: 24px;">
    <h2 class="section-title" style="margin-bottom: 16px;">📦 Tambah Produk & Tipe Paket</h2>
    <form action="{{ route('admin.product.store') }}" method="POST">
        @csrf
        <div style="display: flex; gap: 24px; flex-wrap: wrap;">
            
            <!-- KIRI: PRODUK UTAMA -->
            <div style="flex: 1; min-width: 300px; background: #fafafa; padding: 20px; border-radius: 12px; border: 1px solid #eee;">
                <h3 style="font-size: 15px; margin-bottom: 16px;">1. Detail Produk Utama</h3>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px;">Nama Produk</label>
                    <input type="text" name="name" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;" placeholder="Misal: Netflix Premium" required>
                </div>
                
                <!-- DROPDOWN KATEGORI DIAMBIL DARI DATABASE -->
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px;">Kategori</label>
                    <select name="category" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;" required>
                        <option value="">-- Pilih Kategori --</option>
                        @forelse($product_categories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @empty
                            <option value="" disabled>Belum ada kategori, buat di bagian paling bawah!</option>
                        @endforelse
                    </select>
                </div>
                
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px;">URL Gambar (Opsional)</label>
                    <input type="url" name="image" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px;">Deskripsi</label>
                    <textarea name="description" rows="3" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;" required></textarea>
                </div>
            </div>

            <!-- KANAN: PAKET DINAMIS -->
            <div style="flex: 1.5; min-width: 400px; background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #eee; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <h3 style="font-size: 15px; margin-bottom: 16px;">2. Daftar Paket/Tipe</h3>
                
                <div id="variants-container">
                    <div class="variant-row" style="display: grid; grid-template-columns: 2fr 1fr 1.5fr auto; gap: 8px; margin-bottom: 12px; align-items: end;">
                        <div>
                            <label style="font-size: 11px; font-weight: 600;">Nama Tipe Paket</label>
                            <input type="text" name="variant_name[]" placeholder="Misal: Ultra HD 4K" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;" required>
                        </div>
                        <div>
                            <label style="font-size: 11px; font-weight: 600;">Bulan/Durasi</label>
                            <input type="text" name="variant_duration[]" placeholder="Misal: 1 Bulan" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;" required>
                        </div>
                        <div>
                            <label style="font-size: 11px; font-weight: 600;">Harga (Rp)</label>
                            <input type="number" name="variant_price[]" placeholder="Misal: 35000" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;" required>
                        </div>
                    </div>
                </div>
                
                <button type="button" id="add-variant-btn" style="background: transparent; border: 1px dashed #790D16; color: #790D16; font-weight: 600; padding: 8px 16px; border-radius: 6px; cursor: pointer; margin-top: 8px; width: 100%;">
                    + Tambah Tipe Paket Lain
                </button>
            </div>
        </div>
        <button type="submit" style="background-color: #790D16; color: white; width: 100%; margin-top: 24px; padding: 14px; font-size: 16px; border-radius: 8px; cursor: pointer; border: none; font-weight: 600;">SIMPAN PRODUK & PAKET KE KATALOG</button>
    </form>
</section>

<!-- BAGIAN 2: DAFTAR ETALASE -->
<section class="admin-section" style="padding: 20px; background: transparent; margin-bottom: 24px;">
    <h2 class="section-title" style="margin-bottom: 16px;">📋 Daftar Etalase Aktif</h2>
    <div>
        <?php if($products->isEmpty()): ?>
            <p style="color: gray; text-align: center; background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #eee;">Belum ada produk.</p>
        <?php else: ?>
            <?php foreach($products as $product): ?>
            <!-- CARD ETALASE DENGAN BACKGROUND PUTIH -->
            <div style="background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 20px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                <div>
                    <h4 style="font-size: 18px; font-weight: 700; color: #790D16; margin: 0;"><?php echo e($product->name); ?></h4>
                    <p style="font-size: 13px; color: gray; margin: 6px 0 16px 0;">Kategori: <strong><?php echo e($product->category); ?></strong></p>
                    
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <?php foreach($product->variants as $variant): ?>
                            <span style="background: #fef0e6; color: #e87c2b; padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; border: 1px solid #fbdcce;">
                                <?php echo e($variant->name); ?> (<?php echo e($variant->duration); ?>) - Rp <?php echo e(number_format($variant->price, 0, ',', '.')); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <a href="<?php echo e(route('admin.product.edit', $product->id)); ?>" style="background: #e87c2b; color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; transition: background 0.2s;">Edit</a>
                    
                    <form action="<?php echo e(route('admin.product.destroy', $product->id)); ?>" method="POST" onsubmit="return confirm('Hapus produk ini beserta seluruh paketnya?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: #dc3545; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; transition: background 0.2s;">Hapus</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- BAGIAN 3: KOTAK CRUD KATEGORI (DI PALING BAWAH) -->
<section class="admin-section" style="padding: 20px; background: #fff; border-radius: 12px; border: 1px solid #eee; margin-bottom: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
    <h2 class="section-title" style="margin-bottom: 12px; color: #1e293b;">🏷️ Kelola Kategori Produk</h2>
    <p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">Tambahkan kategori baru di sini agar muncul sebagai pilihan di form "Tambah Produk" di atas.</p>
    
    <div style="display: flex; gap: 24px; flex-wrap: wrap;">
        <!-- Form Tambah Kategori -->
        <div style="flex: 1; min-width: 280px; background: #fafafa; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <form action="{{ route('admin.category.store') }}" method="POST">
                @csrf
                <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 8px; color: #334155;">Nama Kategori Baru</label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" name="name" style="flex: 1; padding: 10px 14px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 14px;" placeholder="Cth: Streaming, Edukasi" required>
                    <button type="submit" style="background: #10b981; color: white; border: none; padding: 0 20px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 14px;">Tambah</button>
                </div>
            </form>
        </div>

        <!-- List Kategori -->
        <div style="flex: 1; min-width: 280px;">
            <h4 style="font-size: 14px; font-weight: 700; color: #334155; margin-bottom: 12px;">Daftar Kategori Tersedia:</h4>
            <div style="max-height: 200px; overflow-y: auto; background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0;">
                @forelse($product_categories as $cat)
                    <div class="category-list-item">
                        <span style="font-size: 14px; font-weight: 600; color: #334155;">{{ $cat->name }}</span>
                        <form action="{{ route('admin.category.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $cat->name }}?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete-cat">Hapus</button>
                        </form>
                    </div>
                @empty
                    <p style="font-size: 13px; color: #94a3b8; text-align: center; margin: 10px 0; font-style: italic;">Belum ada kategori yang dibuat.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById('add-variant-btn').addEventListener('click', function() {
        const container = document.getElementById('variants-container');
        const newRow = document.createElement('div');
        newRow.className = 'variant-row';
        newRow.style = 'display: grid; grid-template-columns: 2fr 1fr 1.5fr auto; gap: 8px; margin-bottom: 12px; align-items: end;';
        newRow.innerHTML = `
            <div>
                <input type="text" name="variant_name[]" placeholder="Misal: Family Share" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;" required>
            </div>
            <div>
                <input type="text" name="variant_duration[]" placeholder="Misal: 3 Bulan" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;" required>
            </div>
            <div>
                <input type="number" name="variant_price[]" placeholder="Harga" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;" required>
            </div>
            <button type="button" class="remove-variant" style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-weight: bold;">X</button>
        `;
        container.appendChild(newRow);
        
        newRow.querySelector('.remove-variant').addEventListener('click', function() {
            newRow.remove();
        });
    });
</script>
@endsection 