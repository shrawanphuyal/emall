<?php

namespace App\Exceptions\Api;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class ValidationFailed extends Exception {
	protected $customMessage;

	public function __construct(Validator $validator) {
		// $validation->errors() return MessageBag instance with error message collection
		// then we convert it to array
		// the result still has the keys of the error messages. Thus we flatten it to get single dimensional array message.
		// $this->customMessage = array_flatten($validator->errors()->toArray());
		$this->customMessage = $validator->errors()->toArray();
	}

	public function render($request) {
		return response()->json([
			'status'  => false,
			'code'    => Response::HTTP_UNPROCESSABLE_ENTITY,
			'message' => $this->customMessage,
		], Response::HTTP_UNPROCESSABLE_ENTITY);
	}

}