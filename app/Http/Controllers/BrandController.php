<?php

namespace App\Http\Controllers;

use App\Brand;
use Illuminate\Http\Request;

class BrandController extends AsdhController {
	private $viewPath = 'admin.slider';
	protected $prefix = 'brand';

	public function __construct() {
		parent::__construct();
		$this->website['routeType'] = 'brand';
	}

	public function index() {
		$this->website['models'] = Brand::latest()->select('id', 'image', 'url')->get();

		return view($this->viewPath . '.index', $this->website);
	}

	public function create() {
		$this->website['edit'] = false;

		return view("{$this->viewPath}.create", $this->website);
	}

	public function store(Request $request) {
		$image_name = null;
		if ( ! is_null($request->file('image'))) {
			$image_name = upload_image_modified($request->file('image'), $this->prefix);
		}

		return Brand::create([
			'image' => $image_name,
			'url'   => $request->input('url'),
		])
			? redirect()->route($this->website['routeType'] . '.index')->with('success_message', 'Brand successfully added.')
			: redirect()->route($this->website['routeType'] . '.index')->with('failure_message', 'Brand could not be added. Please try again later.');
	}

	public function edit(Brand $brand) {
		$this->website['edit']  = true;
		$this->website['model'] = $brand;

		return view("{$this->viewPath}.create", $this->website);
	}

	public function update(Request $request, Brand $brand) {
		$image_name = $brand->getOriginal('image');
		if ( ! is_null($request->file('image'))) {
			$brand->delete_image();
			$image_name = upload_image_modified($request->file('image'), $this->prefix);
		}

		return $brand->update([
			'image' => $image_name,
			'url'   => $request->input('url'),
		])
			? back()->with('success_message', 'Brand successfully updated.')
			: back()->with('failure_message', 'Brand could not be updated. Please try again later.');
	}

	public function destroy(Brand $brand) {
		try {
			$brand->delete();
			$brand->delete_image();

			return back()->with('success_message', 'Brand successfully deleted.');
		} catch (\Exception $exception) {
			return back()->with('failure_message', 'Brand could not be deleted. Please try again later.');
		}
	}
}
