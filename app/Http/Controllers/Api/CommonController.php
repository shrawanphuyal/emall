<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommonController extends Controller
{
	public function __construct()
	{
		ini_set('memory_limit', - 1);
	}
}
