<?php

namespace Psecio\DiscordPHP\Entity;

class Guild extends \Modler\Model
{
    protected $properties = [
        'id' => [
            'description' => 'ID of Guild',
            'type' => 'integer'
        ],
        'icon' => [
            'description' => 'Icon for Guild',
            'type' => 'string'
        ],
        'owner' => [
            'description' => 'Owner of the Guild',
            'type' => 'string'
        ],
        'permissions' => [
            'description' => 'Current Guild permissions',
            'type' => 'integer'
        ],
        'name' => [
            'description' => 'Guild name',
            'type' => 'string'
        ]
    ];
}
