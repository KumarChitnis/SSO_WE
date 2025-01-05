<?php
require '../VerificationService.php';

function sendVerificationCode($phoneNumber, $code) {
    $db = new PDO('mysql:host='.getenv('DB_HOST').';dbname='.getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));
    $verificationService = new VerificationService($db);
    return $verificationService->sendVerificationCode('phone', $phoneNumber);
}
