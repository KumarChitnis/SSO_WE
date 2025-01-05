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
            <h1>Verify Your Email</h1>
        </header>
        
        <main>
            <form action="/verify/email" method="post">
                <div class="form-group">
                    <label for="code">Verification Code</label>
                    <input type="text" name="code" id="code" required>
                    <?php if (isset($errors['code'])): ?>
                        <div class="error"><?= implode('<br>', $errors['code']) ?></div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn">Verify Email</button>
            </form>
            
            <p>Didn't receive the code? <a href="/verify/email/resend">Resend Code</a></p>
        </main>
    </div>
</body>
</html>
