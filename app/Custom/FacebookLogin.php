<?php

namespace App\Custom;

use App\Custom\Abstracts\SocialLogin;
use Symfony\Component\HttpFoundation\Response;

class FacebookLogin extends SocialLogin {
	private $userData;
	private $appData;
	private $androidAppId;
	private $iosAppId;

	/**
	 * @param string $androidAppId
	 *
	 * @return FacebookLogin
	 */
	public function setAndroidAppId(string $androidAppId): FacebookLogin {
		$this->androidAppId = $androidAppId;

		return $this;
	}

	/**
	 * @param string $iosAppId
	 *
	 * @return FacebookLogin
	 */
	public function setIosAppId(string $iosAppId): FacebookLogin {
		$this->iosAppId = $iosAppId;

		return $this;
	}

	/**
	 * @throws \Exception
	 */
	public function verify() {
		$this->setAndroidAppId(config('services.facebook.android.app_id'))
		     ->setIosAppId(config('services.facebook.ios.app_id'));

		$httpRequest = new HttpRequest;
		// accessing user data
		$this->userData = $httpRequest->get('https://graph.facebook.com/me?fields=email&access_token=' . $this->getToken())->execute();
		$this->checkErrorsInUserData();

		// accessing app data
		$this->appData = $httpRequest->get('https://graph.facebook.com/app?access_token=' . $this->getToken())->execute();
		$this->checkErrorsInAppData();

		$this->valid = ($this->isAndroidAppIdValid() || $this->isIosAppIdValid()) && $this->isUserEmailValid();

		return $this;
	}

	/**
	 * @throws \Exception
	 */
	private function checkErrorsInUserData() {
		if(array_key_exists('error', $this->userData)) {
			throw new \Exception($this->userData['error']['message'], Response::HTTP_UNPROCESSABLE_ENTITY);
		}
	}

	/**
	 * @throws \Exception
	 */
	private function checkErrorsInAppData() {
		if(array_key_exists('error', $this->appData)) {
			throw new \Exception($this->appData['error']['message'], Response::HTTP_UNPROCESSABLE_ENTITY);
		}
	}

	/**
	 * @return bool
	 */
	private function isAndroidAppIdValid() {
		if($this->appData['id'] != $this->androidAppId) {
			$this->errorMessages[] = 'App ID did not match';

			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	private function isIosAppIdValid() {
		if($this->appData['id'] != $this->iosAppId) {
			$this->errorMessages[] = 'App ID did not match';

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