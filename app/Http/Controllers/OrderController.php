<?php

namespace App\Http\Controllers;

use App\Mail\OrderDelivered;
use App\Mail\OrderProcessing;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends AsdhController {
	private $viewPath = 'admin.order';

	public function __construct() {
		parent::__construct();
		$this->website['routeType'] = 'order';
	}

	public function index() {
		if (auth()->user()->hasRole('admin')) {
			$this->website['models'] = Order::with('user')->latest()->get();
		} else {
			$this->website['models'] = Order::with('user')
			                                ->whereHas('products.product', function ($query) {
				                                $query->where('vendor_id', auth()->id());
			                                })
			                                ->latest()->get();
		}

		return view($this->viewPath . '.index', $this->website);
	}

	public function getProducts(Order $order) {
		return response()->json($order->products()->with('product:id,title,slug')->get())->withHeaders([
			'Cache-Control' => 'public, max-age=604800', // caches for one week
		]);
	}

	public function changeStatus(Order $order) {
		$status = request()->status;

		$email = $order->user->email;

		if($status == 'processing') {
			Mail::to($email)->send(new OrderProcessing($order));
		} else if ($status == 'delivered') {
			Mail::to($email)->send(new OrderDelivered($order));
		}

		$order->status = $status;
		$order->save();

		return response()->json('Order status changed to ' . $status);
	}
}
