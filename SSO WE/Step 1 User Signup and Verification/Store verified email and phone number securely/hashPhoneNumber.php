<?php
function hashEmail($email) {
    $salt = bin2hex(random_bytes(16));
    $hash = password_hash($email. $salt, PASSWORD_BCRYPT);
    return array('salt' => $salt, 'hash' => $hash);
}

function hashPhoneNumber($phoneNumber) {
    $salt = bin2hex(random_bytes(16));
    $hash = password_hash($phoneNumber. $salt, PASSWORD_BCRYPT);
    return array('salt' => $salt, 'hash' => $hash);
}

$email = 'user@example.com';
$phoneNumber = '1234567890';

$emailHash = hashEmail($email);
$phoneNumberHash = hashPhoneNumber($phoneNumber);

$db = new PDO('pgsql:host=localhost;dbname=database', 'username', 'password');
$stmt = $db->prepare("INSERT INTO users (email, email_salt, email_hash, phone_number, phone_number_salt, phone_number_hash) VALUES (:email, :email_salt, :email_hash, :phone_number, :phone_number_salt, :phone_number_hash)");
$stmt->bindParam(':email', $email);
$stmt->bindParam(':email_salt', $emailHash['salt']);
$stmt->bindParam(':email_hash', $emailHash['hash']);
$stmt->bindParam(':phone_number', $phoneNumber);
$stmt->bindParam(':phone_number_salt', $phoneNumberHash['salt']);
$stmt->bindParam(':phone_number_hash', $phoneNumberHash['hash']);
$stmt->execute();
?>