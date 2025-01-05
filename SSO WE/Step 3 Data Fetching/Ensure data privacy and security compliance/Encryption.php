<?php
require_once 'KeyManager.php';

class SecureEncryption {
    private $cipher = 'aes-256-gcm';
    private $keyManager;

    public function __construct() {
        $this->keyManager = KeyManager::getInstance();
    }

    public function encrypt($data) {
        $iv = random_bytes(openssl_cipher_iv_length($this->cipher));
        $key = $this->keyManager->getEncryptionKey();
        
        $encrypted = openssl_encrypt(
            $data,
            $this->cipher,
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
        
        return base64_encode($iv . $tag . $encrypted);
    }

    public function decrypt($encryptedData) {
        $data = base64_decode($encryptedData);
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = substr($data, 0, $ivLength);
        $tag = substr($data, $ivLength, 16);
        $cipherText = substr($data, $ivLength + 16);
        $key = $this->keyManager->getEncryptionKey();
        
        return openssl_decrypt(
            $cipherText,
            $this->cipher,
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
    }

    public function generateHmac($data) {
        $key = $this->keyManager->getHmacKey();
        return hash_hmac('sha256', $data, $key);
    }

    public function verifyHmac($data, $hmac) {
        $key = $this->keyManager->getHmacKey();
        return hash_equals($this->generateHmac($data), $hmac);
    }
}
