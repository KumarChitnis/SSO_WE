const fs = require('fs');
const path = require('path');
const KeyManager = require('../../Step 3 Data Fetching/Ensure data privacy and security compliance/KeyManager');
const SecureEncryption = require('../Use encryption for sensitive data transmission and storage/Encryption Algorithms for Storing Sensitive Data');
const securityMiddleware = require('../Implement secure coding practices/securityMiddleware');

class SecurityAudit {
  constructor() {
    this.keyManager = KeyManager.getInstance();
    this.encryption = SecureEncryption;
  }

  async performAudit() {
    try {
      // Check key rotation status
      const keyRotationStatus = await this.checkKeyRotation();
      
      // Verify encryption functionality
      const encryptionStatus = await this.testEncryption();
      
      // Check security middleware configuration
      const middlewareStatus = this.checkMiddleware();
      
      // Verify file permissions
      const filePermissionsStatus = this.checkFilePermissions();
      
      return {
        keyRotation: keyRotationStatus,
        encryption: encryptionStatus,
        middleware: middlewareStatus,
        filePermissions: filePermissionsStatus
      };
    } catch (error) {
      console.error('Security audit failed:', error);
      throw new Error('Security audit failed');
    }
  }

  async checkKeyRotation() {
    try {
      const lastRotation = await this.keyManager.getLastRotationDate();
      const daysSinceRotation = Math.floor((new Date() - lastRotation) / (1000 * 60 * 60 * 24));
      return daysSinceRotation <= 90; // Rotate keys every 90 days
    } catch (error) {
      console.error('Key rotation check failed:', error);
      return false;
    }
  }

  async testEncryption() {
    try {
      const testData = 'Security audit test data';
      const encrypted = await this.encryption.encrypt(testData);
      const decrypted = await this.encryption.decrypt(encrypted);
      return decrypted === testData;
    } catch (error) {
      console.error('Encryption test failed:', error);
      return false;
    }
  }

  checkMiddleware() {
    try {
      return securityMiddleware.apiLimiter && 
             securityMiddleware.csrfProtection && 
             securityMiddleware.securityHeaders;
    } catch (error) {
      console.error('Middleware check failed:', error);
      return false;
    }
  }

  checkFilePermissions() {
    try {
      const files = [
        path.join(__dirname, '../../config/secrets.json'),
        path.join(__dirname, '../../logs/security.log')
      ];
      
      return files.every(file => {
        const stats = fs.statSync(file);
        return (stats.mode & 0o777) === 0o600; // Check for 600 permissions
      });
    } catch (error) {
      console.error('File permissions check failed:', error);
      return false;
    }
  }
}

module.exports = new SecurityAudit();
