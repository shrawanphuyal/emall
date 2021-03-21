<?php

/**
 * Registration and authentication routes
 * */
Route::namespace('Api')->prefix('auth')->group(function () {
	Route::post('register', 'RegisterController@register');
	Route::post('login', 'LoginController@login');

	Route::middleware('apiAuth')->group(function () {
		Route::post('logout', 'LoginController@logout');
		Route::post('refresh-access-token', 'LoginController@refreshAccessToken');
	});
});

Route::namespace('Api')->group(function () {
	Route::get('get-sub-sub-categories/{sub_category?}', 'CategoryController@getSubSubCategories')->name('api.get-sub-sub-categories');
	Route::get('slider', 'SliderController@index');
	Route::get('latest-products', 'ProductController@latest');
	Route::get('products', 'ProductController@products');
	Route::get('product/{product}', 'ProductController@detail');
	Route::get('news', 'NewsController@index');
	Route::get('categories', 'CategoryController@index');
	Route::get('category-products', 'CategoryController@products');
	Route::get('sub-categories/{category}', 'CategoryController@subCategories');

	Route::middleware('apiAuth')->group(function () {
		Route::post('save-firebase-instance-token', 'NotificationController@saveInstanceToken');
		Route::get('make-favourite/{productId}', 'ProductController@makeFavourite');
		Route::get('remove-from-favourite/{productId}', 'ProductController@removeFromFavourite');
		Route::get('favourite-products', 'ProductController@favourite');

		Route::post('process-payment', 'PaymentController@process');
		Route::post('checkout', 'CartController@checkout');
		Route::post('checkout-ios', 'CartController@checkoutIos');
		Route::get('my-orders', 'CartController@myOrders');
		Route::post('product/{product}/rate', 'ReviewController@rate');
	});
});