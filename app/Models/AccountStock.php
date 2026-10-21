<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model 
{
    // HAPUS BARIS protected $table = 'account_stocks';
    // Biarkan Laravel otomatis mendeteksi tabel 'stocks'
    
    protected $guarded = [];
    
    public function variant() 
    { 
        return $this->belongsTo(ProductVariant::class, 'product_variant_id'); 
    }
}