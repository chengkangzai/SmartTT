<?php

namespace App\Http\Services;

use App\Models\User;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class TokenService
{
    public function storeTokens($accessToken, $msUser, User $user): void
    {
        $user->msOauth()->create([
            'accessToken' => $accessToken->getToken(),
            'refreshToken' => $accessToken->getRefreshToken(),
            'tokenExpires' => $accessToken->getExpires(),
            'userName' => $msUser->getDisplayName(),
            'userEmail' => $msUser->getMail() !== null ? $msUser->getMail() : $msUser->getUserPrincipalName(),
            'userTimeZone' => $msUser->getMailboxSettings()->getTimeZone(),
        ]);
    }

    public function clearTokens(User $user): void
    {
        $user->msOauth()->delete();
    }

    public function getAccessToken(User $user)
    {
        $token = $user->msOauth()->first();

        if (! $token) {
            return '';
        }

        if (! $token->expired()) {
            return $token->accessToken;
        }

        $oauthClient = app(MicrosoftGraphService::class)->getOAuthClient();

        try {
            $newToken = $oauthClient->getAccessToken('refresh_token', [
                'refresh_token' => $token->refreshToken,
            ]);

            $this->updateTokens($newToken, $user);

            return $newToken->getToken();
        } catch (IdentityProviderException $e) {
            return '';
        }
    }

    public function updateTokens($accessToken, User $user): void
    {
        $user->msOauth()->update([
            'accessToken' => $accessToken->getToken(),
            'refreshToken' => $accessToken->getRefreshToken(),
            'tokenExpires' => $accessToken->getExpires(),
        ]);
    }
}
