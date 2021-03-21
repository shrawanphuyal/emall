<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SliderResource;
use App\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SliderController extends Controller {
	public function index() {
		return SliderResource::collection(Slider::get());
	}
}
