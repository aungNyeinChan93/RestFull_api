<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class OrdersProducts extends Model
{
    //
    protected $table = 'orders_products';

    protected $guarded = [];

    public function product()

    {
        return $this->belongsTo(Product::class);
    }

    public function order()

    {
        return $this->belongsTo(Order::class);
    }
}
