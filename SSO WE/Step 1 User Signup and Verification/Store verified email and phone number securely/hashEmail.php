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

$db = new mysqli('localhost', 'username', 'password', 'database');
if ($db->connect_error) {
    die("Connection failed: ". $db->connect_error);
}

$stmt = $db->prepare("INSERT INTO users (email, email_salt, email_hash, phone_number, phone_number_salt, phone_number_hash) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("sssssss", $email, $emailHash['salt'], $emailHash['hash'], $phoneNumber, $phoneNumberHash['salt'], $phoneNumberHash['hash']);
$stmt->execute();
$stmt->close();
$db->close();
?>