<?php

namespace Psecio\DiscordPHP;

abstract class Entity
{
    protected $properties = [];

    public function __construct(array $properties = [])
    {
        if (!empty($properties)) {
            $this->load($properties);
        }
    }

    public function load(array $properties = [])
    {
        $this->properties = $properties;
    }

    public function __get($name)
    {
        return (array_key_exists($name, $this->properties))
            ? $this->properties[$name] : null;
    }
}
