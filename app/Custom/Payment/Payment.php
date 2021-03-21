<?php

namespace App\Custom\Payment;

abstract class Payment
{
	protected $error = '';
	protected $verified = false;
	protected $response = [];

	public function hasError()
	{
		return $this->getErrorMessage() !== '';
	}

	public function getErrorMessage()
	{
		return $this->error;
	}

	public function isVerified()
	{
		return $this->verified;
	}

	public function getResponse()
	{
		return $this->response;
	}
}
