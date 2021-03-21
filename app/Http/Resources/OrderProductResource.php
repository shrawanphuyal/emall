<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class OrderProductResource extends Resource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function toArray($request) {
		return [
			'id'       => $this->product_id,
			'title'    => $this->product->title,
			'price'    => $this->rate,
			'quantity' => $this->quantity,
			'size'     => $this->size,
		];
	}
}
