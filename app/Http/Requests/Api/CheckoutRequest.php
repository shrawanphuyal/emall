<?php

namespace App\Http\Requests\Api;

class CheckoutRequest extends CommonRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'cartItems'   => 'required|array',
			'phone'       => 'required|string',
			'address'     => 'required|string',
			'token'       => 'nullable|string', // TODO: Make all below fields required
			'paymentType' => 'nullable|string|in:normal,khalti',
			'deviceType'  => 'nullable|string|in:android,ios',
		];
	}
}
