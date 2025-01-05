// Add event listener to the form submission
document.getElementById('signup-form').addEventListener('submit', function(event) {
    event.preventDefault();

    // Get the email and phone number values
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;

    // Validate the input values
    if (!validateEmail(email) ||!validatePhone(phone)) {
        alert('Please enter a valid email and phone number');
        return;
    }

    // Send the form data to the server using HTTPS
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'https://example.com/signup', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`email=${email}&phone=${phone}`);
});

// Email validation function
function validateEmail(email) {
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailRegex.test(email);
}

// Phone number validation function
function validatePhone(phone) {
    const phoneRegex = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
    return phoneRegex.test(phone);
}