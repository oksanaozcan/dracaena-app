<?php

namespace App\Http\Resources;

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
            'session_id' => $this->session_id,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'payment_status' => $this->payment_status,
            'total_amount' => $this->total_amount,
            'payment_method' => $this->payment_method,
            'shipping_address' => $this->shipping_address,
            'billing_address' => $this->billing_address,
            'discount_amount' => $this->discount_amount,
            'created_at' => $this->created_at,
        ];
    }
}
