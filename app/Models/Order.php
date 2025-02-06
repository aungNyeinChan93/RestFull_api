<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    protected $guarded = [];

    public function orders_products()
    {
        return $this->hasMany(OrdersProducts::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeFilter($query, $filters)
    {
        if ($filters['user_id'] ?? false) {
            $query->where('user_id', $filters['user_id']);
        }

        if ($filters['user_name'] ?? false) {
            $query->whereHas('user', function (Builder $builder) use ($filters) {
                $builder->where('name', $filters['user_name']);
            });
        }

        if ($filters['product_id'] ?? false) {
            $query->whereHas('orders_products', function (Builder $builder) use ($filters) {
                $builder->where('product_id', $filters['product_id']);
            });
        }

        if ($filters['product_name'] ?? false) {
            $query->whereHas('orders_products.product', function (Builder $builder) use ($filters) {
                $builder->where('name', 'like', '%' . $filters['product_name'] . '%');
            });
        }
    }
}
