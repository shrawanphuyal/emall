<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $guarded = ['id'];

	protected $casts = [
		'payment_detail' => 'array'
	];

	public function products()
	{
		return $this->hasMany(OrderProduct::class);
	}

	public function realProducts()
	{
		return $this->belongsToMany(Product::class, 'order_products')->withPivot(['rate', 'quantity', 'size']);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
