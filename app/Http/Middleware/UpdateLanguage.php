<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UpdateLanguage
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
        $accepted = ['en', 'es'];
        if ($request->get('hl') && in_array($request->get('hl'), $accepted)) {
            $request->session()->put('hl', $request->get('hl'));
        } else if (auth()->check() && auth()->user()->preferred_language) {
            $request->session()->put('hl', auth()->user()->preferred_language);
        }

        if (!$request->session()->get('hl')) {
            $request->session()->put('hl', 'en');
        }

        $lang = $request->session()->get('hl');
        app()->setLocale($lang);

        return $next($request);
    }
}
