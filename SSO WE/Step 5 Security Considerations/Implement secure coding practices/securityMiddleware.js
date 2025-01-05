const rateLimit = require('express-rate-limit');
const csrf = require('csurf');
const helmet = require('helmet');
const { body, validationResult } = require('express-validator');

// Rate limiting configuration
const apiLimiter = rateLimit({
  windowMs: 15 * 60 * 1000, // 15 minutes
  max: 100, // Limit each IP to 100 requests per windowMs
  message: 'Too many requests, please try again later.'
});

// CSRF protection
const csrfProtection = csrf({ cookie: true });

// Security headers
const securityHeaders = helmet({
  contentSecurityPolicy: {
    directives: {
      defaultSrc: ["'self'"],
      scriptSrc: ["'self'", "'unsafe-inline'", 'trusted.cdn.com'],
      styleSrc: ["'self'", "'unsafe-inline'", 'fonts.googleapis.com'],
      imgSrc: ["'self'", 'data:', 'trusted.cdn.com'],
      fontSrc: ["'self'", 'fonts.gstatic.com']
    }
  },
  hsts: { maxAge: 31536000, includeSubDomains: true, preload: true },
  referrerPolicy: { policy: 'same-origin' }
});

// Input validation middleware
const validateInput = (rules) => {
  return [
    ...rules,
    (req, res, next) => {
      const errors = validationResult(req);
      if (!errors.isEmpty()) {
        return res.status(400).json({ errors: errors.array() });
      }
      next();
    }
  ];
};

// Common validation rules
const userValidationRules = [
  body('username').isLength({ min: 3 }).trim().escape(),
  body('password').isLength({ min: 8 }).trim(),
  body('email').isEmail().normalizeEmail()
];

module.exports = {
  apiLimiter,
  csrfProtection,
  securityHeaders,
  validateInput,
  userValidationRules
};
