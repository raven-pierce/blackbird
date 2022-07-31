<?php

namespace App\Services;

use Microsoft\Graph\GraphRequestAdapter;
use Microsoft\Graph\GraphServiceClient;
use Microsoft\Kiota\Authentication\Oauth\ClientCredentialContext;
use Microsoft\Kiota\Authentication\PhpLeagueAuthenticationProvider;

class MicrosoftGraph
{
    public function authenticate()
    {
        $tokenRequestContext = new ClientCredentialContext(
            config('services.azure.tenant'),
            config('services.azure.client_id'),
            config('services.azure.client_secret')
        );

        $scopes = ['https://graph.microsoft.com/.default'];

        $authProvider = new PhpLeagueAuthenticationProvider($tokenRequestContext, $scopes);
        $requestAdapter = new GraphRequestAdapter($authProvider);

        return new GraphServiceClient($requestAdapter);
    }

    public function getAllGroups()
    {
        $graph = $this->authenticate();

        return $graph->groups()->get();
    }
}
