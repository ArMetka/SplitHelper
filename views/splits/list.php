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
        <button class="split_add">&#xFF0B;Create new split</button>
    </a>

    <table class="split_table">
        <?php
        foreach ($this->params['user'] as $ownedSplit) {
            $date = new DateTime($ownedSplit['updated_at']);
            $date->setTimeZone(new DateTimeZone('Asia/Novosibirsk'));
            echo <<<HTML
<tr class="split_table_row">
    <td class="td1"><a href="/splits/view?s={$ownedSplit['id']}"><p class="split_name">{$ownedSplit['title']}</p></a></td>
    <td class="td2"><p class="split_owner">&#x1F464; {$ownedSplit['displayed_name']}</p></td>
    <td class="td3"><p class="split_date">{$date->format('Y-m-d')} &mdash; {$date->format('H:i')}</p></td>
</tr>
HTML;
        }
        ?>
    </table>

    <h1>Public splits</h1>

    <table class="split_table">
        <?php
        foreach ($this->params['public'] as $publicSplit) {
            $date = new DateTime($publicSplit['updated_at']);
            $date->setTimeZone(new DateTimeZone('Asia/Novosibirsk'));
            echo <<<HTML
<tr class="split_table_row">
    <td class="td1"><a href="/splits/view?s={$publicSplit['id']}"><p class="split_name">{$publicSplit['title']}</p></a></td>
    <td class="td2"><p class="split_owner">&#x1F464; {$publicSplit['displayed_name']}</p></td>
    <td class="td3"><p class="split_date">{$date->format('Y-m-d')} &mdash; {$date->format('H:i')}</p></td>
</tr>
HTML;
        }
        ?>
    </table>
</div>
</body>
</html>