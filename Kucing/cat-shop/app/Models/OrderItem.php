<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id','product_id','sku','qty','price','cost_price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
