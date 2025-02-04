<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function scopeFilter($query, $filters)
    {

        if (isset($filters['price_min'])) {
            $query->where('price', '>=', $filters['price_min']);
        }

        if (isset($filters['price_max'])) {
            $query->where('price', '<=', $filters['price_max']);
        }

        if (isset($filters['quantity_min'])) {
            $query->where('quantity', '>=', $filters['quantity_min']);
        }

        if (isset($filters['quantity_max'])) {
            $query->where('quantity', '<=', $filters['quantity_max']);
        }

        if ($filters['category_id'] ?? false) {
            $query->whereHas('category', function (Builder $builder) use ($filters) {
                $builder->where('id', '=', $filters['category_id']);
            });
        }

        return $query;
    }
}
