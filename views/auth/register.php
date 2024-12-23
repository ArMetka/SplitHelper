<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include __DIR__ . '/style-login-register.css'?>
    </style>
    <title>Register</title>
</head>
<body>
<form action="/auth/register" method="post">
    <div class="container">
        <div class="header">
            <h1>Register</h1>
        </div>

        <div class="errors">
            <p>
                <?php
                echo $_SESSION['errors']['register'] ?? '';
                unset($_SESSION['errors']['register']);
                ?>
            </p>
        </div>

        <div class="form_user_pass">
            <p>Username</p>
            <input type="text" name="username" placeholder="Login" required>
        </div>

        <div class="form_user_pass">
            <p>Password</p>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <div class="form_user_pass">
            <p>Repeat password</p>
            <input type="password" name="repeat_password" placeholder="Password" required>
        </div>

        <div class="button_login">
            <button type="submit">Register</button>
        </div>

        <div class="link_register">
            <p>Already have an account? <a href="/auth/login">Login</a></p>
        </div>

        <div class="link_oauth">
            <p>Login with <a href="">Яндекс ID</a></p>
        </div>
    </div>
</form>
</body>
</html>