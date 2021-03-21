<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\SubCategory;
use App\SubSubCategory;
use Illuminate\Http\Request;

class CategoryController extends CommonController
{
	public function index(Request $request)
	{
		$levelOne = $request->query('levelOne');
		$levelTwo = $request->query('levelTwo');

		if ($levelTwo != 0) {
			$categories = SubCategory::findOrFail($levelTwo)->children()->has('products')->get();
		} elseif ($levelOne != 0) {
			$categories = Category::findOrFail($levelOne)->sub_categories()->has('products')->get();
		} else {
			$categories = Category::has('products')->orderBy('priority', 'desc')->get();
		}

		return CategoryResource::collection($categories);
	}

	public function subCategories(Category $category)
	{
		$subCategories = $category->sub_categories;

		return CategoryResource::collection($subCategories);
	}

	public function products(Request $request)
	{
		$levelOne   = $request->query('levelOne');
		$levelTwo   = $request->query('levelTwo');
		$levelThree = $request->query('levelThree');

		if ($levelThree != 0) {
			$category = SubSubCategory::findOrFail($levelThree);
		} elseif ($levelTwo != 0) {
			$category = SubCategory::findOrFail($levelTwo);
		} elseif ($levelOne != 0) {
			$category = Category::findOrFail($levelOne);
		} else {
			return failureResponse('Not allowed');
		}

		$products = $category->products()->with('category:id,name', 'sub_category:id,name', 'sub_sub_category:id,name')->paginate(10);

		//return ($products);

		return ProductResource::collection($products);
	}

	public function getSubSubCategories(SubCategory $sub_category)
	{
		return response()->json($sub_category->children()->select('id', 'name')->get());
	}
}
