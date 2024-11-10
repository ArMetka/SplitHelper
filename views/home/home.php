<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include __DIR__ . '/style-home.css'?>
    </style>
    <title>Home</title>
</head>
<body>
<header class="header">
    <div class="container">
        <div class="user-name">
            <p>
                <?php echo $_SESSION['username'] ?? 'null' ?>
            </p>
        </div>

        <div class="nav">
            <nav class="header-nav">
                <ul class="header-nav-list">
                    <li><a class="active" href="/home">home</a></li>
                    <li><a href="#!">splits</a></li>
                    <li><a href="https://github.com/ArMetka/SplitHelper" target="_blank">github</a></li>
                    <li><a href="/test">test</a></li>
                </ul>
            </nav>
        </div>

        <div class="logout">
            <a href="/auth/logout" class="btn-exit">Log out</a>
        </div>
    </div>
</header>
</body>
</html>