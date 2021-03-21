<?php

Auth::routes();

Route::prefix('admin')->group(function () {

	// admin routes that are all authenticated with authorization
	Route::middleware(['auth', 'authorized_users'])->group(function () {
		Route::get('/', 'AdminController@index')->name('admin_home');
		Route::get('/change-conversion-rate', 'AdminController@changeConversionRate')->name('change-rate');
		Route::get('change-password', 'UserController@change_password_form')->name('user.password.change');
		Route::post('change-password', 'UserController@change_password')->name('user.password.change.store');
		Route::get('my-profile', 'UserController@profile')->name('user.profile');
		Route::resource('user', 'UserController', ['only' => ['edit', 'update']]);
		Route::resource('order', 'OrderController', ['only' => ['index']]);

		// routes only admin can access
		Route::group(['middleware' => 'role', 'roles' => ['admin']], function () {
			Route::resource('company', 'CompanyController');
			Route::resource('user', 'UserController', ['except' => ['edit', 'update']]);
			Route::resource('category', 'CategoryController');
			Route::resource('news', 'NewsController');
			Route::resource('sub-category', 'SubCategoryController');
			Route::resource('sub-sub-category', 'SubSubCategoryController');
			Route::get('subscribers', 'AdminController@subscribers')->name('admin.subscriber');
			Route::resource('advertisement', 'AdvertisementController');
			Route::resource('notification', 'NotificationController');
			Route::resource('test', 'TestController');

			Route::get('vendor/all', 'VendorController@index')->name('vendor.index');
			Route::get('vendor/{vendor}/products', 'VendorController@show')->name('vendor.show');
			Route::post('vendor/assign-category', 'VendorController@assign_category')->name('vendor.assign-category');
			Route::resource('vendor-category', 'VendorCategoryController');

			Route::get('product/all', 'AdminProductController@all')->name('product.all');
			Route::post('product/assign-profit-percentage', 'AdminProductController@assign_profit_percentage')->name('product.assign-profit-percentage');

			Route::resource('slider', 'SliderController');
			Route::resource('brand', 'BrandController');

			Route::get('order/{order}/change-status', 'OrderController@changeStatus')->name('order.change-status');
			Route::get('order/{order}/products', 'OrderController@getProducts')->name('order.get-products');
		});

		// routes only vendors can access
		Route::group(['middleware' => 'role', 'roles' => ['vendor']], function () {
			Route::post('product/assign-discount-percentage', 'ProductController@assign_discount_percentage')->name('product.assign-discount-percentage');
			Route::resource('product', 'ProductController');

			Route::get('discount/by-category', 'DiscountController@by_category')->name('discount.by-category');
			Route::post('discount/by-category/{category}', 'DiscountController@by_category_post')->name('discount.by-category.post');
			Route::get('discount/by-sub-category', 'DiscountController@by_sub_category')->name('discount.by-sub-category');
			Route::post('discount/by-sub-category/{sub_category}', 'DiscountController@by_sub_category_post')->name('discount.by-sub-category.post');
			//Route::get('');
		});

		// admin ajax
		Route::prefix('ajax')->group(function () {
			Route::get('show-or-hide-sub-category', 'CategoryController@show_or_hide_sub_category')->name('ajax.sub-category.show-hide');
			Route::get('category/show-on-menu/{category}', 'CategoryController@show_on_menu')->name('category.show-on-menu');
			Route::get('category/make-exclusive/{category}', 'CategoryController@make_exclusive')->name('category.make-exclusive');
			Route::get('category/set-priority/{category}', 'CategoryController@set_priority')->name('category.set-priority');
			Route::get('product/make-featured/{product}', 'ProductController@make_featured')->name('product.make-featured');
			Route::get('product/make-hot/{product}', 'ProductController@make_hot')->name('product.make-hot');
		});
	});
});
Route::prefix('/')->middleware('locale')->group(function () {
	Route::get('privacy-policy', 'WebsiteController@privacyPolicy')->name('privacy-policy');
	Route::get('register/verify/{token}', 'Auth\RegisterController@verify')->name('verify');
	Route::get('change-language/{lang}', 'WebsiteController@changeLanguage')->name('changeLanguage');
	Route::get('test', 'WebsiteController@test')->name('test');


	Route::get('search', 'WebsiteController@search')->name('search');
	Route::namespace('Frontend')->group(function () {
		Route::middleware('frontAuth')->group(function () {
			Route::post('process-payment', 'PaymentController@process')->name('payment.process');
			Route::post('review/{productSlug}', 'ReviewController@store')->name('review.store');
			Route::get('checkout', 'CartController@checkout')->name('checkout');
		});

		Route::get('cart', 'CartController@cartItems')->name('cart-items');
		Route::get('add-to-cart/{slug}', 'CartController@addToCart')->name('add-to-cart');
		Route::get('remove-from-cart/{slug}', 'CartController@removeFromCart')->name('remove-from-cart');
	});

	Route::get('category/{catSlug}/{subCatSlug?}/{subSubCatSlug?}', 'WebsiteController@catProducts')->name('category.products');
	Route::post('subscribe', 'WebsiteController@subscribe')->name('subscribe');
	Route::get('all-news', 'WebsiteController@allNews')->name('news.all');
	Route::get('news/{slug}', 'WebsiteController@newsDetail')->name('news.detail');
	Route::get('product/{slug}', 'WebsiteController@productDetail')->name('product.detail');
	Route::post('signin', 'WebsiteController@signin')->name('signin');
	Route::get('signup', 'WebsiteController@signupForm')->name('signup.form');
	Route::post('signup', 'WebsiteController@signup')->name('signup');
	Route::get('/', 'WebsiteController@index')->name('home');
});

Route::get('get-asset/{path1?}/{path2?}/{path3?}/{path4?}/{path5?}/{path6?}', function ($path1 = null, $path2 = null, $path3 = null, $path4 = null, $path5 = null, $path6 = null) {
	$path = public_path("frontend/{$path1}/{$path2}/{$path3}/{$path4}/{$path5}/{$path6}");

	return response()->download(rtrim($path, '/'), null, [], null)->setPublic()->setMaxAge(3600);

})->name('get-asset');

// social login starts
Route::get('login/facebook', 'SocialLoginController@redirectToFacebook')->name('facebookLogin');
Route::get('login/facebook/callback', 'SocialLoginController@getFacebookCallback');
Route::get('login/google', 'SocialLoginController@redirectToGoogle')->name('googleLogin');
Route::get('login/google/callback', 'SocialLoginController@getGoogleCallback');
Route::get('a-logout', 'SocialLoginController@logout')->name('sLogout');
// social login ends
