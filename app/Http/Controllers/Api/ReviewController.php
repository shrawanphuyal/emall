<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\RatingRequest;
use App\Product;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller {
	public function rate(RatingRequest $request, Product $product) {
		$product->reviews()->updateOrCreate(
			['user_id' => auth()->guard('api')->id()],
			['rating' => $request->rating, 'review' => $request->review]
		);

		return successResponse('Rating successfully made', Response::HTTP_CREATED);
	}
}
