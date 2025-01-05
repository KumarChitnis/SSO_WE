<?php
namespace App\Controllers;

use App\Core\Application;
use App\Database\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate login credentials
    $db = Application::getInstance()->getDatabase();
    $query = "SELECT * FROM users WHERE email = :email";
    $user = $db->query($query, ['email' => $email])->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Start session and redirect to dashboard
        session_start();
        $_SESSION['user'] = $user['email'];
        header('Location: /dashboard.php');
        exit;
    } else {
        // Redirect back to login with an error message
        header('Location: /login.php?error=invalid_credentials');
        exit;
    }
}
