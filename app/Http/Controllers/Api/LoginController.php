<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;

class LoginController extends Controller {

	/**
	 * Log user out
	 *
	 */
	public function logout() {
		$user = auth()->guard('api')->userOrFail();

		$user->update(['access_token' => null]);

		auth()->guard('api')->logout();

		return successResponse('Successfully logged out');
	}

	/**
	 * Refresh login access token of user
	 *
	 * @return UserResource
	 */
	public function refreshAccessToken() {
		$user = auth()->guard('api')->userOrFail();

		$refreshedToken = auth()->guard('api')->refresh();

		$user->update(['access_token' => $refreshedToken]);

		return new UserResource($user);
	}
}
