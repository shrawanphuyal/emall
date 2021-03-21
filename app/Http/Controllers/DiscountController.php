<?php

namespace App\Http\Controllers;

use App\Category;
use App\SubCategory;
use Illuminate\Http\Request;

class DiscountController extends AsdhController {
	public function by_category() {
		$allCategoriesUsedByAuthUser = auth()->user()->products->pluck('category_id')->unique()->toArray();
		$this->website['categories'] = Category::whereIn('id', $allCategoriesUsedByAuthUser)->get();

		return view('admin.discount.by-category', $this->website);
	}

	public function by_category_post(Request $request, Category $category) {
		$request->validate(['discount_percentage' => 'required|integer|min:0|max:100']);

		auth()->user()->products()
		      ->where('category_id', $category->id)
		      ->update([
			      'discount'      => $request->discount_percentage,
			      'discount_type' => 0,
		      ]);

		return redirect()->route('product.index')->with('success_message', 'Discount percentage successfully assigned to all the products within "' . $category->name . '" category.');
	}

	public function by_sub_category() {
		$allSubCategoriesUsedByAuthUser  = auth()->user()->products->pluck('sub_category_id')->unique()->toArray();
		$this->website['sub_categories'] = SubCategory::with('category')->whereIn('id', $allSubCategoriesUsedByAuthUser)->get();

		return view('admin.discount.by-sub-category', $this->website);
	}

	public function by_sub_category_post(Request $request, SubCategory $sub_category) {
		$request->validate(['discount_percentage' => 'required|integer|min:0|max:100']);

		auth()->user()->products()
		      ->where('sub_category_id', $sub_category->id)
		      ->update([
			      'discount'      => $request->discount_percentage,
			      'discount_type' => 0,
		      ]);

		return redirect()->route('product.index')->with('success_message', 'Discount percentage successfully assigned to all the products within "' . $sub_category->name . '" sub-category.');
	}
}
