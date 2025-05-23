<?php

namespace App\Http\Middleware;

use App;
use Request;
use Closure;

class Language
{
    public static function getLanguage()
    {
        $uri = Request::path();
        if(strpos($uri, 'inh/', 0) !== false || strpos($uri, '/inh/', 0) !== false || $uri === 'inh') {
            return 'inh';
        }
        return null;
    }

    public function handle($request, Closure $next)
    {
        $locale = self::getLanguage();
        if($locale) {
            App::setLocale($locale);
        } else {
            App::setLocale('ru');
        }
        return $next($request);
    }
}
