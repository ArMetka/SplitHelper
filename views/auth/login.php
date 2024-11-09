<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include __DIR__ . '/style-login-register.css'?>
    </style>
    <title>Login</title>
</head>
<body>
<form action="/auth/login" method="post">
    <div class="container">
        <div class="header">
            <h1>Login</h1>
        </div>

        <div class="form_user_pass">
            <p>Username</p>
            <input type="text" name="username" placeholder="Login" required>
        </div>

        <div class="form_user_pass">
            <p>Password</p>
            <input type="password" name="password"  placeholder="Password" required>
        </div>

        <div class="button_login">
            <button type="submit">Login</button>
        </div>

        <div class="link_register">
            <p>Don't have an account? <a href="/auth/register">Register</a></p>
        </div>

        <div class="link_oauth">
            <p>Login with <a href="">Яндекс ID</a></p>
        </div>
    </div>
</form>
</body>
</html>