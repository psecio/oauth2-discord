<?php

namespace League\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\DiscordResourceOwner;
use League\OAuth2\Client\Provider\Exception\DiscordIdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use App\Email;

class Discord extends \League\OAuth2\Client\Provider\AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Base URL of the Discord API
     * @var string
     */
    protected $baseUrl = 'https://discordapp.com/api';
    //protected $baseUrl = 'https://discordapp.com';

    /**
     * Default scopes to be requested
     * @var array
     */
    protected $scopes = ['bot'];

    /**
     * Get the URL (with base) for the authorization endpoint
     *
     * @return string Full URL
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->baseUrl.'/oauth2/authorize';
    }

    /**
     * Get the URL (with base) for the token endpoint
     *
     * @param array $params Extra parameters
     * @return string Full URL
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->baseUrl.'/oauth2/token';
    }

    /**
     * Get the URL for fetching the resource owner (current user)
     *
     * @param AccessToken $token AccessToken instance
     * @return string Full URL
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->baseUrl.'/users/@me';
    }

    /**
     * Get the set of current scopes (in this case the default)
     *
     * @return string Scopes list separated by spaces
     */
    protected function getDefaultScopes()
    {
        return join(' ', $this->scopes);
    }

    /**
     * Check the response from the OAuth flow for errors
     *
     * @param ResponseInterface $response Response instance
     * @param array $data Extra data
     * @return null|DiscordIdentityProviderException Null if valid, exception on failure
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (isset($data['error'])) {
            throw DiscordIdentityProviderException::oauthException($response, $data);
        }
    }

    /**
     * Create a new instance of the DiscordResourceOwner based on the response
     *
     * @param array $response Response object
     * @param AccessToken $token Token object instance
     * @return DiscordResourceOwner instance
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new DiscordResourceOwner($response);
    }

    /**
     * Add additional scopes to fetch from the URL
     * See here for more information: https://discordapp.com/developers/docs/topics/oauth2#shared-resources
     *
     * @param array $scopes New scopes to add to the fetch
     */
    public function addScopes(array $scopes)
    {
        $this->scopes = array_merge($scopes, $this->scopes);
    }

    /**
     * Make the authorized request to the API using the token
     *
     * @param string $method HTTP method to use (GET/POST/etc)
     * @param string  $url URL for request
     * @param boolean $raw Return the raw response object or just the JSON result
     * @param  AccessToken $token  Token instance
     * @return Psr-7 Response object
     */
    public function request($method, $url, AccessToken $token, $raw = false)
    {
        // Check the expiration on the token, use the refresh if needed
        if ($token->getExpires() < time()) {
            // Refresh here - @todo
        }

        $url = $this->baseUrl.$url;
        $request = $this->getAuthenticatedRequest($method, $url, $token);

        $response = $this->getResponse($request);
        return ($raw == true) ? $response : json_decode($response->getBody());
    }
}
