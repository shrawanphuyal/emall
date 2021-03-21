<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model {
	protected $guarded = ['id'];

	public function setRatingAttribute($value) {
		return $this->attributes['rating'] = $value * 10;
	}

	public function getRatingAttribute($value) {
		return $value / 10;
	}

	public function user() {
		return $this->belongsTo(User::class);
	}
}
