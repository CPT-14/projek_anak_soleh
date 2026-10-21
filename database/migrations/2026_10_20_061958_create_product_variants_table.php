<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke produk utama (misal: Netflix)
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            // Detail Varian
            $table->string('name'); // Contoh: "Ultra HD 4K"
            $table->string('duration'); // Kolom durasi paket (Contoh: "1 Bulan")
            $table->integer('price'); // Harga paket
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');s
    }
};