<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include __DIR__ . '/style-splits.css'?>
    </style>
    <title><?php echo $this->params['split']['title'] ?? 'null'?></title>
</head>
<body>

<?php
echo $this->getHeader($_SESSION['username'] ?? 'null', 'splits') ?>

<div class="container_view">
    <h1><?php
        echo $this->params['split']['title'] ?? 'null'
        ?></h1>

    <p><?php
        $dateCreated = new DateTime($this->params['split']['created_at']);
        $dateCreated->setTimeZone(new DateTimeZone('Asia/Novosibirsk'));
        $dateUpdated = new DateTime($this->params['split']['updated_at']);
        $dateUpdated->setTimeZone(new DateTimeZone('Asia/Novosibirsk'));
        $count = count($this->params['clients']);

        echo ($this->params['split']['is_public'] ? '&#x1F513; public' : '&#x1F512; private') .
            ' | created ' . $dateCreated->format('Y-m-d') . ' &mdash; ' . $dateCreated->format('H:i') .
            ' | updated ' . $dateUpdated->format('Y-m-d') . ' &mdash; ' . $dateUpdated->format('H:i') .
            ' | &#x1F464; ' . $this->params['split']['displayed_name'] .
            ' | ' . $count . ' client' . ($count !== 1 ? 's' : '');
        ?></p>

    <div class="container_btn">
        <a href="/splits/edit?s=<?php
        echo $this->params['split']['id'] ?>">
            <button class="split_btn">&#9881; Edit split</button>
        </a>

        <a href="/splits/view?s=<?php
        echo $this->params['split']['id'] ?>">
            <button class="split_btn">&#x1F441; View split</button>
        </a>

        <button id="btn-delete" class="split_btn delete_btn">&#xFF0D;Delete split</button>
    </div>

    <div class="errors">
        <p>
            <?php
            echo $_SESSION['errors']['access'] ?? '';
            unset($_SESSION['errors']['access']);
            ?>
        </p>
    </div>

    

</div>
<script>
    <?php include __DIR__ . '/script.js'?>
</script>
</body>
</html>