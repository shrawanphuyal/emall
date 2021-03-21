<?php

namespace App;

class Product extends Model
{
	public $cart_quantity = 1;
	public $cart_size = 1;

	public function category()
	{
		return $this->belongsTo(Category::class)->withDefault([
			'id'   => 0,
			'name' => '',
		]);
	}

	public function sub_category()
	{
		return $this->belongsTo(SubCategory::class)->withDefault([
			'id'   => 0,
			'name' => '',
		]);
	}

	public function sub_sub_category()
	{
		return $this->belongsTo(SubSubCategory::class)->withDefault([
			'id'   => 0,
			'name' => '',
		]);
	}

	public function vendor()
	{
		return $this->belongsTo(User::class, 'vendor_id', 'id');
	}

	public function images()
	{
		return $this->morphMany(Image::class, 'model', 'model_type', 'model_id');
	}

	public function firstImage()
	{
		return $this->morphOne(Image::class, 'model', 'model_type', 'model_id');
	}

	public function reviews()
	{
		return $this->hasMany(Review::class);
	}

	public function rating()
	{
		$sum = $this->reviews->reduce(function ($sum, $item) {
			return $sum + $item->rating;
		}, 0);

		if ($sum == 0) {
			return 0;
		}

		return number_format($sum / $this->reviews->count(), '1', '.', '');
	}

	public function hasImages()
	{
		return $this->images->count() > 0;
	}

	public function getFirstImage()
	{
		if ($this->hasImages()) {
			return $this->images->first()->image;
		}

		return asset("public/images/no-image.jpg");
	}

	public function getFirstImageCropped($x, $y)
	{
		if ($this->hasImages()) {
			return $this->images->first()->image($x, $y);
		}

		return $this->image($x, $y, 'no-image');
	}

	public function is_of_logged_in_vendor()
	{
		return optional($this->vendor)->id == auth()->id();
	}

	public function hasAmountDiscountType()
	{
		return $this->discount_type == 1;
	}

	public function hasPercentageDiscountType()
	{
		return $this->discount_type == 0;
	}

	public function hasDiscount()
	{
		return $this->discount > 0;
	}

	public function discountAmount()
	{
		$costPrice = $this->price;
		if ($this->hasPercentageDiscountType()) {
			$discountPercentage = $this->discount;
			$discountAmount     = ($discountPercentage / 100) * $costPrice;
		} else {
			$discountAmount = $this->discount;
		}

		return $discountAmount;
	}

	public function discountPercentage()
	{
		return (int) ($this->hasPercentageDiscountType()
			? $this->discount
			: $this->discountAmount() / $this->price * 100);
	}

	public function sellingPrice()
	{
		$costPrice = $this->price;
		if ($this->hasPercentageDiscountType()) {
			$discountPercentage = $this->discount;
			$discountAmount     = ($discountPercentage / 100) * $costPrice;
			$sellingPrice       = $costPrice - $discountAmount;
		} else {
			$discountAmount = $this->discount;
			$sellingPrice   = $costPrice - $discountAmount;
		}


		return $sellingPrice;
	}

	public function sellingPriceFixed()
	{
		return number_format($this->sellingPrice(), 2, '.', '');
	}

	public function scopeMale($query)
	{
		return $query->where('gender', 1);
	}

	public function scopeFemale($query)
	{
		return $query->where('gender', 0);
	}

	public function scopeFeatured($query)
	{
		return $query->where('featured', 1);
	}

	public function scopeSale($query)
	{
		return $query->where('sale', 1);
	}

	public function scopeHot($query)
	{
		return $query->where('hot', 1);
	}

	public function scopeSearch($query)
	{
		$slug    = request()->query('category');
		$keyword = '%' . request()->query('keyword') . '%';

		return $query
			->when($slug, function ($query) use ($keyword, $slug) {
				return $query
					->where('slug', $slug)
					->where(function ($query) use ($keyword) {
						return $query
							->where('title', 'like', $keyword)
							->orWhere('description', 'like', $keyword);
					});
			})
			->when(! $slug, function ($query) use ($keyword) {
				return $query
					->where('title', 'like', $keyword)
					->orWhere('description', 'like', $keyword);
			});
	}
}
