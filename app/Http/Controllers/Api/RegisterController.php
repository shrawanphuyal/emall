<?php

namespace App\Http\Controllers\Api;

use App\Custom\Abstracts\SocialLogin;
use App\Custom\FacebookLogin;
use App\Custom\GoogleLogin;
use App\Custom\NormalLogin;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
	/**
	 * Register a new user
	 *
	 * @param RegisterRequest $request
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function register(RegisterRequest $request)
	{
		$loginValidation = $this->validateLogin($request);

		if ($loginValidation->isValid()) {
			$user = $this->saveUser($request);
			if ( ! $user->auth_token) {
				$user->auth_token = auth()->guard('api')->login($user);
			}
			$user->verified = 1;
			$user->save();

			$user->roles()->sync([2]);

			return [
				'data' => [(new UserResource($user, true))->toArray($request)],
			];
		}

		throw new \Exception($loginValidation->getErrorMessages(), Response::HTTP_UNPROCESSABLE_ENTITY);
	}

	/**
	 * Save user data to database
	 *
	 * @param Request $request
	 *
	 * @return User
	 */
	private function saveUser(Request $request): User
	{
		$user              = User::where('email', $request->email)->first() ?: new User;
		$user->name        = $request->name;
		$user->email       = $request->email;
		$user->password    = bcrypt(str_random('15'));
		$user->phone       = $request->phone;
		$user->social_from = $request->input('from');
		$user->save();

		return $user;
	}

	/**
	 * Validate Google and Facebook login
	 *
	 * @param Request $request
	 *
	 * @return SocialLogin
	 * @throws \Exception
	 */
	private function validateLogin(Request $request): SocialLogin
	{
		$loginPortal = $request->input('from');

		switch ($loginPortal) {
			case 'facebook':
				return (new FacebookLogin)->verify();
				break;
			case 'google':
				return (new GoogleLogin)->verify();
				break;
			default:
				return (new NormalLogin())->verify();
		}
	}

}
