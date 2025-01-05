const apiUrl = 'https://api.emailverificationprovider.com/v1/location';
const response = await fetch(apiUrl, {
  method: 'GET',
  headers: {
    'Authorization': 'Bearer YOUR_API_KEY',
    'Content-Type': 'application/json'
  }
});

const locationData = await response.json();