<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SliderResource extends Resource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function toArray($request) {
		return [
			'id'        => $this->id,
			'image'     => $this->image,
			'url'       => $this->url,
			'createdAt' => $this->created_at->timestamp,
			'updatedAt' => $this->updated_at->timestamp,
		];
	}
}
