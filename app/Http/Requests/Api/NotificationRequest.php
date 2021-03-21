<?php

namespace App\Http\Requests\Api;

class NotificationRequest extends CommonRequest {
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'token'  => 'required|string',
			'from'   => ['required', 'max:7', 'in:ios,android'],
		];
	}

	/**
	 * @return array
	 */
	public function messages() {
		return [
			'type.in' => 'The from may be ios or android only.',
		];
	}

}
