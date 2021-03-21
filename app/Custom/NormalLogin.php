<?php

namespace App\Custom;

use App\Custom\Abstracts\SocialLogin;

class NormalLogin extends SocialLogin {
	public function verify() {
		return $this;
	}

	public function isValid() {
		return true;
	}
}