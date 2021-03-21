<?php

namespace App;

class VendorCategory extends Model {
	public function vendors() {
		return $this->belongsToMany(User::class);
	}
}
