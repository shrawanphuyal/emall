<?php

namespace App\Http\Controllers;

use App\News;
use App\Product;
use App\Subscriber;
use App\User;
use Carbon\Carbon;

class AdminController extends AsdhController
{
	public function index()
	{
		$this->website['total_users']       = User::count();
		$this->website['total_products']    = Product::count();
		$this->website['news_24_hrs']       = News::whereBetween('created_at', [
			Carbon::now(),
			Carbon::now()->subDay(),
		])->count();
		$this->website['total_subscribers'] = Subscriber::count();

		return view('admin.index', $this->website);
	}

	public function subscribers()
	{
		$this->website['routeType']   = 'subscriber';
		$this->website['subscribers'] = Subscriber::latest()->paginate($this->default_pagination_limit);

		return view('admin.subscriber.index', $this->website);
	}

	public function changeConversionRate()
	{
		$company                  = $this->website['company'];
		$company->conversion_rate = request()->conversion_rate;
		$company->save();
	}
}
