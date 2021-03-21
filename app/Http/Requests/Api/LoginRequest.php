<?php

namespace App\Http\Requests\Api;

class LoginRequest extends CommonRequest {
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'email'       => 'nullable|email',
			'phoneNumber' => 'required',
			'password'    => 'required|string|min:4',
		];
	}
}
