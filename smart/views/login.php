<?php

if (isset($_POST['username']) && isset($_POST['password'])){
    if (\smart\Application::$instance->getUser()->login($_POST['username'], $_POST['password'])){
        header('Location: '. \smart\Application::$instance->getBaseUrl().'/smart/index', true, 303);
        exit;
    } else {
        $authError = true;
    }
}
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
    <link rel="stylesheet" href="<?= $this->getBaseUrl() ?>/smart/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="<?= $this->getBaseUrl() ?>/smart/css/alert-notify.min.css">
    <link rel="stylesheet" href="<?= $this->getBaseUrl() ?>/smart/css/main.css">
</head>
<body>
<div class="lightbox"><div class="loader_img"></div></div>

<div class="login-content">
    <form class="login-form" method="post">
        <h1>Вход</h1>
        <div class="cl-form-group">
            <label class="cl-label" for="username">Имя пользователя</label>
            <input type="text" class="cl-input" name="username" id="username" required <?= isset($_POST['username']) ? 'value="'.$_POST['username'].'"' : '' ?>>
        </div>
        <div class="cl-form-group">
            <label class="cl-label" for="password">Пароль</label>
            <input type="password" class="cl-input" name="password" id="password" required>
        </div>
        <div class="cl-form-group">
            <button type="submit" class="btn btn-lg btn-main">Вход</button>
        </div>
    </form>
</div>

<script src="<?= $this->getBaseUrl() ?>/smart/js/jquery.min.js"></script>
<script src="<?= $this->getBaseUrl() ?>/smart/js/bootstrap.min.js"></script>
<script src="<?= $this->getBaseUrl() ?>/smart/js/alert-notify.min.js"></script>
<script src="<?= $this->getBaseUrl() ?>/smart/js/main.js"></script>
<?php if (isset($authError) && $authError) { ?>
    <script>
        $(document).ready(function(){
            notify.showNotification({
                type: 'error',
                text: 'Пользователь с указанными данными не найден'
            });
        });
    </script>
<?php } ?>
</body>
</html>
