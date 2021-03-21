<?php

namespace App;

class Image extends Model {
	protected $guarded = ['id'];
	public $timestamps = false;

	public function parent() {
		return $this->morphTo('model', 'model_type', 'model_id');
	}
}
