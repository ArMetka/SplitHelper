<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        <?php include __DIR__ . '/style-me.css'?>
    </style>
    <title><?php echo $_SESSION['username'] ?? 'My profile' ?></title>
</head>
<body>

<?php echo $this->getHeader($_SESSION['username'] ?? 'null', 'username') ?>

<form action="/me/update" method="post">
    <div class="body">
        <div class="info">
            <h2>Update profile data</h2>
        </div>

        <div class="errors">
            <p>
                <?php
                echo $_SESSION['errors']['updateDisplayedName'] ?? '';
                unset($_SESSION['errors']['updateDisplayedName']);
                ?>
            </p>
        </div>

        <div class="form_user_pass">
            <p>Display name</p>
            <input type="text" name="displayed_name" placeholder="<?php echo $_SESSION['username'] ?? 'null' ?>" required>
        </div>

        <div class="button_login">
            <button type="submit">Update</button>
        </div>
    </div>
</form>

</body>
</html>