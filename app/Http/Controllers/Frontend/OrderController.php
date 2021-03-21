<?php

namespace App\Http\Controllers\Frontend;

use App\Order;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
	public function save($cartItems)
	{
		DB::transaction(function () use ($cartItems, &$order) {
			/** @var Order $order */
			$order = Order::create(['user_id' => auth()->id()]);

			$dataToSave = [];
			foreach ($cartItems as $item) {
				$dataToSave[] = [
					'product_id' => $item['id'],
					'rate'       => $item['sellingPrice'],
					'quantity'   => $item['quantity'],
					'size'       => $item['size'] ?? "",
				];

				Product::find($item['id'])->decrement('quantity', $item['quantity']);
			}

			$order->products()->createMany($dataToSave);
		});

		return $order;
	}
}
