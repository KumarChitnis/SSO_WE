const crypto = require('crypto');
const KeyManager = require('../../Step 3 Data Fetching/Ensure data privacy and security compliance/KeyManager');
const keyManager = KeyManager.getInstance();

class SecureEncryption {
  constructor() {
    this.algorithm = 'aes-256-gcm';
  }

  async encrypt(data) {
    try {
      const iv = crypto.randomBytes(12);
      const key = await keyManager.getEncryptionKey();
      
      const cipher = crypto.createCipheriv(this.algorithm, key, iv);
      let encrypted = cipher.update(data, 'utf8', 'hex');
      encrypted += cipher.final('hex');
      const tag = cipher.getAuthTag();
      
      return Buffer.concat([iv, tag, Buffer.from(encrypted, 'hex')]).toString('base64');
    } catch (error) {
      console.error('Encryption error:', error);
      throw new Error('Failed to encrypt data');
    }
  }

  async decrypt(encryptedData) {
    try {
      const data = Buffer.from(encryptedData, 'base64');
      const iv = data.slice(0, 12);
      const tag = data.slice(12, 28);
      const encrypted = data.slice(28);
      const key = await keyManager.getEncryptionKey();
      
      const decipher = crypto.createDecipheriv(this.algorithm, key, iv);
      decipher.setAuthTag(tag);
      
      let decrypted = decipher.update(encrypted, null, 'utf8');
      decrypted += decipher.final('utf8');
      
      return decrypted;
    } catch (error) {
      console.error('Decryption error:', error);
      throw new Error('Failed to decrypt data');
    }
  }

  async rotateKeys() {
    try {
      await keyManager.rotateKeys();
    } catch (error) {
      console.error('Key rotation error:', error);
      throw new Error('Failed to rotate encryption keys');
    }
  }
}

module.exports = new SecureEncryption();
