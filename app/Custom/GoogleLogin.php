<?php

namespace App\Custom;

use App\Custom\Abstracts\SocialLogin;
use Symfony\Component\HttpFoundation\Response;

class GoogleLogin extends SocialLogin {
	private $userData;
	private $clientIdAndroid;
	private $clientIdIos;

	/**
	 * @param string $clientIdAndroid
	 *
	 * @return GoogleLogin
	 */
	public function setClientIdAndroid(string $clientIdAndroid): GoogleLogin {
		$this->clientIdAndroid = $clientIdAndroid;

		return $this;
	}

	/**
	 * @param string $clientIdIos
	 *
	 * @return GoogleLogin
	 */
	public function setClientIdIos(string $clientIdIos): GoogleLogin {
		$this->clientIdIos = $clientIdIos;

		return $this;
	}

	/**
	 * @throws \Exception
	 */
	public function verify() {
		$this->setClientIdAndroid(config('services.google.android.client_id'))
		     ->setClientIdIos(config('services.google.android.client_id'));

		$httpRequest = new HttpRequest;
		// accessing user data
		$this->userData = $httpRequest->get('https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . $this->getToken())->execute();
		$this->checkErrorsInUserData();

		$this->valid = ($this->isAndroidAudValid() || $this->isIosAudValid()) && $this->isUserEmailValid();

		return $this;
	}

	/**
	 * @throws \Exception
	 */
	private function checkErrorsInUserData() {
		if(array_key_exists('error_description', $this->userData)) {
			throw new \Exception($this->userData['error_description'], Response::HTTP_UNPROCESSABLE_ENTITY);
		}
	}

	/**
	 * @return bool
	 */
	private function isAndroidAudValid() {
		if($this->userData['aud'] != $this->clientIdAndroid) {
			$this->errorMessages[] = 'AUD did not match';

			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	private function isIosAudValid() {
		if($this->userData['aud'] != $this->clientIdIos) {
			$this->errorMessages[] = 'AUD did not match';

			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	private function isUserEmailValid() {
		// return $this->userData['email'] == request()->input('email');
		if($this->userData['email'] != $this->getEmail()) {
			$this->errorMessages[] = 'Email did not match';

			return false;
		}

		return true;
	}
}