<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include __DIR__ . '/style-splits.css'?>
    </style>
    <title>Create split</title>
</head>
<body>

<?php
echo $this->getHeader($_SESSION['username'] ?? 'null', 'splits') ?>

<form action="/splits/create" method="post" class="split_create_form">
    <div class="container">
        <h1>Create new split</h1>

        <div class="form-text">
            <p>Title</p>
            <input type="text" name="title" placeholder="My split" required>
        </div>

        <div class="form-checkbox">
            <p>Make public?</p>
            <input type="checkbox" name="is_public">
        </div>

        <button class="split_add" type="submit">Create</button>
    </div>
</form>
</body>
</html>