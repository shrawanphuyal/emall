<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Order;
use App\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
	public function checkout(CheckoutRequest $request)
	{
		DB::transaction(function () use ($request) {
			$user = auth()->guard('api')->user();

			$user->phone   = $request->input('phone');
			$user->address = $request->input('address');
			$user->save();

			/** @var Order $order */
			$order = Order::create(['user_id' => $user->id]);

			$dataToSave = [];
			foreach ($request->cartItems as $item) {
				/** @var Product $product */
				$product = Product::findOrFail($item['id']);

				$hasEnoughQuantity = $product->quantity > $item['quantity'];
				if ( ! $hasEnoughQuantity) {
					throw new \Exception("{$product->title} has only {$product->quantity} items left.", 422);
				}

				$dataToSave[] = [
					'product_id' => $item['id'],
					'rate'       => $product->sellingPrice(),
					'quantity'   => $item['quantity'],
					'size'       => $item['size'] ?? 0,
				];

				if ($hasEnoughQuantity) {
					$product->decrement('quantity', $item['quantity']);
				}
			}

			$order->products()->createMany($dataToSave);
		});

		return successResponse('Order successfully placed. We will contact you soon.');
	}

	public function checkoutIos(CheckoutRequest $request)
	{
		DB::transaction(function () use ($request) {
			$user = auth()->guard('api')->user();

			$user->phone   = $request->input('phone');
			$user->address = $request->input('address');
			$user->save();

			/** @var Order $order */
			$order = Order::create(['user_id' => $user->id]);

			$dataToSave = [];
			foreach ($request->cartItems as $item) {
				$id       = $item[0];
				$quantity = $item[1];
				$size     = $item[2];
				/** @var Product $product */
				$product = Product::findOrFail($id);

				$hasEnoughQuantity = $product->quantity > $quantity;
				if ( ! $hasEnoughQuantity) {
					throw new \Exception("{$product->title} has only {$product->quantity} items left.", 422);
				}

				$dataToSave[] = [
					'product_id' => $id,
					'rate'       => $product->sellingPrice(),
					'quantity'   => $quantity,
					'size'       => $size ?? 0,
				];

				if ($hasEnoughQuantity) {
					$product->decrement('quantity', $quantity);
				}
			}

			$order->products()->createMany($dataToSave);
		});

		return successResponse('Order successfully placed. We will contact you soon.');
	}

	public function myOrders()
	{
		$user = auth()->guard('api')->user();

		$orders = $user->orders()->with('products', 'products.product:id,title')->latest()->get();

		return OrderResource::collection($orders);
	}
}
