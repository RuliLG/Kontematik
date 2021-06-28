<?php

namespace App\Http\Controllers;

use App\Integrations\IntegrationFactory;
use App\Models\OauthToken;
use App\Services\Integrations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IntegrationsController extends Controller
{
    public function index()
    {
        $integrations = (new Integrations())->active();
        return response()->json([
            'integrations' => $integrations,
        ]);
    }

    public function renew()
    {
        $tokens = OauthToken::where('expires_at', '<=', now()->addHour())
            ->where('expires_at', '>=', now())
            ->where('should_renew', true)
            ->get();

        foreach ($tokens as $token) {
            try {
                $integration = IntegrationFactory::from($token->provider)->with($token);
                $integration->renew();
            } catch (\Exception $e) {
                Log::error($e);
            }
        }
    }
}
