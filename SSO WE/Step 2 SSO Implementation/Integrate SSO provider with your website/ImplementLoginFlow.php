<?php
if (isset($_POST['email']) && isset($_POST['phone_number'])) {
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];

    try {
        $authResponse = $authClient->authenticate([
            'username' => $email,
            'password' => $phoneNumber,
            'options' => [
                'ultiOptionalFactorEnroll' => true,
                'warnBeforePasswordExpired' => true
            ]
        ]);

        if ($authResponse->getStatus() === 'SUCCESS') {
            // User is authenticated, redirect to protected page
            header('Location: protected.php');
            exit;
        } else {
            // Authentication failed, display error message
            echo 'Authentication failed';
        }
    } catch (Exception $e) {
        // Handle error
        echo 'Error: '. $e->getMessage();
    }
}