<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><?= $title ?></h1>
            <p><?= $description ?></p>
        </header>
        
        <nav>
            <a href="/login">Login</a>
            <a href="/register">Register</a>
        </nav>
        
        <main>
            <section>
                <h2>Features</h2>
                <ul>
                    <li>Secure Single Sign-On</li>
                    <li>Email and Phone Verification</li>
                    <li>Data Privacy Compliance</li>
                    <li>Customizable Application Integration</li>
                    <li>Advanced Security Measures</li>
                </ul>
            </section>
            
            <section>
                <h2>Get Started</h2>
                <p>
                    <a href="/register" class="btn">Create an Account</a>
                    or
                    <a href="/login" class="btn">Login to Your Account</a>
                </p>
            </section>
        </main>
        
        <footer>
            <p>&copy; <?= date('Y') ?> SSO Webtool. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
