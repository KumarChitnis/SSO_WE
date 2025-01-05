const express = require('express');
const app = express();
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');

// Hash and salt password
const password = 'mysecretpassword';
const salt = bcrypt.genSaltSync(10);
const hashedPassword = bcrypt.hashSync(password, salt);

// Store hashed password securely
const user = { id: 1, password: hashedPassword };
const db = require('./db');
db.saveUser(user);

// Authenticate user using JWT
app.post('/login', (req, res) => {
  const username = req.body.username;
  const password = req.body.password;
  const user = db.getUserByUsername(username);
  if (user && bcrypt.compareSync(password, user.password)) {
    const token = jwt.sign({ userId: user.id }, 'mysecretkey', { expiresIn: '1h' });
    res.json({ token });
  } else {
    res.status(401).json({ error: 'Invalid credentials' });
  }
});

// Protect routes using JWT
app.use('/protected', (req, res, next) => {
  const token = req.header('Authorization');
  if (!token) {
    return res.status(401).json({ error: 'Unauthorized' });
  }
  jwt.verify(token, 'mysecretkey', (err, decoded) => {
    if (err) {
      return res.status(401).json({ error: 'Invalid token' });
    }
    req.userId = decoded.userId;
    next();
  });
});

app.get('/protected/data', (req, res) => {
  const userId = req.userId;
  const data = db.getDataForUser(userId);
  res.json(data);
});