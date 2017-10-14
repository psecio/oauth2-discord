### Discord OAuth2 Provider

This is a Discord provider for use with the PHP League's [OAuth2 client](https://github.com/thephpleague/oauth2-client).

### Installation

To install the provider use Composer:

```
composer require psecio/oauth2-discord
```

### Usage

```
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
