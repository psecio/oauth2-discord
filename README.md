### Discord OAuth2 Provider

This is a Discord provider for use with the PHP League's [OAuth2 client](https://github.com/thephpleague/oauth2-client).

### Installation

To install the provider use Composer:

```
composer require psecio/oauth2-discord
```

### Usage: OAuth Connection

The code below is an example of how to use the library to make an OAuth connection to the Discord
service, authorizing it for your account. By default it will request these scopes:

- `identity`
- `email`
- `messages.read`
- `guilds`

See [this documentation](https://discordapp.com/developers/docs/topics/oauth2) for more about scopes.

```php
<?php

$provider = new \League\OAuth2\Client\Provider\Discord([
    'clientId'          => 'client-id',
    'clientSecret'      => 'client-secret',
    'redirectUri'       => 'http://yoursite.com/callback'
]);

if (!isset($_GET['code'])) {
    // If we don't have a code yet, we need to make the link
    echo '<a href="'.$provider->getAuthorizationUrl().'">Link to Discord</a>';

} else {
    // If we do have a code, use it to get a token
    $accessToken = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    $user = $provider->getResourceOwner($accessToken);

    /**
     * User object contains:
     * - username
     * - verified
     * - mfa_enabled
     * - id
     * - avatar
     */
}

?>
```

### Usage: Getting Data

This library can also be used to get information from the API once an authenticated request has been made. This process requires a class that implements the `Psecio\DiscordPHP\ConnectorInterface` and defines a `getToken()` method. This method should return the current OAuth2 token and can then be used with the `Manager` class. For example:

```php
<?php
class MyConnector implements \Psecio\DiscordPHP\ConnectorInterface
{
    public function getToken()
    {
        return 'fdagahdar4324fdgfdsgfss';
    }
}

$connector = new MyConnector();
$manager = \Psecio\DiscordPHP\Manager::getInstance($connector);

?>
```

If all goes well (and the token isn't expired or anything odd like that) you should now be able to get information from the API using helper methods. For example, to get all of the guilds for the currently linked user, you can just use the `guilds` property:

```php
<?php
$guilds = $manager->guilds;
foreach ($guilds as $guild) {
    echo $guild->name;
}

?>
```

#### Supported properties

- `guilds`: list of current user's guilds
- `user`: information about the current user
