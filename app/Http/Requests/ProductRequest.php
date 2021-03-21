<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'category_id'     => 'nullable|integer|min:1',
			'sub_category_id' => 'nullable|integer|min:1',
			'title'           => 'required|string|max:255',
			'image'           => 'image|max:5120',
			'quantity'        => 'required|integer|min:0',
			'discount'        => 'nullable|integer|min:0',
			'discount_type'   => 'nullable|boolean',
			'price'           => 'required|numeric|min:0',
			'description'     => 'nullable',
		];
	}
}
