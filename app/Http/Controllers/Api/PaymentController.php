<?php

namespace App\Http\Controllers\Api;

use App\Custom\Payment\PaymentContract;
use App\Http\Requests\Api\CheckoutRequest;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class PaymentController extends Controller
{
	private $paymentType;
	private $token;
	private $amount;
	private $orderedProductsFromDatabase;

	public function __construct(Request $request)
	{
		$this->paymentType = $request->input('paymentType');
		$this->token       = $request->input('token');
		//$this->amount      = $request->input('amount');
	}

	public function process(CheckoutRequest $request)
	{
		return $this->handle($request, $this->parseClassFromPaymentGatewayName());
	}

	private function parseClassFromPaymentGatewayName()
	{
		$class = "\\App\\Custom\\Payment\\" . ucfirst($this->paymentType);

		return new $class;
	}

	private function handle(CheckoutRequest $request, PaymentContract $paymentGateway)
	{
		$this->calculateTotalAmount($request);

		$paymentGateway->verify($this->token, $this->amount * 100);

		if ( ! $paymentGateway->isVerified()) {
			return failureResponse($paymentGateway->getErrorMessage());
		}

		$this->addDataToDatabase($request, $paymentGateway);

		return successResponse('Your order is successfully placed. We will contact you soon.');
	}

	private function addDataToDatabase(CheckoutRequest $request, PaymentContract $paymentGateway)
	{
		DB::transaction(function () use ($request, $paymentGateway) {
			$user = auth()->guard('api')->user();

			$user->phone   = $request->input('phone');
			$user->address = $request->input('address');
			$user->save();

			/** @var Order $order */
			$order = Order::create([
				'user_id' => $user->id,
				'payment_type' => $this->paymentType,
				'payment_detail' => $paymentGateway->getResponse()
			]);

			$dataToSave = [];
			foreach ($request->cartItems as $item) {
				if($request->deviceType === 'ios') {
					$id       = $item[0];
					$quantity = $item[1];
					$size     = $item[2] ?? 0;
				} else {
					$id       = $item['id'];
					$quantity = $item['quantity'];
					$size     = $item['size'] ?? 0;
				}

				/** @var Product $product */
				$product = $this->orderedProductsFromDatabase()->firstWhere('id', $id);
				if(!$product) {
					throw new Exception("Product with id:{$product->id} not found.");
				}

				$hasEnoughQuantity = $product->quantity > $quantity;
				if ( ! $hasEnoughQuantity) {
					throw new \Exception("{$product->title} has only {$product->quantity} items left.", 422);
				}

				$dataToSave[] = [
					'product_id' => $id,
					'rate'       => $product->sellingPrice(),
					'quantity'   => $quantity,
					'size'       => $size,
				];

				if ($hasEnoughQuantity) {
					$product->decrement('quantity', $item['quantity']);
				}
			}

			$order->products()->createMany($dataToSave);
		});
	}

	private function calculateTotalAmount(CheckoutRequest $request)
	{
		$totalPrice = 0;
		foreach ($request->cartItems as $item) {
			/** @var Product $product */
			$product = $this->orderedProductsFromDatabase[] = Product::findOrFail($item['id']);

			$hasEnoughQuantity = $product->quantity > $item['quantity'];
			if ( ! $hasEnoughQuantity) {
				throw new \Exception("{$product->title} has only {$product->quantity} items left.", 422);
			}

			$totalPrice += $product->sellingPrice() * $item['quantity'];
		}

		$this->amount =  $totalPrice;
	}

	private function orderedProductsFromDatabase()
	{
		return collect($this->orderedProductsFromDatabase);
	}
}
