<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat Akun Admin
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password123'), // Passwordnya: password123
            'role' => 'admin',
        ]);

        // 2. Membuat Akun Pembeli (User biasa)
        User::create([
            'name' => 'Pembeli Biasa',
            'email' => 'user@toko.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // 3. Membuat Produk 1 (Streaming)
        Product::create([
            'name' => 'Netflix Premium 1 Bulan',
            'description' => 'Akun Netflix kualitas 4K UHD, garansi 1 bulan penuh.',
            'price' => 35000,
            'category' => 'Streaming', // <-- Tambahan kategori
        ]);

        // 4. Membuat Produk 2 (Design)
        Product::create([
            'name' => 'Canva Premium 1 Tahun',
            'description' => 'Nikmati berbagai alat desain profesional untuk kebutuhan kreatif.',
            'price' => 15000,
            'category' => 'Design & Editing', // <-- Tambahan kategori
        ]);
    }
}