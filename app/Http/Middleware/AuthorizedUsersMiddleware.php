<?php

namespace App\Http\Middleware;

use Closure;
use \Illuminate\Support\Facades\Auth;

class AuthorizedUsersMiddleware {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('vendor')) {
			return $next($request);
		}

		return redirect()->route('home')->with('success_message', 'You are logged in.');
	}
}
