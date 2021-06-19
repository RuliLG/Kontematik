<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthToken
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
        if ($request->get('auth_token')) {
            $user = User::where('api_token', $request->auth_token)->first();
            if ($user) {
                Auth::login($user);

                $user->api_token = Str::random(80);
                $user->save();
            }
        }

        return $next($request);
    }
}
