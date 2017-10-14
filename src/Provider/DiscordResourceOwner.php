<?php

namespace League\OAuth2\Client\Provider;

class DiscordResourceOwner implements ResourceOwnerInterface
{
    // protected $domain;

    protected $response;

    public function __construct(array $response = [])
    {
        error_log(print_r($response, true));

        $this->response = $response;
    }

    public function getId()
    {
        error_log(get_class().' :: '.__FUNCTION__);
    }

    public function toArray()
    {
        error_log(get_class().' :: '.__FUNCTION__);

        return $this->response;
    }
}
