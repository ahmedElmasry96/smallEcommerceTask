<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => new UserResource($this->user),
            'total_price' => $this->total_price,
            'tax_value' => $this->tax_value,
            'discount_value' => $this->discount_value,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'products' => OrderItemResource::collection($this->order_items),
        ];
    }
}
