<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
use App\Custom\Cart;
use App\Http\Controllers\AsdhController;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CartController extends AsdhController
{
	public $cartVariable = 'cart-items';

	public function __construct()
	{
		parent::__construct();
		$this->website['allCategories'] = Category::select('id', 'name', 'slug')->get();
	}

	public function addToCart($slug, Request $request)
	{
		/** @var Product $product */
		$product                = Product::whereSlug($slug)->firstOrFail();
		$product->cart_quantity = $request->quantity ?? 1;
		$product->cart_size     = $request->size ?? "";
		$product->firstImage    = $product->getFirstImage();
		Cart::add($product, $this->cartVariable);

		return back()->with('success_message', 'Product added to cart.');
	}

	public function removeFromCart($slug, Request $request)
	{
		/** @var Product $product */
		$product = Product::whereSlug($slug)->firstOrFail();
		Cart::remove('cart-items', $product);

		return back()->with('success_message', 'Product removed from cart.');
	}

	public function checkout()
	{
		$order = (new OrderController)->save(Cart::getItems('cart-items'));

		return redirect()->route('home')->with('success_message', 'Your order is placed successfully. We will contact you as soon as possible.');
	}

	public function cartItems()
	{
		return view('cart', $this->website);
	}
}
