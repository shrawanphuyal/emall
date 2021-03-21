<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\Http\Requests\AdvertisementRequest;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvertisementController extends AsdhController {
	private $viewPath = 'admin.advertisement';
	private $prefix = 'a';

	public function __construct() {
		parent::__construct();
		$this->website['routeType'] = 'advertisement';
	}

	public function index() {
		$this->website['advertisements'] = Advertisement::with('image')->orderBy('home', 'desc')->latest()->get();

		return view("{$this->viewPath}.index", $this->website);
	}

	public function create() {
		$this->website['edit'] = false;

		return view("{$this->viewPath}.create", $this->website);
	}

	public function store(AdvertisementRequest $request) {
		DB::transaction(function () use ($request) {
			$advertisement = Advertisement::create([
				'title' => $request->input('title'),
				'url'   => $request->input('url'),
				'home'  => $request->input('home') ? 1 : 0,
			]);

			if ( ! is_null($request->file('image'))) {
				$image_name = upload_image_modified($request->file('image'), $this->prefix);
				$advertisement->image()->create(['image' => $image_name]);
			}
		});

		return redirect()->route($this->website['routeType'] . '.index')->with('success_message', 'Advertisement successfully added.');
	}

	public function edit(Advertisement $advertisement) {
		$this->website['edit']  = true;
		$this->website['model'] = $advertisement;

		return view("{$this->viewPath}.create", $this->website);
	}

	public function update(AdvertisementRequest $request, Advertisement $advertisement) {
		$advertisement->update([
			'title' => $request->input('title'),
			'url'   => $request->input('url'),
			'home'  => $request->input('home') ? 1 : 0,
		]);

		if ( ! is_null($request->file('image'))) {
			$image_name = upload_image_modified($request->file('image'), $this->prefix);

			/** @var Image $image_model */
			$image_model = $advertisement->image;
			if ($image_model) {
				$image_model->delete_image();
				$advertisement->image()->update(['image' => $image_name]);
			} else {
				$advertisement->image()->create(['image' => $image_name]);
			}
		}

		return back()->with('success_message', 'Advertisement successfully updated.');
	}

	public function destroy(Advertisement $advertisement) {
		try {
			$advertisement->image->delete_image();
			$advertisement->image()->delete();
			$advertisement->delete();

			return back()->with('success_message', 'Advertisement successfully deleted.');
		} catch (\Exception $exception) {
			return back()->with('failure_message', 'Advertisement could not be deleted. Please try again later.');
		}
	}
}
