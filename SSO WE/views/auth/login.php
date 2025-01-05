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
            <h1>Login</h1>
        </header>
        
        <main>
            <form action="/login" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?= $email ?? '' ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="error"><?= implode('<br>', $errors['email']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                    <?php if (isset($errors['password'])): ?>
                        <div class="error"><?= implode('<br>', $errors['password']) ?></div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn">Login</button>
            </form>
            
            <p>Don't have an account? <a href="/register">Register here</a></p>
        </main>
    </div>
</body>
</html>
