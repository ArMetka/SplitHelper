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

<?php
echo $this->getHeader($_SESSION['username'] ?? 'null', 'splits') ?>

<div class="container">
    <h1>My splits</h1>

    <a href="/splits/create">
        <button class="split_add">&#xFF0B; Create new split</button>
    </a>

    <?php
    foreach ($this->params['user'] as $ownedSplit) {
        $date = new DateTime($ownedSplit['updated_at']);
        $date->setTimeZone(new DateTimeZone('Asia/Novosibirsk'));
        echo <<<HTML
<div class="split_list">
    <a class="split_link" href="/splits/edit?{$ownedSplit['id']}"><p class="split_name">{$ownedSplit['title']}</p></a>
    <p class="split_owner">👤 {$ownedSplit['displayed_name']}</p>
    <p class="split_date">{$date->format('Y-m-d')} &mdash; {$date->format('H:i')}</p>
</div>
HTML;
    }
    ?>

    <h1>Public splits</h1>

    <?php
    foreach ($this->params['public'] as $publicSplit) {
        $date = new DateTime($publicSplit['updated_at']);
        $date->setTimeZone(new DateTimeZone('Asia/Novosibirsk'));
        echo <<<HTML
<div class="split_list">
    <a class="split_link" href="/splits/edit?{$publicSplit['id']}"><p class="split_name">{$publicSplit['title']}</p></a>
    <p class="split_owner">👤 {$publicSplit['displayed_name']}</p>
    <p class="split_date">{$date->format('Y-m-d')} &mdash; {$date->format('H:i')}</p>
</div>
HTML;
    }
    ?>
</div>
</body>
</html>