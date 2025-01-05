// Server-side (Node.js)
const https = require('https');
const fs = require('fs');

const sslOptions = {
  key: fs.readFileSync('path/to/ssl/key.pem'),
  cert: fs.readFileSync('path/to/ssl/cert.pem')
};

const server = https.createServer(sslOptions, (req, res) => {
  // Handle requests and responses
});

server.listen(443, () => {
  console.log('Server listening on port 443');
});

// Client-side (JavaScript)
const xhr = new XMLHttpRequest();
xhr.open('GET', 'https://example.com/data', true);
xhr.send();

xhr.onload = function() {
  if (xhr.status === 200) {
    console.log(xhr.responseText);
  }
};