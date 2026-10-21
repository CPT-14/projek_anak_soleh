@extends('layouts.user')

@section('title', 'Edit Profile - QITSPREM')

@section('styles')
<style>
    .profile-container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
    .profile-card { background: var(--card-bg); border-radius: 16px; padding: 32px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid var(--border-color); }
    .profile-title { font-size: 24px; font-weight: 800; margin-bottom: 24px; color: #111; }

    /* Layout Grid Kiri-Kanan */
    .profile-grid { display: grid; grid-template-columns: 1fr; gap: 32px; margin-bottom: 32px; }
    @media (min-width: 768px) { .profile-grid { grid-template-columns: 1fr 1fr; } }

    .section-title { font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--brand-hover); border-bottom: 1px solid var(--border-color); padding-bottom: 8px; }

    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--text-dark); }
    .form-control { width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; outline: none; transition: 0.2s; background: #fafafa; }
    .form-control:focus { border-color: var(--brand-hover); background: #fff; }

    /* Tombol Simpan Panjang */
    .btn-long-save { width: 100%; background: var(--brand-hover); color: white; border: none; padding: 14px; border-radius: 8px; font-size: 15px; font-weight: 700; cursor: pointer; transition: 0.2s; margin-bottom: 12px; }
    .btn-long-save:hover { opacity: 0.9; }

    /* Tombol Hapus Akun (Seperti tombol putih di foto referensi) */
    .btn-delete-outline { width: 100%; background: white; color: #dc2626; border: 2px solid #dc2626; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; transition: 0.2s; }
    .btn-delete-outline:hover { background: #fef2f2; }
</style>
@endsection

@section('content')
<div class="profile-container">
    <a href="{{ route('dashboard') }}" class="btn-back" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); text-decoration: none; font-weight: 600; margin-bottom: 20px;"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    <h1 class="profile-title">Edit Profile</h1>

    <!-- Notifikasi Sukses -->
    @if(session('status') === 'profile-updated')
        <div style="background-color: #d1e7dd; color: #0f5132; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-weight: bold; border: 1px solid #badbcc;">
            ✅ Profil Anda berhasil diperbarui!
        </div>
    @endif

    <div class="profile-card">
        <!-- FORM UTAMA (PROFIL & SANDI) -->
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="profile-grid">
                <!-- KIRI: INFORMASI USER -->
                <div class="grid-left">
                    <h3 class="section-title">Informasi Akun</h3>

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                        @error('name') <small style="color: red;">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Valid</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
                        @error('email') <small style="color: red;">{{ $message }}</small> @enderror
                    </div>
                </div>

                <!-- KANAN: GANTI KATA SANDI -->
                <div class="grid-right">
                    <h3 class="section-title">Ganti Kata Sandi (Opsional)</h3>

                    <div class="form-group">
                        <label class="form-label">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Kosongkan jika tidak diganti">
                        @error('current_password') <small style="color: red;">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kata Sandi Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter">
                        @error('password') <small style="color: red;">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Sandi Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang sandi baru">
                    </div>
                </div>
            </div>

            <!-- 1 TOMBOL PANJANG UNTUK SIMPAN KEDUANYA -->
            <button type="submit" class="btn-long-save">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </form>

        <hr style="margin: 24px 0; border: 0; border-top: 1px dashed var(--border-color);">

        <!-- TOMBOL HAPUS AKUN (Terkonfirmasi Sandi) -->
        <h3 class="section-title" style="color: #dc2626; border-color: #fca5a5;">HAPUS AKUN</h3>
        <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus akun? Data Anda tidak bisa dikembalikan.');">
            @csrf
            @method('DELETE')
            <div style="display: flex; gap: 16px; align-items: flex-start; flex-wrap: wrap;">
                <div class="form-group" style="flex: 2; margin-bottom: 0;">
                    <input type="password" name="password" class="form-control" placeholder="Masukkan Sandi Anda untuk konfirmasi hapus" required>
                    @error('password', 'userDeletion') 
                        <small style="color: red; display:block; margin-top:4px;">Sandi salah, gagal menghapus akun.</small> 
                    @enderror
                </div>
                <!-- Tombol Hapus sesuai ukuran "Batal" di foto referensi -->
                <button type="submit" class="btn-delete-outline" style="flex: 1;">
                    <i class="fa-solid fa-trash-can"></i> Hapus Akun
                </button>
            </div>
        </form>

    </div>
</div>
@endsection