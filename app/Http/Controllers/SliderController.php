<?php

namespace App\Http\Controllers;

use App\Slider;
use Illuminate\Http\Request;

class SliderController extends AsdhController {
	private $viewPath = 'admin.slider';
	protected $prefix = 'slider';

	public function __construct() {
		parent::__construct();
		$this->website['routeType'] = 'slider';
	}

	public function index() {
		$this->website['models'] = Slider::select('id', 'image', 'url')->get();

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

		return Slider::create([
			'image' => $image_name,
			'url'   => $request->input('url'),
		])
			? redirect()->route($this->website['routeType'] . '.index')->with('success_message', 'Slider successfully added.')
			: redirect()->route($this->website['routeType'] . '.index')->with('failure_message', 'Slider could not be added. Please try again later.');
	}

	public function edit(Slider $slider) {
		$this->website['edit']  = true;
		$this->website['model'] = $slider;

		return view("{$this->viewPath}.create", $this->website);
	}

	public function update(Request $request, Slider $slider) {
		$image_name = $slider->getOriginal('image');
		if ( ! is_null($request->file('image'))) {
			$slider->delete_image();
			$image_name = upload_image_modified($request->file('image'), $this->prefix);
		}

		return $slider->update([
			'image' => $image_name,
			'url'   => $request->input('url'),
		])
			? back()->with('success_message', 'Slider successfully updated.')
			: back()->with('failure_message', 'Slider could not be updated. Please try again later.');
	}

	public function destroy(Slider $slider) {
		try {
			$slider->delete();
			$slider->delete_image();

			return back()->with('success_message', 'Slider successfully deleted.');
		} catch (\Exception $exception) {
			return back()->with('failure_message', 'Slider could not be deleted. Please try again later.');
		}
	}

}
