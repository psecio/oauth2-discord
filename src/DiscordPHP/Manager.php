<?php

namespace Psecio\DiscordPHP;
use League\OAuth2\Client\Token\AccessToken;

use \Psecio\DiscordPHP\Entity\Guild;
use \Psecio\DiscordPHP\Collection\Guilds;
use App\Email;


class Manager
{
    protected $accessToken;
    protected $provider;

    /**
     * Create a new Manager instance and init the token/provider
     *
     * @param $token    [description]
     * @param [type] $provider [description]
     */
    public function __construct($token, $provider)
    {
        $this->token = $token;
        $this->provider = $provider;
    }

    /**
     * Static method for setting up an instance
     *
     * @param object $connector Connector class implements the required interface
     * @return \Psecio\DiscordPHP\Manager instance
     */
    public static function getInstance($connector, array $addl = [])
    {
        $token = new AccessToken((array)$connector->getConfig()->token);
        $provider = $connector->getProvider();

        return new self($token, $provider);
    }

    /**
     * Get the current access token
     *
     * @return League\OAuth2\Client\Token\AccessToken instance
     */
    public function getToken()
    {
        return $this->accessToken;
    }

    /**
     * Magic method to handle the "get" fetches by property
     *
     * @param string $name Property name
     * @return mixed Result of the "get*" method call
     */
    public function __get($name)
    {
        $method = 'get'.ucwords($name);
        if (!method_exists($this, $method)) {
            throw new \InvalidArgumentException('Invalid property: '.$name);
        }
        return $this->$method();
    }

    /**
     * Make the request to the API
     *
     * @param string $url URL to request
     * @param string  $method HTTP method to use [defailt: GET]
     * @param boolean $raw Return the raw response or the formatted result
     * @throws \Psecio\DiscordPHP\Exception\RateLimit if rate limited
     * @return mixed Response information or false
     */
    public function request($url, $method = 'GET', $raw = false)
    {
        try {
            $result = $this->provider->request($method, $url, $this->token, $raw);
            return $result;
        } catch (\Exception $e) {
            $headers = $e->getResponse()->getHeaders();
            switch ($e->getCode()) {
                // Rate limiting
                case 429:
                    $message = json_encode([
                        'retry' => $headers['Retry-After'][0],
                        'reset' => $headers['X-RateLimit-Reset'][0],
                        'remaining' => $headers['X-RateLimit-Remaining'][0],
                        'message' => (string)$e->getResponse()->getBody()
                    ]);
                    throw new \Psecio\DiscordPHP\Exception\RateLimit($message);
                    break;
                case 401:
                    $body = (string)$e->getResponse()->getBody();
                    throw new \Psecio\DiscordPHP\Exception\NotAllowed($body);
                    break;
            }
        }
        return false;
    }

    /**
     * Get the current user's guilds list
     *
     * @return \Psecio\DiscordPHP\Collection\Guilds collection
     */
    public function getGuilds()
    {
        $result = $this->request('/users/@me/guilds');
        $guilds = new Guilds();

        foreach ($result as $guild) {
            $guilds->add(new Guild((array)$guild));
        }

        return $guilds;
    }

    /**
     * Get the information about the current user
     *
     * @return \Psecio\DiscordPHP\Collection\User instance
     */
    public function getUser()
    {
        $result = $this->request('/users/@me');
        return new \Psecio\DiscordPHP\Entity\User((array)$result);
    }
}
