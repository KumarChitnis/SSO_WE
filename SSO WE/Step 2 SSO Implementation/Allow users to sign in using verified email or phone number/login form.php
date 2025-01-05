<?php
require_once '../Integrate SSO provider with your website/AuthenticationClient.php';

// Login form
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Authenticate user using Okta's API
        $authResponse = $client->getAuthenticationClient()->authenticate([
            'username' => $username,
            'password' => $password,
            'options' => [
                'ultiOptionalFactorEnroll' => true,
                'warnBeforePasswordExpired' => true
            ]
        ]);

        if ($authResponse->getStatus() === 'SUCCESS') {
            // User is authenticated, redirect to application
            header('Location: application.php');
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

$ssoConfig = include '../Integrate SSO provider with your website/OktaSSOConfig.php';

// Generate SSO login URL
$ssoLoginUrl = "https://{$ssoConfig['metadataUrl']}/v1/authorize?" . http_build_query([
    'client_id' => $ssoConfig['clientId'],
    'response_type' => 'code',
    'scope' => implode(' ', $ssoConfig['scopes']),
    'redirect_uri' => $ssoConfig['redirectUri'],
    'state' => bin2hex(random_bytes(16))
]);

// Display login form
?>
<div style="max-width: 400px; margin: 0 auto;">
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <label for="username">Email or Phone Number:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" name="login" value="Login">
        <?php if (isset($error)) { echo '<p style="color: red;">'. $error.'</p>'; }?>
    </form>

    <div style="text-align: center; margin: 20px 0;">OR</div>

    <div style="text-align: center;">
        <a href="<?php echo $ssoLoginUrl; ?>" style="padding: 10px 20px; background: #007dc1; color: white; text-decoration: none; border-radius: 4px;">
            Sign in with Okta
        </a>
    </div>
</div>
