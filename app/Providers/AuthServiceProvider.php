<?php

namespace App\Providers;

use App\Models\OauthToken;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('oauth-token', function (Request $request) {
            $oauth = OauthToken::where('expires_at', '>=', now())
                ->where('token', $request->token)
                ->where('provider', $request->provider)
                ->first();
            return $oauth ? User::find($oauth->user_id) : null;
        });
    }
}
