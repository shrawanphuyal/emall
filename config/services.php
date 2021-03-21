<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, SparkPost and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => [
		'domain' => env('MAILGUN_DOMAIN'),
		'secret' => env('MAILGUN_SECRET'),
	],

	'ses' => [
		'key'    => env('SES_KEY'),
		'secret' => env('SES_SECRET'),
		'region' => 'us-east-1',
	],

	'sparkpost' => [
		'secret' => env('SPARKPOST_SECRET'),
	],

	'stripe' => [
		'model'  => App\User::class,
		'key'    => env('STRIPE_KEY'),
		'secret' => env('STRIPE_SECRET'),
	],

	'facebook' => [
		'client_id'     => env('FACEBOOK_ID'),
		'client_secret' => env('FACEBOOK_SECRET'),
		'redirect'      => env('FACEBOOK_REDIRECT_URL'),
		'android'       => [
			'app_id' => env('FACEBOOK_APP_ID_ANDROID'),
		],
		'ios'           => [
			'app_id' => env('FACEBOOK_APP_ID_IOS'),
		],
	],

	'google' => [
		'client_id'     => env('GOOGLE_ID'),
		'client_secret' => env('GOOGLE_SECRET'),
		'redirect'      => env('GOOGLE_REDIRECT_URL'),
		'android'       => [
			'client_id' => env('GOOGLE_CLIENT_ID_ANDROID'),
		],
		'ios'           => [
			'client_id' => env('GOOGLE_CLIENT_ID_IOS'),
		],
	],

	'khalti' => [
		'test_secret_key' => env('KHALTI_TEST_SECRET'),
		'live_secret_key' => env('KHALTI_LIVE_SECRET'),
		'merchant_idx'    => env('KHALTI_MERCHANT_IDX'),
	],

];
