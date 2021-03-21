<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class AdminProductController extends AsdhController {
	public function all() {
		$this->website['products'] = Product::latest()->paginate($this->default_pagination_limit);

		return view('admin.product.all', $this->website);
	}

	public function assign_profit_percentage(Request $request) {
		$request->validate([
			'product_ids'       => 'required|array',
			'product_ids.*'     => 'required|integer|min:1',
			'profit_percentage' => 'required|integer|min:0|max:100',
		], [
			'product_ids.required' => 'You must select at-least one product.',
		]);

		Product::whereIn('id', $request->product_ids)
		       ->update(['admin_profit_percentage' => $request->profit_percentage]);

		return back()->with(['success_message' => 'Profit percentage successfully added.']);
	}
}
