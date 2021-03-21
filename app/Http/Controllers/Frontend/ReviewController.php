<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\AsdhController;
use App\Product;
use App\Review;
use Illuminate\Http\Request;

class ReviewController extends AsdhController
{
	public function store(Request $request, $productSlug)
	{
		$request->validate([
			'rating' => 'required|numeric|min:1|max:5',
			'review' => 'nullable|string',
		]);

		$product = Product::where('slug', $productSlug)->firstOrFail();

		Review::updateOrCreate(
			['user_id' => auth()->id(), 'product_id' => $product->id],
			['rating' => $request->rating, 'review' => $request->review]
		);

		return back()->with('success_message', 'Your review has been submitted.');
	}
}
