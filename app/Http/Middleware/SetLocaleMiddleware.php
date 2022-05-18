<?php


namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Session;

class SetLocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        $language = Session::get('language', config('app.locale'));
        if (!in_array($language, ['vi','en'])) $language = config('app.locale');
        \App::setLocale($language);
        return $next($request);
    }
}