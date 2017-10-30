<?php

namespace Psecio\DiscordPHP\Entity;

class User extends \Modler\Model
{
    protected $properties = [
        'id' => [
            'description' => 'ID of User',
            'type' => 'integer'
        ],
        'username' => [
            'description' => 'Username',
            'type' => 'string'
        ],
        'discriminator' => [
            'description' => 'Discriminator',
            'type' => 'string'
        ],
        'avatar' => [
            'description' => 'Avatar ID',
            'type' => 'string'
        ],
        'verified' => [
            'description' => 'Verified state',
            'type' => 'string'
        ],
        'email' => [
            'description' => 'Email address',
            'type' => 'string'
        ]
    ];
}
