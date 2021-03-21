<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model {
	protected $guarded = ['id'];

	public function image() {
		return $this->morphOne(Image::class, 'model', 'model_type', 'model_id');
	}

	public function showImage() {
		return $this->image ? $this->image->image : asset("public/images/no-image.jpg") ;
	}

	public function showImageCropped($x, $y) {
		return $this->image ? $this->image->image($x, $y) : asset("public/images/no-image.jpg") ;
	}
}
