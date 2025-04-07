<?php

namespace App\Http\Controllers;

use App\Http\Services\MicrosoftGraphService;
use App\Http\Services\TokenService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Microsoft\Graph\Exception\GraphException;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model\User;

class MSOauthController extends Controller
{
    private MicrosoftGraphService $MSGraphService;

    private TokenService $tokenService;

    public function __construct()
    {
        $this->MSGraphService = new MicrosoftGraphService;
        $this->tokenService = new TokenService;
    }

    public function signin(): RedirectResponse
    {
        $oauthClient = $this->MSGraphService->getOAuthClient();
        $authUrl = $oauthClient->getAuthorizationUrl();
        Session::put(['oauthState' => $oauthClient->getState()]);

        return redirect()->away($authUrl);
    }

    public function callback(Request $request): Redirector|RedirectResponse|Application
    {
        $expectedState = Session::get('oauthState');
        $request->session()->forget('oauthState');
        $providedState = $request->query('state');

        if (! isset($expectedState)) {
            return redirect(route('profile.show'));
        }

        if (! isset($providedState) || $expectedState != $providedState) {
            return redirect(route('profile.show'))
                ->with('error', 'Invalid auth state')
                ->with('errorDetail', 'The provided auth state did not match the expected value');
        }

        $authCode = $request->query('code');
        if (isset($authCode)) {
            $oauthClient = $this->MSGraphService->getOAuthClient();

            try {
                $accessToken = $oauthClient->getAccessToken('authorization_code', [
                    'code' => $authCode,
                ]);
                $user = (new Graph)
                    ->setAccessToken($accessToken->getToken())
                    ->createRequest('GET', '/me?$select=displayName,mail,mailboxSettings,userPrincipalName')
                    ->setReturnType(User::class)
                    ->execute();

                $this->tokenService->storeTokens($accessToken, $user, auth()->user());
            } catch (IdentityProviderException $e) {
                return redirect(route('profile.show'))
                    ->with('error', 'Error requesting access token')
                    ->with('errorDetail', json_encode($e->getResponseBody()));
            } catch (GraphException|GuzzleException $e) {
                return redirect(route('profile.show'))
                    ->with('error', 'Error requesting access token')
                    ->with('errorDetail', $e->getMessage());
            }
        }

        return redirect(route('profile.show'))
            ->with('error', $request->query('error'))
            ->with('errorDetail', $request->query('error_description'));
    }

    public function disconnect(): Redirector|Application|RedirectResponse
    {
        auth()->user()->msOauth()->delete();
        $this->tokenService->clearTokens(auth()->user());

        return to_route('profile.show');
    }
}
