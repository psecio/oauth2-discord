<?php

namespace League\OAuth2\Client\Provider;
use League\OAuth2\Client\Provider\DiscordResourceOwner;

class Discord extends \League\OAuth2\Client\Provider\AbstractProvider
{
    protected $baseUrl = 'https://discordapp.com/api';
    protected $scopes = ['identity'];

    public function getBaseAuthorizationUrl()
    {
        return $this->baseUrl.'/oauth2/authorize';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->baseUrl.'/oauth2/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        $url = $this->baseUrl.'/users/@me';
    }

    protected function getDefaultScopes()
    {
        return $this->scopes;
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        error_log(print_r($data, true));
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new DiscordResourceOwner($response);
    }
}
