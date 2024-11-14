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
    <?php echo $this->getHeader($_SESSION['username'] ?? 'null', 'home') ?>
    <div class="gifka">
        <img src="/img?i=botani.gif" alt="botani">
    </div>
</body>
</html>