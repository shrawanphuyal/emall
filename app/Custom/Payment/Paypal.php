<?php

namespace App\Custom\Payment;

use Carbon\Carbon;

class Paypal extends Payment implements PaymentContract
{
	public function verify($token, $amount = null, $model = null)
	{
		$accessToken = $this->getAccessToken();

		if ($this->hasError()) {
			return;
		}

		$paymentDetail = $this->getPaymentDetail($token, $accessToken);

		if ($this->hasError()) {
			return;
		}

		// check if payment is approved
		if ($paymentDetail['state'] !== 'approved') {
			$this->error = 'Payment is not approved yet.';

			return;
		}

		// if transaction has occurred just before 5 minutes, then accept it else deny it.
		if (Carbon::parse($paymentDetail['create_time'])->addMinutes(5)->lessThan(now())) {
			$this->error = 'Old transaction';

			return;
		}

		// check if transaction amount and currency type is valid
		$transactionAmount = $paymentDetail['transactions'][0]['amount'];
		$transactionPrice  = (float) $transactionAmount['total'];
		$amount            = (float) $amount;

		if ((string) $transactionPrice !== (string) $amount || $transactionAmount['currency'] !== 'USD') {
			$this->error = 'Transaction amount or currency is invalid.';

			return;
		}

		$this->verified = true;
	}

	/**
	 * Get access token to access payment detail
	 *
	 * @return string
	 * @throws \Exception
	 */
	private function getAccessToken(): string
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_USERPWD, config('paypal_payment.account.client_id') . ":" . config('paypal_payment.account.client_secret'));

		$headers   = [];
		$headers[] = "Accept: application/json";
		$headers[] = "Accept-Language: en_US";
		$headers[] = "Content-Type: application/x-www-form-urlencoded";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result     = json_decode(curl_exec($ch), true);
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if (curl_errno($ch)) {
			$this->error = 'Something went wrong. Please try again later.';
		}
		curl_close($ch);

		if ($statusCode !== 200) {
			$this->error = $result['error'] . ': ' . $result['error_description'];

			return '';
		}

		return $result['access_token'];
	}

	/**
	 * Get payment detail according to id/token
	 *
	 * @param $paymentToken
	 * @param $accessToken
	 *
	 * @return mixed
	 */
	private function getPaymentDetail($paymentToken, $accessToken)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/payments/payment/{$paymentToken}");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		$headers   = [];
		$headers[] = "Content-Type: application/json";
		$headers[] = "Authorization: Bearer {$accessToken}";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result     = json_decode(curl_exec($ch), true);
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if (curl_errno($ch)) {
			$this->error = 'Something went wrong. Please try again later.';
		}
		curl_close($ch);

		if ($statusCode !== 200) {
			$this->error = $result['message'];
		} else {
			$this->successResponse = $result;
		}

		return $result;
	}
}
