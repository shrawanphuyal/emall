<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NewsResource;
use App\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller {
	public function index() {
		$news = News::get();

		return NewsResource::collection($news);
	}
}
