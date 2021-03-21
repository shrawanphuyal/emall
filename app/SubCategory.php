<?php

namespace App;

class SubCategory extends Model {
	public function category() {
		return $this->belongsTo(Category::class);
	}

	public function children() {
		return $this->hasMany(SubSubCategory::class, 'sub_category_id');
	}

	public function products() {
		return $this->hasMany(Product::class);
	}

	public function has_children() {
		return $this->children->count();
	}

	public function has_products() {
		return $this->products()->count();
	}
}
