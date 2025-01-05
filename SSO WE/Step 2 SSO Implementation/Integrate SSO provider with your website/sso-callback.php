<?php
require_once 'OktaSSOConfig.php';
require_once 'okta/okta-php-sdk/autoload.php';

use Okta\Client;

$ssoConfig = include 'OktaSSOConfig.php';

// Initialize Okta client
$client = new Client([
    'orgUrl' => 'https://your-okta-domain.com',
    'token' => 'your-okta-api-token'
]);

try {
    // Verify state matches
    if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['oauth_state']) {
        throw new Exception('Invalid state parameter');
    }

    // Exchange authorization code for tokens
    $authResponse = $client->getAuthenticationClient()->exchangeCodeForTokens([
        'code' => $_GET['code'],
        'redirect_uri' => $ssoConfig['redirectUri']
    ]);

    // Store tokens in session
    $_SESSION['access_token'] = $authResponse->getAccessToken();
    $_SESSION['id_token'] = $authResponse->getIdToken();

    // Redirect to application
    header('Location: application.php');
    exit;
} catch (Exception $e) {
    // Handle error
    die('SSO Error: ' . $e->getMessage());
}
