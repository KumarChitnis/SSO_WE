<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SSO Webtool</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("loginForm").addEventListener("submit", function (e) {
                e.preventDefault(); // Prevent default form submission

                const formData = new FormData(this);

                fetch("/process-login", {
                    method: "POST",
                    body: formData,
                })
                    .then((response) => {
                        if (response.redirected) {
                            window.location.href = response.url; // Redirect to dashboard
                        } else {
                            return response.json(); // Handle errors
                        }
                    })
                    .then((data) => {
                        if (data && data.error) {
                            alert("Login failed: " + data.error);
                        }
                    })
                    .catch((error) => console.error("Error:", error));
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Login</h1>
        </header>
        
        <main>
            <form id="loginForm" method="POST" action="/process-login">
                <input type="text" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit">Login</button>
            </form>
        </main>
    </div>
</body>
</html>
