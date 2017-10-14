<?php

namespace League\OAuth2\Client\Provider;

class DiscordResourceOwner implements ResourceOwnerInterface
{
    /**
     * Response information
     * @var array
     */
    protected $response = [];

    /**
     * Init the object with the response information
     *
     * @param array $response Response data
     */
    public function __construct(array $response = [])
    {
        $this->response = $response;
    }

    /**
     * Magic method allowing the fetch of any properties from the object
     *
     * @param string $name Method name to call (get*)
     * @param array $args Arguments
     * @return mixed|InvalidArgumentException Value if the property is found, otherwise an exception
     */
    public function __call($name, $args)
    {
        $name = str_replace('get', '', strtolower($name));
        if (!isset($this->response[$name])) {
            throw new \InvalidArgumentException('Invalid property: '.$name);
        }
        return $this->response[$name];
    }

    /**
     * Get the ID of the current user
     *
     * @return integer User ID
     */
    public function getId()
    {
        return $this->response['id'];
    }

    /**
     * Return an array of all current properties
     *
     * @return array Set of properties
     */
    public function toArray()
    {
        return $this->response;
    }
}
