<?php
function generateVerificationCode() {
    $code = rand(100000, 999999); // Generate a 6-digit random code
    return $code;
}