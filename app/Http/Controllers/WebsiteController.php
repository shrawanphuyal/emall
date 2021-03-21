<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\Brand;
use App\Category;
use App\Custom\Cart;
use App\Mail\OrderPlaced;
use App\Mail\VerifyEmail;
use App\News;
use App\Order;
use App\Product;
use App\Role;
use App\Slider;
use App\SubCategory;
use App\Subscriber;
use App\SubSubCategory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class WebsiteController extends AsdhController
{

	public function __construct()
	{
		parent::__construct();
		$this->website['allCategories'] = Category::with('sub_categories:id,category_id,name,slug', 'sub_categories.children:id,sub_category_id,name,slug')
		                                          ->orderBy('priority')
		                                          ->select('id', 'name', 'slug', 'image')
		                                          ->get();
	}

	public function index()
	{
		$this->website['sliders']          = Slider::select('image', 'url')->get();
		$this->website['hotProducts']      = Product::with('images')->hot()->latest('updated_at')->limit(8)->get();
		$this->website['featuredProducts'] = Product::with('images')->featured()->latest('updated_at')->limit(8)->get();
		$this->website['recentProducts']   = Product::with('images')->latest()->limit(8)->get();
		$this->website['brands']           = Brand::select('image', 'url')->get();
		$this->website['latestNews']       = News::latest()->limit(4)->get();
		$this->website['advertisement']    = Advertisement::where('home', 1)->first();

		return response()->view('index', $this->website);
	}

	public function productDetail($slug)
	{
		$this->website['product'] = $product = Product::with('images', 'reviews:id,user_id,product_id,rating,review,created_at', 'reviews.user:id,name,image')->where('slug', $slug)->firstOrFail();

		$this->website['relatedProducts'] = Product::with('images')
		                                           ->where('id', '!=', $product->id)
		                                           ->where('category_id', $product->category_id)
		                                           ->limit(6)->get();

		return view('product-detail', $this->website);
	}

	public function newsDetail($slug)
	{
		$this->website['news'] = News::where('slug', $slug)->firstOrFail();

		return view('news_detail', $this->website);
	}

	public function allNews()
	{
		$this->website['allNews'] = News::latest()->paginate(5);

		return view('all_news', $this->website);
	}

	public function subscribe(Request $request)
	{
		$request->validate(['email' => 'required|email']);

		Subscriber::create(['email' => $request->email]);

		return back()->with('success_message', 'You have successfully subscribed to our news letter.');
	}

	public function search(Request $request)
	{
		$request->validate([
			'keyword' => 'required|string',
			'type'    => 'nullable|string|in:created_at,price',
			'order'   => 'nullable|string|in:asc,desc',
		]);

		$keyword = $request->query('keyword');

		$query = Product::with('images')
		                ->where(function ($query) use ($keyword) {
			                $query->where('title', 'like', "%{$keyword}%")
			                      ->orWhere('description', 'like', "%{$keyword}%");
		                });

		$categoryId    = $request->query('category');
		$categoryLevel = $request->query('level');
		if ($categoryId && $categoryLevel) {
			switch ($categoryLevel) {
				case 2:
					$query->where('sub_category_id', $categoryId);
					break;
				case 3:
					$query->where('sub_sub_category_id', $categoryId);
					break;
				default:
					$query->where('category_id', $categoryId);
			}
		}

		$type  = $request->query('type');
		$order = $request->query('order');
		if ($type && $order) {
			if ($type == 'price') {
				$orderRaw = 'CASE WHEN discount_type = 0 THEN (price-price*discount/100) ELSE (price-discount) END';
				$query->orderBy(DB::raw($orderRaw), $order);
			} else {
				$query->orderBy($type, $order);
			}
		}

		$this->website['routeType'] = 'search';
		$this->website['title']     = "Search results for '{$keyword}'";
		$this->website['products']  = $query->paginate(9)
		                                    ->appends(request()->except('page'));

		return view('products', $this->website);
	}

	public function catProducts($catSlug, $subCatSlug = null, $subSubCatSlug = null)
	{
		/** @var Category $category */
		$category       = $cat = Category::whereSlug($catSlug)->firstOrFail();
		$subCategory    = SubCategory::whereSlug($subCatSlug)->first();
		$subSubCategory = SubSubCategory::whereSlug($subSubCatSlug)->first();

		$products = [];
		if ( ! $subCatSlug && ! $subSubCatSlug) {
			$products = $category->products();
		}

		if ($subCatSlug && ! $subSubCatSlug) {
			$products = $subCategory->products();
			$cat      = $subCategory;
		}

		if ($subCatSlug && $subSubCatSlug) {
			$products = $subSubCategory->products()->whereSubCategoryId($subCategory->id);
			$cat      = $subSubCategory;
		}

		if (request()->query('priceRange')) {
			list($minRange, $maxRange) = explode(';', request()->query('priceRange'));
			$products->whereBetween('price', [$minRange, $maxRange]);

			$this->website['minRange'] = $minRange;
			$this->website['maxRange'] = $maxRange;
		}

		$type  = request()->query('type');
		$order = request()->query('order');
		if ($type && $order) {
			if ($type == 'price') {
				$orderRaw = 'CASE WHEN discount_type = 0 THEN (price-price*discount/100) ELSE (price-discount) END';
				$products->orderBy(DB::raw($orderRaw), $order);
			} else {
				$products->orderBy($type, $order);
			}
		}

		$this->website['title']       = $cat->name;
		$this->website['category']    = $category;
		$this->website['subCategory'] = optional($subCategory);
		$this->website['products']    = $a = $products->with('images')
		                                              ->paginate(9)
		                                              ->appends(request()->except('page'));
		$this->website['routeType']   = 'category.products';
		$this->website['currentUrl']  = url()->current();

		return view('products', $this->website);
	}

	public function signupForm()
	{
		return view('signup', $this->website);
	}

	public function signup(Request $request)
	{
		$request->validate([
			'first_name' => 'required|string',
			'last_name'  => 'required|string',
			'email'      => 'required|email',
			'password'   => 'required|confirmed',
			'address'    => 'required|string',
			'phone'      => 'required|string',
		]);

		$user              = new User();
		$user->name        = $request->input('first_name') . ' ' . $request->input('last_name');
		$user->email       = $request->input('email');
		$user->address     = $request->input('address');
		$user->phone       = $request->input('phone');
		$user->password    = bcrypt($request->input('password'));
		$user->email_token = str_random(11);
		$user->save();

		$user->roles()->syncWithoutDetaching([Role::whereName('normal')->first()->id]);

		Mail::to($request->email)->send(new VerifyEmail($user));

		return redirect()->route('home')->with('success_message', 'Registration successful. Please check your email to verify it.');
	}

	public function signin(Request $request)
	{
		$request->validate([
			'email'    => 'required|email',
			'password' => 'required',
		]);

		$user = User::where('email', $request->email)->first();
		if ( ! optional($user)->isVerified()) {
			return back()->with('failure_message', 'You must be a verified user to perform this action.');
		}

		$attemptLogin = Auth::attempt([
			'email'    => $request->input('email'),
			'password' => $request->input('password'),
		]);

		if ($attemptLogin) {
			return redirect()->route('home')->with('success_message', 'Login successful.');
		}

		return back()->with('failure_message', 'These credentials do not match our records.');
	}

	public function test()
	{
		return view('test', $this->website);
	}

	function changeLanguage($lang)
	{
		session(['locale' => $lang]);

		return redirect()->back();
	}

	public function privacyPolicy()
	{
		return view('privacy-policy', $this->website);
	}
}
