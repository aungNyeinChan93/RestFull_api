<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'total_amount' => $this->total_amount,
            "note" => $this->note ?? null,
            "shipping_address" => $this->shipping_address,
            "user_id" => $this->user_id,
            "userName" => User::find($this->user_id)->name ?? null,
            "user_email" => $this->user->email ?? null,
            "screen_short" => $this->screen_short ?? null,
            "order_id" => $this->id,
            "product_id" => $this->orders_products()->pluck('product_id'),
            "ordered_products" => array_map(fn($item) => Product::find($item['product_id']), $this->orders_products()->get()->toArray()),
            "create_at" => Carbon::now()->format('d-M-Y h-m-s'),
        ];
    }
}
