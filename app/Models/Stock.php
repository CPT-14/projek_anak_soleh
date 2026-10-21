<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model 
{
    protected $guarded = [];
    
    // INI YANG DICARI OLEH ADMIN CONTROLLER (variant.product)
    public function variant() 
    { 
        return $this->belongsTo(ProductVariant::class, 'product_variant_id'); 
    }

    // Relasi opsional jika dibutuhkan untuk mengecek pesanan
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}