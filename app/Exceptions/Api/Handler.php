<?php

namespace App\Exceptions\Api;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class Handler {
	private $exception;
	private $statusCode;
	private $customStatusCode = 0;

	/**
	 * Handler constructor.
	 *
	 * @param \Exception $exception
	 */
	public function __construct(\Exception $exception) {
		$this->exception = $exception;
		$this->setStatusCode();
	}

	private function setStatusCode() {
		$validStatusCodes = array_keys(Response::$statusTexts);

		$defaultCode = $this->exception->getCode();

		$this->customStatusCode = $defaultCode;
		// if status code is not valid set it to 200
		$this->statusCode = !in_array($defaultCode, $validStatusCodes) ? Response::HTTP_OK : $defaultCode;
	}

	/**
	 * @return integer
	 */
	public function getStatusCode() {
		return $this->statusCode;
	}

	/**
	 * @return int
	 */
	public function getCustomStatusCode() {
		return $this->customStatusCode;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->defaultMessageExists() ? $this->defaultMessage() : $this->customMessage();
	}

	/**
	 * @return bool
	 */
	private function defaultMessageExists() {
		return $this->defaultMessage() !== '';
	}

	/**
	 * @return string
	 */
	private function defaultMessage() {
		if(
			$this->exception instanceof AuthenticationException ||
			$this->exception instanceof JWTException ||
			$this->exception instanceof TokenInvalidException ||
			$this->exception instanceof UserNotDefinedException ||
			$this->exception instanceof TokenBlacklistedException ||
			$this->exception instanceof TokenExpiredException
		) {
			$this->statusCode = $this->customStatusCode = Response::HTTP_UNAUTHORIZED;
		}

		if($this->exception instanceof TokenBlacklistedException) {
			return 'Token blacklisted.';
		} elseif($this->exception instanceof TokenInvalidException) {
			return 'Token is invalid.';
		} elseif($this->exception instanceof TokenExpiredException) {
			return 'Token has expired.';
		} elseif($this->exception instanceof UserNotDefinedException) {
			return 'User not found.';
		} elseif($this->exception instanceof JWTException) {
			return 'Token is absent.';
		} elseif($this->exception instanceof ModelNotFoundException) {
			$this->statusCode = $this->customStatusCode = Response::HTTP_NOT_FOUND;

			return 'Model not found.';
		}

		return $this->exception->getMessage();
	}

	/**
	 * @return string
	 */
	private function customMessage() {
		if($this->exception instanceof NotFoundHttpException) {
			$this->statusCode = $this->customStatusCode = Response::HTTP_NOT_FOUND;

			return 'Page not found.';
		} elseif($this->exception instanceof MethodNotAllowedHttpException) {
			$this->statusCode = $this->customStatusCode = Response::HTTP_METHOD_NOT_ALLOWED;

			return 'Method not allowed.';
		}

		return $this->customDefaultMessage();
	}

	/**
	 * @return string
	 */
	private function customDefaultMessage() {
		// i have returned full class name so that i could make an exception handler for it in the future
		return "Error: " . get_class($this->exception);
	}
}