import requests

response = requests.get('https://api.twilio.com/2010-04-01/Accounts/{account_sid}/IncomingPhoneNumbers/{phone_number}.json')

print(response.json())