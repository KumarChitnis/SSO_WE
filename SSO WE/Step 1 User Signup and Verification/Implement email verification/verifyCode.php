<?php
function verifyCode($email, $code) {
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND verification_code = :code");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':code', $code);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt = $db->prepare("UPDATE users SET email_verified = 1 WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return true;
    } else {
        return false;
    }
}