<?php

namespace App\Custom\Payment;

class Khalti extends Payment implements PaymentContract
{
	public function verify($token, $amount = null, $model = null)
	{
		$status = $this->getVerificationStatus($token, $amount);

		if ( ! $status) {
			$this->error = $this->response;

			return;
		}

		if ($this->response['amount'] != $amount) {
			$this->error = "Payment amount did not match.";

			return;
		}

		if ($this->response['merchant']['idx'] != config('services.khalti.merchant_idx')) {
			$this->error = "Merchant Id did not match.";

			return;
		}

		$this->verified = true;
	}

	private function getVerificationStatus($token, $amount): bool
	{
		$args = http_build_query([
			'token'  => $token,
			'amount' => $amount,
		]);

		$url = "https://khalti.com/api/v2/payment/verify/";

		# Make the call using API.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$headers = ['Authorization: Key ' . config('services.khalti.live_secret_key')];
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// Response
		$response   = curl_exec($ch);
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$this->response = json_decode($response, true);
		//dd($this->response, $statusCode);

		return $statusCode === 200;
	}

	public function getErrorMessage()
	{
		$error = $this->error;
		return json_encode($error);

		switch ($error['error_key']) {
			case 'validation_error':
				return $error['token'][0];
				break;
			case 'already_verified':
				return $error['detail'];
				break;
			default:
				return $error;
		}
	}
}