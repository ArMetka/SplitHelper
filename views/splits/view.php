<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include __DIR__ . '/style-splits.css'?>
    </style>
    <title>Home</title>
</head>
<body>

<?php echo $this->getHeader($_SESSION['username'] ?? 'null', 'splits') ?>

<div class="container">
    <h1>php</h1>

    <a href="/splits/delete?s=php">
        <button class="split_add">&#xFF0D;Delete split</button>
    </a>

    <a href="/splits/edit?s=php">
        <button class="split_add">&#9881; Edit split</button>
    </a>
</div>

</body>
</html>