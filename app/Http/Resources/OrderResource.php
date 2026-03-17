<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class OrderResource extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $customer = $this->user ? $this->user->customer : null;
        $shipping = $customer ? $customer->shippingAddress : null;
        $billing = $customer ? $customer->billingAddress : null;
        
        // Fallback to order details for guests
        $orderDetail = $this->details;

        return [
            'id' => $this->id,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'items' => $this->items->map(fn($item) => [
                'id' => $item->id,
                'unit_price' => $item->unit_price,
                'quantity' => $item->quantity,
                'product' => [
                    'id' => $item->product->id,
                    'slug' => $item->product->slug,
                    'title' => $item->product->title,
                    'image' => $item->product->image,
                ]
            ]),
            'customer' => [
                'id' => $this->user ? $this->user->id : null,
                'email' => $this->user ? $this->user->email : ($orderDetail->email ?? null),
                'first_name' => $customer ? $customer->first_name : ($orderDetail->first_name ?? ''),
                'last_name' => $customer ? $customer->last_name : ($orderDetail->last_name ?? ''),
                'phone' => $customer ? $customer->phone : ($orderDetail->phone ?? ''),
                'shippingAddress' => [
                    'id' => $shipping ? $shipping->id : null,
                    'address1' => $shipping ? $shipping->address1 : ($orderDetail->address1 ?? ''),
                    'address2' => $shipping ? $shipping->address2 : ($orderDetail->address2 ?? ''),
                    'city' => $shipping ? $shipping->city : ($orderDetail->city ?? ''),
                    'state' => $shipping ? $shipping->state : ($orderDetail->state ?? ''),
                    'zipcode' => $shipping ? $shipping->zipcode : ($orderDetail->zipcode ?? ''),
                    'country' => $shipping ? $shipping->country->name : ($orderDetail->country_code ?? ''),
                ],
                'billingAddress' => [
                    'id' => $billing ? $billing->id : null,
                    'address1' => $billing ? $billing->address1 : '',
                    'address2' => $billing ? $billing->address2 : '',
                    'city' => $billing ? $billing->city : '',
                    'state' => $billing ? $billing->state : '',
                    'zipcode' => $billing ? $billing->zipcode : '',
                    'country' => $billing ? $billing->country->name : '',
                ]
            ],
            'created_at' => (new \DateTime($this->created_at))->format('Y-m-d H:i:s'),
            'updated_at' => (new \DateTime($this->updated_at))->format('Y-m-d H:i:s'),
        ];
    }
}
