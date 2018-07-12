<?php
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Smart - панель управления сайтом</title>
    <link rel="stylesheet" href="<?= $this->getBaseUrl() ?>/smart/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $this->getBaseUrl() ?>/smart/css/main.css">
</head>
<body>
<div class="lightbox"><div class="loader_img"></div></div>

<header>
    <div class="pull-left">Smart - панель управления сайтом</div>
    <?php if ($this->user->IsLoggedIn()) { ?>
        <div class="pull-right">
            <a href="<?= $this->getBaseUrl() . '/smart/logout' ?>">Выход</a>
        </div>
    <?php } ?>
</header>

<div class="content">
    <div class="container">
        <div class="row">
            <div class="alert alert-danger text-center">
                <p>Запрашиваемая страница не найдена.</p>
                <p><a href="<?= $this->getBaseUrl() . '/smart' ?>">Вернуться на главную</a></p>
            </div>
        </div>
    </div>
</div>

<script src="<?= $this->getBaseUrl() ?>/smart/js/jquery.min.js"></script>
<script src="<?= $this->getBaseUrl() ?>/smart/js/bootstrap.min.js"></script>
</body>
</html>
