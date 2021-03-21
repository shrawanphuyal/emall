<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource {
	protected $showToken;

	public function __construct($resource, $showToken = false) {
		parent::__construct($resource);
		$this->showToken = $showToken;
	}

	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request
	 *
	 * @return array
	 */
	public function toArray($request) {
		return [
			'id'      => $this->id,
			'name'    => $this->name,
			'email'   => $this->email,
			'phone'   => $this->phone,
			'address' => $this->address,
			'image'   => $this->image,
			'token'   => $this->when($this->showToken, $this->auth_token),
		];
	}
}
