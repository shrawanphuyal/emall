<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class LocaleMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Closure $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if(session()->has('locale')) {
      App::setLocale(session('locale'));
    } else {
      App::setLocale('en');
    }
    return $next($request);
  }
}
