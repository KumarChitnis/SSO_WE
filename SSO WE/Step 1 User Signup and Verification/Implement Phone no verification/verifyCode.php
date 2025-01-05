<?php
function verifyCode($phoneNumber, $code) {
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $stmt = $db->prepare("SELECT * FROM users WHERE phone_number = :phone_number AND verification_code = :code");
    $stmt->bindParam(':phone_number', $phoneNumber);
    $stmt->bindParam(':code', $code);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt = $db->prepare("UPDATE users SET phone_number_verified = 1 WHERE phone_number = :phone_number");
        $stmt->bindParam(':phone_number', $phoneNumber);
        $stmt->execute();
        return true;
    } else {
        return false;
    }
}