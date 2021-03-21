<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class RegisterRequest extends CommonRequest {
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'name'  => 'required|string',
			'email' => 'required|email',
			'phone' => 'nullable|string',
			//'from'  => ['required', Rule::in(['facebook', 'google', 'normal'])],
			'from'  => ['required', Rule::in(['facebook'])],
			'token' => 'required',
		];
	}
}
