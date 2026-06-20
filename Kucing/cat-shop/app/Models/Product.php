<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['sku','name','cost_price','sell_price','stock_qty','min_stock'];
}
