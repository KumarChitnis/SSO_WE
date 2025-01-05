<?php
require_once 'okta/okta-php-sdk/autoload.php';

use Okta\Client;

// Initialize Okta client
$client = new Client([
    'orgUrl' => 'https://your-okta-domain.com',
    'token' => 'your-okta-api-token'
]);

$authClient = $client->getAuthenticationClient();

// Configure authentication settings
$authClient->setAuthenticationSettings([
    'username' => 'email',
    'password' => 'phone_number',
    'factorTypes' => ['email', 'phone']
]);

// Handle SSO authentication
if (isset($_SESSION['access_token'])) {
    try {
        // Verify SSO token
        $authResponse = $authClient->verifyToken($_SESSION['access_token']);
        
        if ($authResponse->isValid()) {
            // User is authenticated, redirect to application
            $authClient->redirectUserToApplication($authResponse->getSessionToken());
            exit;
        }
    } catch (Exception $e) {
        // Handle error
        $error = 'SSO Error: '. $e->getMessage();
    }
}

// Handle traditional login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $authResponse = $authClient->authenticate([
            'username' => $username,
            'password' => $password,
            'options' => [
                'ultiOptionalFactorEnroll' => true,
                'warnBeforePasswordExpired' => true
            ]
        ]);

        if ($authResponse->getStatus() === 'SUCCESS') {
            // User is authenticated, redirect to application
            $authClient->redirectUserToApplication($authResponse->getSessionToken());
            exit;
        } else {
            // Authentication failed, display error message
            $error = 'Invalid username or password';
        }
    } catch (Exception $e) {
        // Handle error
        $error = 'Error: '. $e->getMessage();
    }
}
