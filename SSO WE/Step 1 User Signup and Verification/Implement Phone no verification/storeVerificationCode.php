<?php
function storeVerificationCode($phoneNumber, $code) {
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $stmt = $db->prepare("INSERT INTO users (phone_number, verification_code) VALUES (:phone_number, :code)");
    $stmt->bindParam(':phone_number', $phoneNumber);
    $stmt->bindParam(':code', $code);
    $stmt->execute();
}