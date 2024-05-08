<?php

require_once 'login.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Checking login and password...

    if(!isset($_POST["admin_login"]) && !isset($_POST["admin_password"])) {
        echo '<script>alert("Login or password is not set!");';
        exit;
    }

    if(!password_verify($_POST["admin_login"] . $_POST["admin_password"], $admin_hash)) {
        echo '<script>alert("Wrong login or password!");';
        exit;
    }

    // Login set

    if($_GET["form"] == "change-login") {
        if(isset($_POST["new_login"]) && isset($_POST["repeat_new_login"])) {

            if($_POST["new_login"] != $_POST["repeat_new_login"]) {
                echo '<script>alert("Logins does not match!");';
                exit;
            }

            file_put_contents("../config/admin.hash", password_hash($_POST["new_login"] . $_POST["admin_password"], null));

            echo '<script>alert("Saved!")</script>';

        }
    }

    // Password set

    if($_GET["form"] == "change-password") {
        if(isset($_POST["new_password"]) && isset($_POST["repeat_new_password"])) {

            if($_POST["new_password"] != $_POST["repeat_new_password"]) {
                echo '<script>alert("Passwords does not match!");';
                exit;
            } 

            file_put_contents("../config/admin.hash", password_hash($_POST["admin_login"] . $_POST["new_password"], null));

            echo '<script>alert("Saved!")</script>';

        }
    }
}

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
        <form id="change-login" class="form" action="./admin-edit.php?form=change-login#change-login" method="POST">
            <span class="form-title">Админ: Сменить логин</span>
            <div class="form-inputs">
                <label class="form-label">Логин<span class="form-label-needed">*</span>:</label>
                <input type="text" name="admin_login" class="form-input" required>
                <label class="form-label">Пароль<span class="form-label-needed">*</span>:</label>
                <input type="password" name="admin_password" class="form-input" required>
                <label class="form-label">Новый логин<span class="form-label-needed">*</span>:</label>
                <input type="text" name="new_login" class="form-input" required>
                <label class="form-label">Повторите новый логин<span class="form-label-needed">*</span>:</label>
                <input type="text" name="repeat_new_login" class="form-input" required>
                <input type="submit" class="form-input form-submit" value="Сохранить">
            </div>
        </form>
        <form id="change-password" class="form" action="./?form=change-password#change-password" method="POST">
            <span class="form-title">Админ: Сменить пароль</span>
            <div class="form-inputs">
                <label class="form-label">Логин<span class="form-label-needed">*</span>:</label>
                <input type="text" name="admin_login" class="form-input" required>
                <label class="form-label">Пароль<span class="form-label-needed">*</span>:</label>
                <input type="password" name="admin_password" class="form-input" required>
                <label class="form-label">Новый пароль<span class="form-label-needed">*</span>:</label>
                <input type="password" name="new_password" class="form-input" required>
                <label class="form-label">Повторите новый пароль<span class="form-label-needed">*</span>:</label>
                <input type="password" name="repeat_new_password" class="form-input" required>
                <input type="submit" class="form-input form-submit" value="Сохранить">
            </div>
        </form>
    </div>
    <hr>
    <footer>
        <?php echo $site_config->footer_text; ?>
    </footer>
</body>
</html>