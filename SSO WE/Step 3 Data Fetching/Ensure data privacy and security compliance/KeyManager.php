<?php
class KeyManager {
    private static $instance = null;
    private $encryptionKey;
    private $hmacKey;

    private function __construct() {
        // Load keys from secure storage
        $this->loadKeys();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadKeys() {
        // Check if keys exist in secure storage
        if (file_exists('keys/encryption.key') && file_exists('keys/hmac.key')) {
            $this->encryptionKey = file_get_contents('keys/encryption.key');
            $this->hmacKey = file_get_contents('keys/hmac.key');
        } else {
            // Generate new keys
            $this->generateKeys();
        }
    }

    private function generateKeys() {
        // Create keys directory if it doesn't exist
        if (!is_dir('keys')) {
            mkdir('keys', 0700, true);
        }

        // Generate secure encryption key
        $this->encryptionKey = random_bytes(32);
        file_put_contents('keys/encryption.key', $this->encryptionKey, LOCK_EX);
        chmod('keys/encryption.key', 0600);

        // Generate secure HMAC key
        $this->hmacKey = random_bytes(32);
        file_put_contents('keys/hmac.key', $this->hmacKey, LOCK_EX);
        chmod('keys/hmac.key', 0600);
    }

    public function getEncryptionKey() {
        return $this->encryptionKey;
    }

    public function getHmacKey() {
        return $this->hmacKey;
    }

    public function rotateKeys() {
        $this->generateKeys();
    }
}
