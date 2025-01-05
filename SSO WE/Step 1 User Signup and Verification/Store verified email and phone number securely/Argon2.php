<?php
function hashEmail($email) {
    $salt = bin2hex(random_bytes(16));
    $hash = password_hash($email. $salt, PASSWORD_ARGON2ID);
    return array('salt' => $salt, 'hash' => $hash);
}

function hashPhoneNumber($phoneNumber) {
    $salt = bin2hex(random_bytes(16));
    $hash = password_hash($phoneNumber. $salt, PASSWORD_ARGON2ID);
    return array('salt' => $salt, 'hash' => $hash);
}

// Rest of the code remains the same
?>