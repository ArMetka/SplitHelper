<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php
    $e = $this->params['exception'] ?? null ?>
    <style>
        <?php include __DIR__ . '/style-error.css'?>
    </style>
    <title>503 Service Unavailable</title>
</head>
<body>
<div class="container">
    <h1>503 Service Unavailable</h1>
    <p class="payload">Internal server error!</p>
    <p class="debug"><?php
        if (isset($e)) {
            echo 'Exception occurred on line ' . $e->getLine() . ' in file "' . $e->getFile() . '"<br>';
            echo 'Message: ' . $e->getMessage();
        } ?></p>
    <a href="/home">
        <button>Go Home</button>
    </a>
</div>
</body>
</html>