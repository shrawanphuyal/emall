<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class OrderResource extends Resource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'id'          => $this->id,
			'status'      => ucfirst($this->status),
			'paymentType' => $this->payment_type,
			'createdAt'   => $this->created_at->timestamp,
			'products'    => OrderProductResource::collection($this->products),
		];
	}
}
