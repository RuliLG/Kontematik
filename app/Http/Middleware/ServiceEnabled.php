<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceEnabled
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
        $service = $request->route('service');
        $user = Auth::user();
        if ($service->is_enabled || $user && $user->is_admin) {
            return $next($request);
        }

        return redirect(route('dashboard'));
    }
}
