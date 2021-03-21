<?php

namespace App\Exceptions\Api;

use Exception;
use Throwable;

class CustomException extends Exception {
	public function render($request) {
		$code = $this->code ?: 200;

		return response()->json([
			'status'  => false,
			'code'    => $code,
			'message' => $this->message,
		], $code);
	}
}
