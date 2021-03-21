<?php

namespace App\Custom;

use Symfony\Component\HttpFoundation\Response;

class HttpRequest {
	private $url;
	private $parameters;
	private $method;
	private $headers = [];

	/**
	 * @param array $headers
	 *
	 * @return HttpRequest
	 */
	public function setHeaders($headers) {
		$this->headers = $headers;

		return $this;
	}

	/**
	 * @param string $url
	 * @param array  $parameters
	 *
	 * @return $this
	 */
	public function get($url, $parameters = null) {
		$this->method = 'GET';
		$this->url    = $parameters
			? $url . '?' . $this->formatGetMethodParameters($parameters)
			: $url;

		return $this;
	}

	/**
	 * @param $url
	 * @param $parameters
	 *
	 * @return $this
	 */
	public function post($url, $parameters) {
		$this->method     = 'POST';
		$this->url        = $url;
		$this->parameters = $parameters;

		return $this;
	}

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public function execute() {
		try {
			$ch = curl_init($this->url);
			// Disable SSL verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			// Will return the response, if false it print the response
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			if($this->method === 'POST') {
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $this->parameters);
			}

			if(count($this->headers)) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
			}

			// Execute
			$result     = curl_exec($ch);
			$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			// Closing
			curl_close($ch);

			$response = json_decode($result, true);
			if($response) {
				return $response;
			}

			throw new \Exception('Unknown error', Response::HTTP_UNPROCESSABLE_ENTITY);

		} catch(\Exception $exception) {
			throw new \Exception($exception->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
		}
	}

	/**
	 * @param array $parameters
	 *
	 * @return string
	 */
	private function formatGetMethodParameters($parameters) {
		$formatted = '';
		foreach($parameters as $key => $value) {
			$formatted .= "{$key}={$value}&";
		}

		// removing last char of string
		return substr($formatted, 0, - 1);
	}
}