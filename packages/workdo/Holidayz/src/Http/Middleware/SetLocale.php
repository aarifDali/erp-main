<?php

namespace Workdo\Holidayz\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $lang = (session()->get('lang')) ? session()->get('lang') : 'en';

        if(auth()->guard('holiday')->user()){
            $lang = (auth()->guard('holiday')->user()->lang) ? auth()->guard('holiday')->user()->lang : 'en';
        }

        App::setLocale($lang);

        return $next($request);
    }
}
