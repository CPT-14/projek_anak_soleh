@extends('layouts.admin')
@section('title', 'Edit Produk')

@section('content')
<div class="welcome-section" style="margin-bottom: 24px;">
    <h1>Edit Produk: {{ $product->name }} ✏️</h1>
    <p>Ubah detail produk dan tipe paket di toko Anda.</p>
</div>

<section class="admin-section" style="border-top: 4px solid #790D16; padding: 20px; background: #fff; border-radius: 12px;">
    <form action="{{ route('admin.product.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: flex; gap: 24px; flex-wrap: wrap;">
            <!-- KIRI: PRODUK UTAMA -->
            <div style="flex: 1; min-width: 300px; background: #fafafa; padding: 20px; border-radius: 12px; border: 1px solid #eee; height: fit-content;">
                <h3 style="font-size: 15px; margin-bottom: 16px;">1. Detail Produk Utama</h3>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px;">Nama Produk</label>
                    <input type="text" name="name" value="{{ $product->name }}" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;" required>
                </div>

                <!-- DROPDOWN KATEGORI DINAMIS DARI DATABASE -->
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px;">Kategori</label>
                    <select name="category" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;" required>
                        <option value="">-- Pilih Kategori --</option>
                        @forelse($product_categories as $cat)
                            <option value="{{ $cat->name }}" {{ $product->category == $cat->name ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @empty
                            <option value="" disabled>Belum ada kategori, buat terlebih dahulu di menu Tambah Produk!</option>
                        @endforelse
                    </select>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px;">URL Gambar (Opsional)</label>
                    <input type="url" name="image" value="{{ $product->image }}" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px;">Deskripsi</label>
                    <textarea name="description" rows="4" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;" required>{{ $product->description }}</textarea>
                </div>
            </div>

            <!-- KANAN: DAFTAR PAKET -->
            <div style="flex: 1.5; min-width: 450px; background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #eee;">
                <h3 style="font-size: 15px; margin-bottom: 16px;">2. Daftar Paket</h3>
                
                <div id="variants-container">
                    @if(isset($product->variants))
                        @foreach($product->variants as $variant)
                        <div class="variant-row" style="background: #fdfdfd; border: 1px solid #e5e7eb; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
                            <input type="hidden" name="variant_id[]" value="{{ $variant->id }}">
                            
                            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 8px; margin-bottom: 12px; align-items: end;">
                                <div>
                                    <label style="font-size: 11px; font-weight: 600;">Nama Paket</label>
                                    <input type="text" name="variant_name[]" value="{{ $variant->name }}" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;" required>
                                </div>
                                <div>
                                    <label style="font-size: 11px; font-weight: 600;">Durasi</label>
                                    <input type="text" name="variant_duration[]" value="{{ $variant->duration }}" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;" required>
                                </div>
                                <div>
                                    <label style="font-size: 11px; font-weight: 600;">Harga (Rp)</label>
                                    <input type="number" name="variant_price[]" value="{{ $variant->price }}" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;" required>
                                </div>
                                <button type="button" class="remove-variant" style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; height: 38px;">Hapus</button>
                            </div>
                            
                            <!-- TOMBOL MENUJU HALAMAN KHUSUS STOK -->
                            <a href="{{ route('admin.variant.stocks', $variant->id) }}" style="display: inline-block; background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold; margin-top: 5px;">
                                ⚙️ Kelola Stok Akun
                            </a>
                        </div>
                        @endforeach
                    @endif
                </div>
                
                <button type="button" id="add-variant-btn" style="background: transparent; border: 1px dashed #790D16; color: #790D16; font-weight: 600; padding: 12px 16px; border-radius: 6px; cursor: pointer; margin-top: 8px; width: 100%;">
                    + Tambah Tipe Paket Baru
                </button>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px; position: sticky; bottom: 20px; background: white; padding: 16px; border-radius: 12px; box-shadow: 0 -4px 15px rgba(0,0,0,0.05); border: 1px solid #eee;">
            <a href="{{ route('admin.products') }}" style="background: #6c757d; color: white; padding: 14px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center;">Batal</a>
            <button type="submit" style="background-color: #790D16; color: white; flex: 1; padding: 14px; font-size: 16px; border-radius: 8px; cursor: pointer; border: none; font-weight: 600;">SIMPAN PERUBAHAN PAKET</button>
        </div>
    </form>
</section>

<script>
    document.getElementById('add-variant-btn').addEventListener('click', function() {
        const container = document.getElementById('variants-container');
        const newRow = document.createElement('div');
        newRow.className = 'variant-row';
        newRow.style = 'background: #fdfdfd; border: 1px solid #e5e7eb; padding: 16px; border-radius: 8px; margin-bottom: 20px;';
        newRow.innerHTML = `
            <input type="hidden" name="variant_id[]" value="">
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 8px; margin-bottom: 12px; align-items: end;">
                <div>
                    <input type="text" name="variant_name[]" placeholder="Nama Paket" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;" required>
                </div>
                <div>
                    <input type="text" name="variant_duration[]" placeholder="Durasi" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;" required>
                </div>
                <div>
                    <input type="number" name="variant_price[]" placeholder="Harga" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;" required>
                </div>
                <button type="button" class="remove-variant" style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; height: 38px;">Hapus</button>
            </div>
            <span style="font-size: 11px; color: #666; font-style: italic;">*Simpan perubahan terlebih dahulu untuk memunculkan tombol Kelola Stok.</span>
        `;
        container.appendChild(newRow);
        
        newRow.querySelector('.remove-variant').addEventListener('click', function() {
            newRow.remove();
        });
    });

    document.querySelectorAll('.remove-variant').forEach(function(btn) {
        btn.addEventListener('click', function() {
            this.closest('.variant-row').remove();
        });
    });
</script>
@endsection