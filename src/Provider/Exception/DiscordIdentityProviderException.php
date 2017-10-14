<?php

namespace League\OAuth2\Client\Provider\Exception;
use Psr\Http\Message\ResponseInterface;

class DiscordIdentityProviderException extends IdentityProviderException
{
    /**
     * Throw an OAuth-related exception
     *
     * @param ResponseInterface $response Response object
     * @param array $data Extra response data
     * @return DiscordIdentityProviderException instance
     */
    public static function oauthException(ResponseInterface $response, array $data = [])
    {
        $message = $response->getReasonPhrase();
        $code = $response->getStatusCode();
        $body = (string) $response->getBody();

        return new static($message, $code, $body);
    }
}
