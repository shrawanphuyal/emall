<?php

namespace App\Http\Controllers\Api;

use App\Device;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\NotificationRequest;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends Controller {
	public function saveInstanceToken(NotificationRequest $request) {
		// save device token
		Device::firstOrCreate([
			'token'   => $request->input('token'),
			'from'    => $request->input('from'),
			'user_id' => auth()->guard('api')->id(),
		]);

		// return response
		return response()->json([
			'status'  => true,
			'code'    => Response::HTTP_OK,
			'message' => 'Device token saved.',
		], Response::HTTP_OK);
	}
}
