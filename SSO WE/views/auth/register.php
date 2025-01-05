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
            <h1>Register</h1>
        </header>
        
        <main>
            <form action="/register" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?= $data['email'] ?? '' ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="error"><?= implode('<br>', $errors['email']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone" value="<?= $data['phone'] ?? '' ?>" required>
                    <?php if (isset($errors['phone'])): ?>
                        <div class="error"><?= implode('<br>', $errors['phone']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                    <?php if (isset($errors['password'])): ?>
                        <div class="error"><?= implode('<br>', $errors['password']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                    <?php if (isset($errors['password_confirmation'])): ?>
                        <div class="error"><?= implode('<br>', $errors['password_confirmation']) ?></div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn">Register</button>
            </form>
            
            <p>Already have an account? <a href="/login">Login here</a></p>
        </main>
    </div>
</body>
</html>
