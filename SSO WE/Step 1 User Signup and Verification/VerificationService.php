<?php
require 'vendor/autoload.php';
use SendGrid\Mail\Mail;
use Twilio\Rest\Client;

class VerificationService {
    private $db;
    private $sendGrid;
    private $twilio;
    private $rateLimiter;

    public function __construct($db) {
        $this->db = $db;
        $this->sendGrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        $this->twilio = new Client(
            getenv('TWILIO_ACCOUNT_SID'),
            getenv('TWILIO_AUTH_TOKEN')
        );
        $this->rateLimiter = new RateLimiter();
    }

    public function sendVerificationCode($type, $recipient) {
        try {
            // Check rate limit
            if (!$this->rateLimiter->check($recipient)) {
                throw new Exception('Too many verification attempts');
            }

            // Generate verification code
            $code = $this->generateVerificationCode();
            
            // Store verification code
            $this->storeVerificationCode($type, $recipient, $code);

            // Send verification code
            if ($type === 'email') {
                $this->sendEmailVerification($recipient, $code);
            } else {
                $this->sendSmsVerification($recipient, $code);
            }

            return true;
        } catch (Exception $e) {
            error_log('Verification error: ' . $e->getMessage());
            return false;
        }
    }

    private function generateVerificationCode() {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function storeVerificationCode($type, $recipient, $code) {
        $stmt = $this->db->prepare(
            "INSERT INTO verification_codes (type, recipient, code, expires_at) 
             VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 15 MINUTE))"
        );
        $stmt->execute([$type, $recipient, $code]);
    }

    private function sendEmailVerification($email, $code) {
        $from = new SendGrid\Email(getenv('APP_NAME'), getenv('EMAIL_FROM'));
        $subject = "Verify Your Email Address";
        $to = new SendGrid\Email(null, $email);
        $content = new SendGrid\Content("text/plain", "Your verification code is: $code");

        $mail = new Mail($from, $subject, $to, $content);
        $response = $this->sendGrid->client->mail()->send()->post($mail);

        if ($response->statusCode() !== 202) {
            throw new Exception('Failed to send verification email');
        }
    }

    private function sendSmsVerification($phoneNumber, $code) {
        $message = $this->twilio->messages
            ->create($phoneNumber, [
                'from' => getenv('TWILIO_PHONE_NUMBER'),
                'body' => "Your verification code is: $code"
            ]);

        if (!$message->sid) {
            throw new Exception('Failed to send verification SMS');
        }
    }

    public function verifyCode($type, $recipient, $code) {
        try {
            // Check rate limit
            if (!$this->rateLimiter->check($recipient)) {
                throw new Exception('Too many verification attempts');
            }

            // Verify code
            $stmt = $this->db->prepare(
                "SELECT code FROM verification_codes 
                 WHERE type = ? AND recipient = ? AND expires_at > NOW() 
                 ORDER BY created_at DESC LIMIT 1"
            );
            $stmt->execute([$type, $recipient]);
            $storedCode = $stmt->fetchColumn();

            if ($storedCode === $code) {
                $this->markAsVerified($type, $recipient);
                return true;
            }

            return false;
        } catch (Exception $e) {
            error_log('Verification error: ' . $e->getMessage());
            return false;
        }
    }

    private function markAsVerified($type, $recipient) {
        $stmt = $this->db->prepare(
            "UPDATE users SET {$type}_verified = 1 WHERE {$type} = ?"
        );
        $stmt->execute([$recipient]);
    }
}

class RateLimiter {
    private $redis;

    public function __construct() {
        $this->redis = new Redis();
        $this->redis->connect(getenv('REDIS_HOST'), getenv('REDIS_PORT'));
    }

    public function check($key) {
        $count = $this->redis->incr($key);
        if ($count === 1) {
            $this->redis->expire($key, 3600);
        }
        return $count <= 5;
    }
}
