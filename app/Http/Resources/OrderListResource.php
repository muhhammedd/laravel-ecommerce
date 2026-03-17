<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class OrderListResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $customer = $this->user ? $this->user->customer : null;
        $orderDetail = $this->details;

        return [
            'id' => $this->id,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'number_of_items' => $this->items_count,
            'customer' => [
                'id' => $this->user ? $this->user->id : null,
                'first_name' => $customer ? $customer->first_name : ($orderDetail->first_name ?? ''),
                'last_name' => $customer ? $customer->last_name : ($orderDetail->last_name ?? ''),
            ],
            'created_at' => (new \DateTime($this->created_at))->format('Y-m-d H:i:s'),
            'updated_at' => (new \DateTime($this->updated_at))->format('Y-m-d H:i:s'),
        ];
    }
}
