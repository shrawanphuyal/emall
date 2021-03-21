<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller {

	public function products(Request $request) {
		$products = Product::with(['images', 'category', 'sub_category', 'sub_sub_category']);

		if ($request->has('latest')) {
			$products->latest();
		}

		if ($request->has('hot')) {
			$products->hot();
		}

		if ($request->has('featured')) {
			$products->featured();
		}

		if ($request->has('sale')) {
			$products->sale();
		}

		return ProductResource::collection($products->paginate(10));
	}

	public function latest() {
		$products = Product::latest()->limit(10)->get();

		return ProductResource::collection($products);
	}

	public function detail(Product $product) {
		return new ProductResource($product);
	}

	public function favourite() {
		$user     = auth()->guard('api')->user();
		$products = $user->favouriteProducts()->with(['images', 'category', 'sub_category', 'sub_sub_category'])->paginate(10);

		return ProductResource::collection($products);
	}

	public function makeFavourite($productId) {
		auth()->guard('api')->user()->favouriteProducts()->syncWithoutDetaching($productId);

		return successResponse('Product marked as favourite.');
	}

	public function removeFromFavourite($productId) {
		auth()->guard('api')->user()->favouriteProducts()->detach($productId);

		return successResponse('Product removed from favourite.');
	}
}
