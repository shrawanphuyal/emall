<?php

namespace App\Http\Controllers;

use App\VendorCategory;
use Illuminate\Http\Request;

class VendorCategoryController extends AsdhController {
	public function __construct() {
		parent::__construct();
		$this->website['routeType'] = 'vendor-category';
	}

	public function create() {
		$this->website['edit']       = false;
		$this->website['categories'] = VendorCategory::latest()->get();

		return view('admin.vendor.category.create', $this->website);
	}

	public function store(Request $request) {
		if ($request->ajax()) {
			$category = VendorCategory::create([
				'name' => $request->category_name,
				'slug' => asdh_str_slug($request->category_name),
			]);

			return $category
				? response()->json(['category' => $category, 'message' => 'Vendor Category successfully added.'])
				: response()->json(['message' => 'Vendor Category could not be added. Please try again later.']);
		}

		$validData = $request->validate(['name.*' => 'required|string|max:255']);
		foreach ($validData['name'] as $validateDatum) {
			VendorCategory::create([
				'name' => $validateDatum,
				'slug' => asdh_str_slug($validateDatum),
			]);
		}

		return back()->with('success_message', 'Vendor Category successfully added.');
	}

	public function edit(VendorCategory $vendor_category) {
		$this->website['edit']       = true;
		$this->website['category']   = $vendor_category;
		$this->website['categories'] = VendorCategory::latest()->get();

		return view('admin.vendor.category.create', $this->website);
	}

	public function update(Request $request, VendorCategory $vendor_category) {
		$validData = $request->validate(['name' => 'required|string|max:255']);

		return $vendor_category->update([
			'name' => $validData['name'],
		])
			? back()->with('success_message', 'Vendor Category successfully updated.')
			: back()->with('failure_message', 'Vendor Category could not be updated. Please try again later.');
	}

	public function destroy(VendorCategory $vendor_category) {
		return $vendor_category->delete()
			? redirect()->route('vendor-category.create')->with('success_message', 'Vendor Category successfully deleted.')
			: redirect()->route('vendor-category.create')->with('failure_message', 'Vendor Category could not be deleted. Please try again later.');
	}
}
