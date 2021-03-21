<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject {
	use Notifiable;

	protected $guarded = ['id'];
	protected $dates = ['dob'];

	protected $hidden = [
		'password',
		'remember_token',
	];

	public function getNameAttribute($value) {
		return ucwords($value);
	}

	public function categories() {
		return $this->belongsToMany(VendorCategory::class);
	}

	public function email_verified() {
		$this->verified    = 1;
		$this->email_token = null;
		$this->save();
	}

	public function verified() {
		return $this->verified ? "Yes" : "No";
	}

	public function isVerified()
	{
		return $this->verified == 1;
	}

	public function products() {
		return $this->hasMany(Product::class, 'vendor_id', 'id');
	}

	public function orders() {
		return $this->hasMany(Order::class);
	}

	public function roles() {
		return $this->belongsToMany(Role::class)->withTimestamps();
	}

	public function favouriteProducts() {
		return $this->belongsToMany(Product::class, 'user_favourite_products');
	}

	public function hasRole($role_name) {
		foreach ($this->roles as $role) {
			if ($role->name == $role_name) {
				return true;
			}
		}

		return false;
	}

	public function has_role($role_id) {
		foreach ($this->roles as $role) {
			if ($role->id == $role_id) {
				return true;
			}
		}

		return false;
	}

	public function hasOnlyRole($role_name) {
		return (count($this->roles) === 1 && $this->roles()->first()->name == $role_name);
	}

	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier() {
		return $this->getKey();
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
	public function getJWTCustomClaims() {
		return [];
	}
}
