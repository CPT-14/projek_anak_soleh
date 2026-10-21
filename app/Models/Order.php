<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model {
    protected $guarded = [];
    public function variant() { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
    public function user() { return $this->belongsTo(User::class); }

public function stock()
{
    return $this->hasOne(Stock::class, 'order_id');
}
    }