<?php

namespace App;

class SubSubCategory extends Model {
	public function parent() {
		return $this->belongsTo(SubCategory::class, 'sub_category_id');
	}

	public function products() {
		return $this->hasMany(Product::class);
	}

	public function has_products() {
		return $this->products()->count();
	}
}
