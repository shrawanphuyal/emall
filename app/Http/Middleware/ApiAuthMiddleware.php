<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class ApiAuthMiddleware {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		$payload = auth()->guard('api')->payload();

		return $next($request);
	}
}
