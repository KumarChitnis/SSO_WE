<?php
require_once 'okta/okta-php-sdk/autoload.php';

use Okta\Client;

$client = new Client([
    'orgUrl' => 'https://your-okta-domain.com',
    'token' => 'your-okta-token'
]);

$authClient = $client->getAuthenticationClient();

$authorizationUrl = $authClient->getAuthorizationUrl([
    'copes' => ['openid', 'profile', 'email'],
    'edirectUri' => 'https://your-app.com/callback',
    'tate' => 'your-state'
]);

header('Location: '. $authorizationUrl);
exit;