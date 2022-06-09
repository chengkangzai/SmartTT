<?php

namespace App\Http\Services;

use App\Models\User;
use League\OAuth2\Client\Provider\GenericProvider;
use Microsoft\Graph\Graph as MicrosoftGraph;

class MicrosoftGraphService
{
    private TokenService $tokenService;

    public function __construct()
    {
        $this->tokenService = new TokenService();
    }

    public function getOAuthClient(): GenericProvider
    {
        return new GenericProvider([
            'clientId' => config('azure.appId'),
            'clientSecret' => config('azure.appSecret'),
            'redirectUri' => config('azure.redirectUri'),
            'urlAuthorize' => config('azure.authority') . config('azure.authorizeEndpoint'),
            'urlAccessToken' => config('azure.authority') . config('azure.tokenEndpoint'),
            'urlResourceOwnerDetails' => '',
            'scopes' => config('azure.scopes'),
        ]);
    }

    public function getGraph(User $user): MicrosoftGraph
    {
        $accessToken = $this->tokenService->getAccessToken($user);

        // Create a Graph client
        $graph = new MicrosoftGraph();
        $graph->setAccessToken($accessToken);

        return $graph;
    }
}
