<?php

namespace App\Custom\Payment;

class Normal extends Payment implements PaymentContract
{
	public function verify($token, $amount = null, $model = null)
	{
		$this->verified = true;
	}
}
