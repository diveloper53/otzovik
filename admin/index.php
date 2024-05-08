<?php

require_once 'login.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/styles/main.css">
    <link rel="stylesheet" href="../templates/styles/admin.css">
    <title><?php echo $site_config->sitename; ?> — ADMIN PANEL</title>
</head>
<body>
    <header>
        <a class="sitename" href="./index.php"><?php echo $site_config->sitename_header; ?> — ADMIN PANEL</a>
    </header>
    <hr>
    <div class="forms">
        <span class="form-title">Добро пожаловать!</span>
        <div class="form">
            <a class="menu-button" href="./edit-services.php">Управление сервисами</a>
            <a class="menu-button" href="./site-edit.php">Редактировать сайт</a>
            <a class="menu-button" href="./pages-edit.php">Редактировать страницы</a>
            <a class="menu-button" href="./mysql-edit.php">Редактировать базу данных</a>
            <a class="menu-button" href="./admin-edit.php">Редактировать администратора</a>
        </div>
    </div>
    <hr>
    <footer>
        <?php echo $site_config->footer_text; ?>
    </footer>
</body>
</html>