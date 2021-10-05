<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Config;
use Session;
use Illuminate\Http\Request;

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
        if(Session::has('locale')) {
            $locale = Session::get('locale', Config::get('app.locale'));
        } else {
            $locale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            if($locale !== 'en' && $locale !== 'ja') {
                $locale = 'en';
            }
        }

        App::setLocale($locale);

        return $next($request);
    }
}
