<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class MicrosoftGraph
{
    public function authenticate()
    {
        $url = 'https://login.microsoftonline.com/'.config('services.azure.tenant').'/oauth2/v2.0/token';

        $accessToken = json_decode(Http::asForm()->post($url, [
            'client_id' => config('services.azure.client_id'),
            'client_secret' => config('services.azure.client_secret'),
            'scope' => 'https://graph.microsoft.com/.default',
            'grant_type' => 'client_credentials',
        ])->body())->access_token;

        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        return $graph;
    }

    public function getUser()
    {
        $graph = $this->authenticate();

        $user = $graph
            ->createRequest('GET', '/users')
            ->setReturnType(Model\User::class)
            ->execute();
        dd($user);
    }
}
