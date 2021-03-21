<?php

namespace App\Http\Controllers\Frontend;

use App\Company;
use App\Custom\Cart;
use App\Custom\Payment\PaymentFactory;
use App\Mail\OrderPlaced;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
	private $orderedProducts = [];
	protected $paymentType = '';

	public function process(Request $request)
	{
		$request->validate([
			'paymentType' => 'required|string|in:paypal,normal',
			'paymentId'   => 'required_unless:paymentType,normal',
			'products'    => 'required|array',
		]);
		$this->paymentType = $request->paymentType;

		$shippingType = $request->shippingAddress['type'] ?? [];

		$totalPrice = $this->getTotalPriceOfOrder($request->products, $shippingType);

		$payment = PaymentFactory::make($this->paymentType);
		$payment->verify($request->paymentId, $totalPrice, null);

		if ($payment->isVerified()) {
			/** @var Order $order */
			$order = (new OrderController)->save($request->products);

			Mail::to($request->user()->email)->send(new OrderPlaced($order, $request->products, $totalPrice, $this->paymentType));

			Cart::remove('cart-items');

			return successResponse('Success');
		}

		return failureResponse($payment->getErrorMessage());
	}

	/**
	 * Get total price of the order from server
	 *
	 * @param $products
	 *
	 * @param $shippingType
	 *
	 * @return float
	 */
	private function getTotalPriceOfOrder($products, $shippingType): float
	{
		$conversionRate = (float) Company::firstOrFail()->conversion_rate;
		
		$productsPrice = collect($products)->reduce(function ($carry, $product) use ($conversionRate) {
			/** @var Product $item */
			$item                    = Product::where('slug', $product['slug'])->firstOrFail();
			$item->quantity          = $product['quantity'];
			$this->orderedProducts[] = $item;

			$sellingPrice = $item->sellingPrice();
			if ($this->paymentType == 'paypal') {
				$sellingPrice = number_format($sellingPrice / $conversionRate, 2, '.', '');
			}

			return $carry + ($sellingPrice * $product['quantity']);
		}, 0);

		if (count($shippingType)) {
			return $productsPrice + $shippingType['price'];
		}

		return $productsPrice;
	}
}
