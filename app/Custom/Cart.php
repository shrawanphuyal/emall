<?php

namespace App\Custom;

class Cart {
	public static function add($item, $sessionVariable = 'cart-items') {
		if (session()->has($sessionVariable)) {
			$itemIsPresent = self::checkIfItemIsPresent($item, $sessionVariable);
			if ( ! $itemIsPresent) {
				session()->push($sessionVariable, $item);
			} else {
				$cartItems = self::getItems($sessionVariable)->except(key($itemIsPresent))->push($item);
				session()->put($sessionVariable, $cartItems);
			}
		} else {
			session()->put($sessionVariable, [$item]);
		}
	}

	public static function remove($sessionVariable = 'cart-items', $item = null) {
		if (is_null($item)) {
			session()->forget($sessionVariable);
		} else {
			if ($item = self::checkIfItemIsPresent($item, $sessionVariable)) {
				$session_items   = session()->get($sessionVariable);
				$newSessionItems = array_except($session_items, [key($item)]);
				session()->put($sessionVariable, $newSessionItems);
			}
		}
	}

	public static function getItems($sessionVariable = 'cart-items') {
		if (session()->has($sessionVariable)) {
			return collect(session($sessionVariable))->values();
		}

		return collect([]);
	}

	public static function itemsCount($sessionVariable = 'cart-items') {
		if (session()->has($sessionVariable)) {
			return count(session($sessionVariable));
		}

		return 0;
	}

	public static function totalPrice($sessionVariable = 'cart-items') {
		if (session()->has($sessionVariable)) {
			return self::getItems($sessionVariable)->sum(function ($item) {
				return $item->sellingPrice() * $item->cart_quantity;
			});
		}

		return 0;
	}

	private static function checkIfItemIsPresent($item, $sessionVariable) {
		foreach (session()->get($sessionVariable) as $key => $cartItem) {
			// i returned item instead of true because we can remove specific item from cart if we want to.
			if ($cartItem->id == $item->id) {
				return [$key => $item];
			}
		}

		return false;
	}
}
