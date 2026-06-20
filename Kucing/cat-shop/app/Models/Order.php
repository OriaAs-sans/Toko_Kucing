<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['external_id','channel_id','status','total_amount','marketplace_fee','discount_amount','total_cost','profit'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function calculateProfit()
    {
        $cost = $this->orderItems->sum(function($item){
            return $item->qty * $item->cost_price;
        });
        $revenue = $this->total_amount;
        $fees = $this->marketplace_fee;
        $discounts = $this->discount_amount;
        $profit = $revenue - $cost - $fees - $discounts;
        $this->total_cost = $cost;
        $this->profit = $profit;
        $this->save();
        return $profit;
    }
}
