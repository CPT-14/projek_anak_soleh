<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    // Relasi: 1 Produk memiliki BANYAK tipe paket (variants)
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Jembatan Relasi: Produk bisa melihat Stok melalui Varian
    public function stocks()
    {
        // PERUBAHAN: Sekarang menggunakan Stock::class
        return $this->hasManyThrough(Stock::class, ProductVariant::class);
    }
}