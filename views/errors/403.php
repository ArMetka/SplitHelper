<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php
    $e = $this->params['exception'] ?? null ?>
    <title>403 Forbidden</title>
</head>
<body>
<h1>403 Forbidden</h1>
<p>You have no permission to visit this page <a href="/home">Go home</a></p>
<p><?php
    if (isset($e)) {
        echo 'Exception occurred on line ' . $e->getLine() . ' in file "' . $e->getFile() . '"<br>';
        echo 'Message: ' . $e->getMessage();
    } ?></p>
</body>
</html>