<?php
$user_data = [
    'name' => 'John Doe',
    'email' => 'johndoe@example.com',
    'phone_number' => '123-456-7890',
    'address' => '123 Main St, Anytown, USA'
];

// Pseudonymize user data
$pseudonymized_data = [
    'name' => 'User_'. uniqid(),
    'email' => 'user_'. substr(hash('sha256', $user_data['email']), 0, 10). '@example.com',
    'phone_number' => 'XXXX-XXXX-'. substr($user_data['phone_number'], -4),
    'address' => 'XXXXX, XXXXXX, USA'
];

print_r($pseudonymized_data);
?>