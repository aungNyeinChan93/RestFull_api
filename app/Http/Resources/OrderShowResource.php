<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderShowResource extends JsonResource
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
            "order_id" => $this->id,
            "user_id" => $this->user_id,
            "userName" => User::find($this->user_id)->name ?? null,
            "user_email" => $this->user->email ?? null,
            "phone" => $this->user->phone ?? null,
            "shipping_address" => $this->shipping_address ?? $this->user->address,
            "note" => $this->note ?? null,
            'total_amount' => $this->total_amount,
            "screen_short" => $this->screen_short ?? null,
            "product_id" => $this->orders_products()->pluck('product_id'),
            "ordered_products" => array_map(fn($item) => Product::find($item['product_id']), $this->orders_products()->get()->toArray()),
            "create_at" => Carbon::now()->format('d-M-Y h-m-s'),
        ];
    }
}
