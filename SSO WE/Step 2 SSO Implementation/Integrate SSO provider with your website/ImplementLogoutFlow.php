<?php
if (isset($_GET['logout'])) {
    try {
        $authClient->logout();
        // Redirect to login page
        header('Location: login.php');
        exit;
    } catch (Exception $e) {
        // Handle error
        echo 'Error: '. $e->getMessage();
    }
}