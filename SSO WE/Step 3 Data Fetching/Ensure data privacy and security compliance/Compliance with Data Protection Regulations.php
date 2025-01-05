<?php
require_once '../Step 2 SSO Implementation/Integrate SSO provider with your website/AuthenticationClient.php';
require_once 'DataAnonymizer.php';

class ConsentManager {
    private $db;
    private $anonymizer;

    public function __construct($db) {
        $this->db = $db;
        $this->anonymizer = new DataAnonymizer();
    }

    public function handleConsent($userId, $consent) {
        $consentDate = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("INSERT INTO user_consents (user_id, consent, consent_date) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $consent, $consentDate]);
    }

    public function getConsentStatus($userId) {
        $stmt = $this->db->prepare("SELECT consent FROM user_consents WHERE user_id = ? ORDER BY consent_date DESC LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function processData($userId, $userData) {
        $consent = $this->getConsentStatus($userId);
        
        if ($consent === 'yes') {
            // Process data with anonymization
            return $this->anonymizer->anonymizeUserData($userData);
        }
        return null;
    }

    public function deleteUserData($userId) {
        // Anonymize and retain only necessary data
        $stmt = $this->db->prepare("UPDATE users SET email = ?, phone_number = ? WHERE id = ?");
        $stmt->execute([
            $this->anonymizer->anonymizeEmail($userId . '@deleted.com'),
            $this->anonymizer->anonymizePhoneNumber('0000000000'),
            $userId
        ]);
        
        // Delete personal data
        $stmt = $this->db->prepare("DELETE FROM user_details WHERE user_id = ?");
        $stmt->execute([$userId]);
    }
}

// Example usage with SSO integration
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $consentManager = new ConsentManager($db);

    if (isset($_POST['consent'])) {
        $consentManager->handleConsent($userId, $_POST['consent']);
    }

    // Process data based on consent
    $userData = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'phone_number' => '123-456-7890',
        'address' => '123 Main St, Anytown, USA'
    ];
    
    $processedData = $consentManager->processData($userId, $userData);
}
?>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <h2>Data Processing Consent</h2>
    <p>We process your data in accordance with GDPR regulations. Please review our <a href="/privacy-policy">Privacy Policy</a>.</p>
    
    <label>
        <input type="radio" name="consent" value="yes" required>
        Yes, I consent to data processing
    </label>
    <br>
    <label>
        <input type="radio" name="consent" value="no" required>
        No, I do not consent to data processing
    </label>
    <br><br>
    <button type="submit">Save Consent</button>
</form>

<div>
    <h3>Data Management</h3>
    <a href="/request-data">Request My Data</a> | 
    <a href="/delete-account">Delete My Account</a>
</div>
