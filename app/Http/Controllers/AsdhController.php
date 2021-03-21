<?php

namespace App\Http\Controllers;

use App\Company;
use App\Custom\Cart;
use Illuminate\Support\Facades\Cache;

class AsdhController extends Controller {
	protected $website = [];
	protected $default_pagination_limit = 20;

	public function __construct() {
		ini_set('memory_limit', - 1);
		Cache::forget('company-info');
		$this->website['company'] = Cache::remember('company-info', 60 * 24 * 30, function () {
			return Company::find(1);
		});

		$this->middleware(function($request, $next) {
			$this->website['cartItems'] = Cart::getItems();
			$this->website['cartCount'] = Cart::itemsCount();

			return $next($request);
		});
	}
}
