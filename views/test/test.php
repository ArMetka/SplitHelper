<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test Page</title>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Login</h1>
    </div>

    <div class="form_user_pass">
        <p>Username</p>
        <input type="text" name="username" placeholder="Login" id="form_username" required>
    </div>

    <div class="form_user_pass">
        <p>Password</p>
        <input type="password" name="password" id="form_password"  placeholder="Password" required>
    </div>

    <div class="button_login">
        <button id="login-btn">Login</button>
    </div>

    <div class="link_register">
        <p>Don't have an account? <a href="/auth/register">Register</a></p>
    </div>

    <div class="link_oauth">
        <p>Login with <a href="">Яндекс ID</a></p>
    </div>
</div>

<script>
    document.getElementById('login-btn').addEventListener('click', loadTextFetch);
    async function loadTextFetch() {
        const res = await fetch('/test', {
            method: "POST",
            body: [
                username => document.getElementById('form_username').value,
                password => document.getElementById('form_password').value
            ]
        });

        console.log(await res.text());
    }
</script>
</body>
</html>