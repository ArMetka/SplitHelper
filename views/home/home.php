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
<?php
echo $this->getHeader($_SESSION['username'] ?? 'null', 'home') ?>

<div class="useless_button">
    <button id="useless_btn" class="glow-on-hover" type="button">Useless Button</button>
</div>

<p align="center"><iframe src="https://store.steampowered.com/widget/2694490/" width="646" height="190"></iframe></p>

<div class="gifka">
    <img src="/img?i=botani.gif" alt="botani">
</div>

<script>
    document.getElementById('useless_btn').addEventListener('click', gamble);

    function gamble() {
        const num = Math.round(Math.random() * 100);
        console.log(num);
        if (num === 69) {
            alert('V EBAL0 SEBE P0Tb1KAY D0LB4Y0B');
            window.location.href = "https://www.youtube.com/watch?v=hr7GyFM7pX4";
        }
    }
</script>
</body>
</html>