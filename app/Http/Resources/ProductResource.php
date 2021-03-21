<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ProductResource extends Resource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function toArray($request) {
		$favourite = - 1;
		if (auth()->guard('api')->check()) {
			$favourite = in_array($this->id, auth()->guard('api')->user()->favouriteProducts()->pluck('product_id')->toArray()) ? 1 : 0;
		}

		return [
			'id'                 => $this->id,
			'title'              => $this->title,
			'images'             => $this->images->map(function ($image) {
				return $image->image(512, 512);
			})->toArray(),
			'quantity'           => $this->quantity,
			'discountType'       => $this->discount_type ? 'amount' : 'percentage',
			'discount'           => $this->discount,
			'price'              => $this->sellingPrice(),
			'description'        => $this->description,
			'specification'      => $this->specification,
			'sale'               => $this->sale,
			'gender'             => ! is_null($this->gender) ? $this->gender ? 'male' : 'female' : null,
			'levelOneCategory'   => new SubCategoryResource($this->category),
			'levelTwoCategory'   => new SubCategoryResource($this->sub_category),
			'levelThreeCategory' => new SubCategoryResource($this->sub_sub_category),
			'favourite'          => $favourite,
			'createdAt'          => $this->created_at->timestamp,
			'updatedAt'          => $this->updated_at->timestamp,
		];
	}
}
