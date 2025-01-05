<?php
require_once 'Encryption.php';

class DataAnonymizer {
    private $encryption;

    public function __construct() {
        $this->encryption = new SecureEncryption();
    }

    public function anonymizeUserData($userData) {
        return [
            'name' => 'Anonymous',
            'email' => $this->anonymizeEmail($userData['email']),
            'phone_number' => $this->anonymizePhoneNumber($userData['phone_number']),
            'address' => $this->anonymizeAddress($userData['address'])
        ];
    }

    private function anonymizeEmail($email) {
        // Use HMAC with a secret key for consistent but irreversible anonymization
        return $this->encryption->generateHmac($email);
    }

    private function anonymizePhoneNumber($phoneNumber) {
        // Remove all non-numeric characters and keep only last 2 digits
        $digits = preg_replace('/[^0-9]/', '', $phoneNumber);
        return '***' . substr($digits, -2);
    }

    private function anonymizeAddress($address) {
        // Remove all street numbers and specific location details
        $parts = explode(',', $address);
        if (count($parts) > 1) {
            return 'XXXXX, ' . trim($parts[1]);
        }
        return 'XXXXX';
    }
}

// Example usage
$anonymizer = new DataAnonymizer();
$user_data = [
    'name' => 'John Doe',
    'email' => 'johndoe@example.com',
    'phone_number' => '123-456-7890',
    'address' => '123 Main St, Anytown, USA'
];

$anonymized_data = $anonymizer->anonymizeUserData($user_data);
print_r($anonymized_data);
?>
