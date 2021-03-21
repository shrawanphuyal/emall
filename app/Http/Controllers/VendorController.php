<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\VendorCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorController extends AsdhController {
	public function index() {
		$this->website['categories'] = VendorCategory::orderBy('name')->get();
		$this->website['vendors']    = Role::where('name', 'vendor')->first()->users()->latest()->paginate($this->default_pagination_limit);

		return view('admin.vendor.index', $this->website);
	}

	public function show(User $vendor) {
		$this->website['vendor']   = $vendor;
		$this->website['products'] = $vendor->products()->orderBy('featured', 'desc')->latest()->paginate($this->default_pagination_limit);

		return view('admin.vendor.show', $this->website);
	}

	public function assign_category(Request $request) {
		$request->validate([
			'vendor_ids'     => 'required|array',
			'vendor_ids.*'   => [
				'required',
				'integer',
				'min:1',
				Rule::unique('user_vendor_category', 'user_id')->where(function ($query) use ($request) {
					$query->whereIn('vendor_category_id', $request->category_ids);
				}),
			],
			'category_ids'   => 'required|array',
			'category_ids.*' => [
				'required',
				'integer',
				'min:1',
				/*Rule::unique('user_vendor_category', 'vendor_category_id')->where(function ($query) use ($request) {
					$query->whereIn('user_id', is_null($request->vendor_ids) ? [] : $request->vendor_ids);
				}),*/
			],
		], [
			'vendor_ids.required'   => 'You must select at-least one product.',
			'category_ids.required' => 'You must select at-least one category.',
			'vendor_ids.*.unique'   => 'Vendor is already assigned to category.',
		]);

		$vendors = User::whereIn('id', $request->vendor_ids)->get();
		foreach ($vendors as $vendor) {
			$vendor->categories()->attach($request->category_ids);
		}

		return back()->with('success_message', 'Vendors successfully assigned to category.');
	}
}
