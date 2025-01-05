<?php
require_once 'okta/okta-php-sdk/autoload.php';

use Okta\Client;

$client = new Client([
    'orgUrl' => 'https://your-okta-domain.com',
    'token' => 'your-okta-api-token'
]);