<?php
function storeVerificationCode($email, $code) {
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $stmt = $db->prepare("INSERT INTO users (email, verification_code) VALUES (:email, :code)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':code', $code);
    $stmt->execute();
}