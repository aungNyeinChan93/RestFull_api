<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function orders_products(){
        return $this->hasMany(OrdersProducts::class);
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
