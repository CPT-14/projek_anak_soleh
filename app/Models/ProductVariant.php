<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model 
{
    protected $guarded = [];
    
    public function product() 
    { 
        return $this->belongsTo(Product::class); 
    }
    
    // Diselaraskan menggunakan model Stock yang baru
    public function stocks() 
    { 
        return $this->hasMany(Stock::class, 'product_variant_id'); 
    }
    
    public function availableStocks() 
    { 
        return $this->stocks()->where('is_available', true); 
    }
}