<?php
return [
    'clientId' => 'YOUR_OKTA_CLIENT_ID',
    'clientSecret' => 'YOUR_OKTA_CLIENT_SECRET',
    'redirectUri' => 'https://yourdomain.com/sso-callback.php',
    'metadataUrl' => 'https://YOUR_OKTA_DOMAIN/.well-known/openid-configuration',
    'scopes' => ['openid', 'profile', 'email'],
    'authServerId' => 'default'
];
