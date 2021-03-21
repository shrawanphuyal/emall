<?php

namespace App;

class Category extends Model {
	public function getNameAttribute($value) {
		return ucfirst($value);
	}

	public function sub_categories() {
		return $this->hasMany(SubCategory::class);
	}

	public function products() {
		return $this->hasMany(Product::class);
	}

	public function has_sub_categories() {
		return $this->sub_categories->count();
	}

	public function has_products() {
		return $this->products()->count();
	}
}
