<?php

namespace App\Integrations;

use App\Models\OauthToken;
use Illuminate\Http\Request;
use JsonSerializable;
use Symfony\Polyfill\Intl\Icu\Exception\MethodNotImplementedException;

abstract class Integration implements JsonSerializable {
    protected $token = null;
    protected $details = null;

    public function setToken(OauthToken $token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function with(OauthToken $token)
    {
        $this->setToken($token);
        return $this;
    }

    public function id()
    {
        throw new MethodNotImplementedException('id');
    }

    public function name()
    {
        throw new MethodNotImplementedException('name');
    }

    public function image()
    {
        throw new MethodNotImplementedException('image');
    }

    public function description()
    {
        throw new MethodNotImplementedException('description');
    }

    public function isAvailable()
    {
        throw new MethodNotImplementedException('isAvailable');
    }

    public function details()
    {
        if (!$this->token) {
            return null;
        }

        throw new MethodNotImplementedException('details');
    }

    public function renew ()
    {
        throw new MethodNotImplementedException('renew');
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'description' => $this->description(),
            'image' => $this->image(),
            'account_details' => $this->details(),
            'token' => $this->token,
        ];
    }

    public function link()
    {
        throw new MethodNotImplementedException('link');
    }

    public function save(Request $request)
    {
        throw new MethodNotImplementedException('link');
    }

    public function saveIfNotRepeated(OauthToken $token)
    {
        $tokens = OauthToken::where('user_id', $token->user_id)
            ->where('provider', $token->provider)
            ->where('expires_at', '>=', now())
            ->get();

        $integration = IntegrationFactory::from($token->provider)->with($token);
        $user = $integration->details()->getUser();
        $site = $integration->details()->getSiteId();

        $isSameUser = true;
        $isDifferentSite = true;
        foreach ($tokens as $t) {
            $integration = IntegrationFactory::from($t->provider)->with($token);
            $existingUser = $integration->details()->getUser();
            $existingSite = $integration->details()->getSiteId();
            if ($existingUser !== $user) {
                $isSameUser = false;
                break;
            }

            if ($existingSite === $site) {
                $isDifferentSite = false;
                break;
            }
        }

        if (!$isSameUser) {
            session()->flash('oauth_error', 'You can only connect Kontematik to one user account');
        } else if (!$isDifferentSite) {
            session()->flash('oauth_error', 'You have already given permissions to access this site');
        }

        if ($isSameUser && $isDifferentSite) {
            $token->save();
        }
    }

    protected function storeRenewedToken ($accessToken, $refreshToken, $expiresAt)
    {
        $this->token->expires_at = now();
        $this->token->should_renew = false;
        $this->token->save();

        $oauth = new OauthToken();
        $oauth->user_id = $this->token->user_id;
        $oauth->provider = $this->token->provider;
        $oauth->token = $accessToken;
        $oauth->refresh_token = $refreshToken;
        $oauth->expires_at = $expiresAt;
        $oauth->save();

        $this->setToken($oauth);
    }
}
