<?php

namespace App\Http\Middleware;

use App;
use SettingHelper;
use Closure;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('locale')  ) {
            $locale = $request->session()->get('locale');
            App::setLocale($locale);
        } else {
            if(!empty(SettingHelper::siteLang()))
                App::setLocale(SettingHelper::siteLang());
        }
        return $next($request);
    }
}