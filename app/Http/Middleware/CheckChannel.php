<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CheckChannel
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth('delegate')->check()) {
            if (auth('delegate')->user()->channel != LaravelLocalization::getCurrentLocale()) {
                $locale = auth('delegate')->user()->channel;
                $langs = array('en', 'zh');
                $requestUrl = str_replace($langs, $locale, $request->getRequestUri());

                return redirect($requestUrl);
            }
        }
        return $next($request);
    }
}
