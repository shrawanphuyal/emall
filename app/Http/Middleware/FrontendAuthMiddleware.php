<?php

namespace App\Http\Middleware;

use Closure;

class FrontendAuthMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (auth()->check() && auth()->user()->isVerified()) {
			return $next($request);
		}

		if ( ! auth()->check()) {
			return redirect()->back()->with('failure_message', 'You must be logged in to perform this action.');
		}

		return redirect()->back()->with('failure_message', 'You must be a verified user to perform this action.');
	}
}
