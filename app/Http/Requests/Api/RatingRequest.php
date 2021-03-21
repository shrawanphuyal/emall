<?php

namespace App\Http\Requests\Api;

class RatingRequest extends CommonRequest {
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'rating' => 'required|integer|min:1|max:10',
		];
	}
}
